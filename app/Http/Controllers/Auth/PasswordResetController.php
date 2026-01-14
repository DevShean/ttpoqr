<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link to user email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Reset link sent! Check your email for instructions.'
                ], 200);
            }
            return back()->with('status', trans($status));
        }

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Unable to send reset link. Please check the email address.'
            ], 404);
        }

        return back()->withErrors(['email' => trans($status)]);
    }

    /**
     * Show the password reset form
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle the password reset
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Password reset successfully. You may now login.'
                ], 200);
            }
            return redirect('/')->with('status', trans($status));
        }

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Unable to reset password. Please try again.'
            ], 422);
        }

        return back()->withErrors(['email' => trans($status)]);
    }
}
