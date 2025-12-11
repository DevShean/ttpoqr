<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointmentRequest;
use App\Models\Availability;

class AppointmentRequestController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'purpose' => ['required', 'string', 'in:Travel Permit,NBI Certificate,Submit Clearance,Conferencing,Application on Parole,Application on Probation'],
        ]);

        // Find the availability record for this date
        $availability = Availability::where('date', $validated['date'])->first();

        // If no availability record exists, check if date is in the future (assume available)
        if (!$availability) {
            $requestDate = new \DateTime($validated['date']);
            $today = new \DateTime();
            $today->setTime(0, 0, 0);

            if ($requestDate < $today) {
                return response()->json(['message' => 'Cannot request appointments for past dates'], 400);
            }

            // Create a temporary availability record as available
            $availability = Availability::create([
                'date' => $validated['date'],
                'is_available' => true,
            ]);
        } elseif (!$availability->is_available) {
            return response()->json(['message' => 'This date is not available'], 400);
        }

        // Check if user already has a non-rejected request for this date
        $existingRequest = AppointmentRequest::where('user_id', Auth::id())
            ->where('availability_id', $availability->id)
            ->where('status', '!=', 'rejected')
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You already have a request for this date'], 400);
        }

        // Create the appointment request
        $appointmentRequest = AppointmentRequest::create([
            'user_id' => Auth::id(),
            'availability_id' => $availability->id,
            'purpose' => $validated['purpose'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Appointment request submitted successfully',
            'request' => $appointmentRequest->load('availability')
        ]);
    }
}
