<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Send email verification notification to the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = auth()->user();

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        // Send verification notification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification email sent successfully'
        ], 200);
    }
}
