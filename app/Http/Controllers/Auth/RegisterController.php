<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Handle signup form submission and create a new user with default usertype 2.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'usertype_id' => 2,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Account created successfully. You may now login.',
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ], 201);
        }

        return redirect()->back()->with('success', 'Account created successfully. You may now login.');
    }
}
