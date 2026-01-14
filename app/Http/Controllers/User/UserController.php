<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerificationCode;
use App\Mail\EmailVerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Send a 4-digit verification code to the user's email
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationCode(Request $request)
    {
        $user = auth()->user();

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        // Check if a code was recently sent (limit to 1 code per minute)
        $recentCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinute())
            ->first();

        if ($recentCode) {
            return response()->json([
                'message' => 'Please wait before requesting another code'
            ], 429);
        }

        // Delete any existing codes for this user
        EmailVerificationCode::where('user_id', $user->id)->delete();

        // Generate a random 4-digit code
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        // Store the code with expiration (10 minutes)
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send the code via email
        Mail::to($user->email)->send(new EmailVerificationCodeMail($code, $user));

        return response()->json([
            'message' => 'Verification code sent to your email'
        ], 200);
    }

    /**
     * Verify the 4-digit code
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:4|regex:/^\d+$/',
        ], [
            'code.regex' => 'Code must contain only digits',
            'code.size' => 'Code must be exactly 4 digits',
        ]);

        $user = auth()->user();

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        // Find the verification code
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 422);
        }

        // Check if the code has expired
        if ($verificationCode->isExpired()) {
            $verificationCode->delete();
            return response()->json([
                'message' => 'Verification code has expired. Please request a new one.'
            ], 422);
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        // Delete the used code
        $verificationCode->delete();

        return response()->json([
            'message' => 'Email verified successfully'
        ], 200);
    }

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
