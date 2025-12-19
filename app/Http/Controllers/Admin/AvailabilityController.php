<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Availability;
use App\Models\AppointmentRequest;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $start = $request->query('start');
        $end = $request->query('end');

        $query = Availability::where('user_id', Auth::id());
        if ($start) {
            $query->where('date', '>=', $start);
        }
        if ($end) {
            $query->where('date', '<=', $end);
        }

        $availabilities = $query->orderBy('date')->get();

        // Get approved appointments that block availability
        $appointmentQuery = AppointmentRequest::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->where('is_archived', false)
            ->with('availability');
        
        if ($start) {
            $appointmentQuery->whereHas('availability', function ($q) use ($start) {
                $q->where('date', '>=', $start);
            });
        }
        if ($end) {
            $appointmentQuery->whereHas('availability', function ($q) use ($end) {
                $q->where('date', '<=', $end);
            });
        }

        $appointments = $appointmentQuery->get();
        
        // Build appointed dates with user information
        $appointedDates = $appointments->map(function($apt) {
            $user = $apt->user;
            $fullName = trim(($user->profile?->fname ?? '') . ' ' . ($user->profile?->lname ?? ''));
            return [
                'date' => $apt->availability->date->format('Y-m-d'),
                'userName' => $fullName ?: 'Unknown User',
                'status' => $apt->status
            ];
        })->toArray();

        return response()->json([
            'availabilities' => $availabilities,
            'appointedDates' => $appointedDates
        ]);
    }

    public function upsert(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'is_available' => ['required', 'boolean'],
        ]);

        $availability = Availability::updateOrCreate(
            ['user_id' => Auth::id(), 'date' => $validated['date']],
            ['is_available' => $validated['is_available']]
        );

        return response()->json($availability);
    }

    public function destroy(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        Availability::where('user_id', Auth::id())
            ->where('date', $validated['date'])
            ->delete();

        return response()->json(['status' => 'deleted']);
    }
}