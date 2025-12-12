<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\QrToken;
use App\Models\AppointmentRequest;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return redirect('/');
        }

        $userId = Auth::id();

        // Get QR code count (active or recently used)
        $qrCount = QrToken::where('user_id', $userId)->count();

        // Get total appointment requests
        $appointmentCount = AppointmentRequest::where('user_id', $userId)
            ->where('is_archived', false)
            ->count();

        // Get pending appointments
        $pendingCount = AppointmentRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->where('is_archived', false)
            ->count();

        // Get approved appointments
        $approvedCount = AppointmentRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('is_archived', false)
            ->count();

        // Get rejected appointments
        $rejectedCount = AppointmentRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->where('is_archived', false)
            ->count();

        return view('user.home', [
            'qrCount' => $qrCount,
            'appointmentCount' => $appointmentCount,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ]);
    }
}
