<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Availability;
use App\Models\AppointmentRequest;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $start = $request->query('start');
        $end = $request->query('end');

        $query = Availability::query();
        if ($start) {
            $query->where('date', '>=', $start);
        }
        if ($end) {
            $query->where('date', '<=', $end);
        }

        $availabilities = $query->orderBy('date')->get();

        // Get user's existing requests for these dates
        $requestsQuery = AppointmentRequest::where('user_id', Auth::id())
            ->with('availability');
        if ($start) {
            $requestsQuery->whereHas('availability', function($q) use ($start) {
                $q->where('date', '>=', $start);
            });
        }
        if ($end) {
            $requestsQuery->whereHas('availability', function($q) use ($end) {
                $q->where('date', '<=', $end);
            });
        }

        $requests = $requestsQuery->get();

        // Calculate cooldown info for rejected requests
        $cooldownMinutes = 3;
        $rejectedDates = [];
        foreach ($requests as $request) {
            if ($request->status === 'rejected' && $request->rejected_at) {
                $rejectedAt = $request->rejected_at;
                $cooldownUntil = $rejectedAt->addMinutes($cooldownMinutes);
                $now = now();
                
                $date = $request->availability->date->format('Y-m-d');
                $rejectedDates[$date] = [
                    'rejected_at' => $rejectedAt->toIso8601String(),
                    'cooldown_until' => $cooldownUntil->toIso8601String(),
                    'on_cooldown' => $now < $cooldownUntil,
                    'cooldown_remaining_seconds' => $now < $cooldownUntil ? $cooldownUntil->diffInSeconds($now) : 0
                ];
            }
        }

        return response()->json([
            'availabilities' => $availabilities,
            'requests' => $requests,
            'rejectedDates' => $rejectedDates
        ]);
    }
}
