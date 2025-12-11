<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProfileController extends Controller
{
    /** Show the authenticated user's profile form */
    public function show()
    {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return redirect('/');
        }

        $user = Auth::user();
        $profile = $user->profile;

        return view('user.profile', compact('profile'));
    }

    /** Store a new profile for the authenticated user */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->usertype_id != 2) {
            return redirect('/');
        }

        $data = $request->validate([
            'fname' => 'required|string|max:100',
            'mname' => 'nullable|string|max:100',
            'lname' => 'required|string|max:100',
            'contactnum' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Annulled,Legally Separated',
            'gender' => 'nullable|in:Male,Female,Other',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = $user->id;

        $profile = Profile::where('user_id', $user->id)->first();
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('profile_avatars', 'public');
            // Delete old avatar if exists
            if ($profile && $profile->avatar_path) {
                \Storage::disk('public')->delete($profile->avatar_path);
            }
            $data['avatar_path'] = $path;
        }

        // create or update
        $profile = Profile::updateOrCreate(['user_id' => $user->id], $data);

        return redirect()->route('user.profile')->with('status', 'Profile saved.');
    }

    /** Update existing profile */
    public function update(Request $request)
    {
        return $this->store($request);
    }

    /** Delete the authenticated user's profile */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/');
        }

        if ($user->profile) {
            $user->profile->delete();
        }

        return redirect()->route('user.profile')->with('status', 'Profile deleted.');
    }

    /** Return a QR image (server-side generated PNG) */
    public function qr()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/');
        }

        $profile = $user->profile;
        if (!$profile) {
            return redirect()->route('user.profile');
        }

        $target = route('profile.view', ['id' => $profile->id], true);

        // Generate PNG binary using simplesoftwareio/simple-qrcode
        $pngData = QrCode::format('png')
            ->size(300)
            ->margin(10)
            ->encoding('UTF-8')
            ->generate($target);

        return response($pngData, 200)->header('Content-Type', 'image/png');
    }

    /** Public profile view used as QR target */
    public function publicShow($id)
    {
        $profile = Profile::where('id', $id)->firstOrFail();
        return view('user.profile_public', compact('profile'));
    }
}
