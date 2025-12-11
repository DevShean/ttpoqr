<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /** Handle login submission. Returns JSON for AJAX or redirect for normal requests. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = ['email' => $data['email'], 'password' => $data['password']];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $redirect = '/';
            if ($user->usertype_id == 1) {
                $redirect = route('admin.home');
            } elseif ($user->usertype_id == 2) {
                $redirect = route('user.home');
            }

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Authenticated', 'redirect' => $redirect]);
            }

            return redirect($redirect);
        }

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
}
