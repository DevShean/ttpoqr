<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    use LogsAdminActions;

    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return redirect('/');
        }

        $query = User::with('profile');

        // Filter by user type
        if ($request->filled('usertype') && $request->usertype !== 'all') {
            $query->where('usertype_id', $request->usertype);
        }

        // Search by name (fname, mname, lname) or email
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('users.email', 'LIKE', $search)
                  ->orWhereHas('profile', function($sq) use ($search) {
                      $sq->where('fname', 'LIKE', $search)
                        ->orWhere('mname', 'LIKE', $search)
                        ->orWhere('lname', 'LIKE', $search);
                  });
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('users.created_at', 'ASC');
        } else {
            $query->orderBy('users.created_at', 'DESC');
        }

        $users = $query->paginate(15);

        return view('admin.users', [
            'users' => $users,
            'currentFilters' => [
                'search' => $request->get('search'),
                'usertype' => $request->get('usertype', 'all'),
                'sort' => $sort,
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'fname' => 'required|string|max:100',
            'mname' => 'required|string|max:100',
            'lname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'usertype_id' => 'required|in:1,2'
        ]);

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'usertype_id' => $validated['usertype_id']
            ]);

            // Create profile with first, middle, last names
            $user->profile()->create([
                'fname' => $validated['fname'],
                'mname' => $validated['mname'],
                'lname' => $validated['lname'],
                'contactnum' => 0,
            ]);

            // Log the user creation
            $this->logAdminAction(
                'User Created',
                'created',
                'Created user ' . $validated['email'] . ' (' . ($validated['usertype_id'] == 1 ? 'Admin' : 'User') . ')',
                'User',
                $user->id
            );

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user->load('profile')
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating user: ' . $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot delete your own account'], 400);
        }

        // Delete related data
        $user->profile()->delete();
        $user->availabilities()->delete();
        $user->appointmentRequests()->delete();
        
        // Log the deletion
        $this->logAdminAction(
            'User Deleted',
            'deleted',
            'Deleted user ' . $user->email,
            'User',
            $user->id
        );

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateType(Request $request, User $user)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot change your own role'], 400);
        }

        $validated = $request->validate([
            'usertype_id' => 'required|in:1,2'
        ]);

        $oldType = $user->usertype_id;
        $user->update(['usertype_id' => $validated['usertype_id']]);

        // Log the role change
        $this->logAdminAction(
            'User Role Updated',
            'updated',
            'Changed ' . $user->email . ' role from ' . ($oldType == 1 ? 'Admin' : 'User') . ' to ' . ($validated['usertype_id'] == 1 ? 'Admin' : 'User'),
            'User',
            $user->id
        );

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user
        ]);
    }
}
