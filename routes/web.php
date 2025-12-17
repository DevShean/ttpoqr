<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\QrController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserController as UserUserController;
use App\Http\Controllers\User\AvailabilityController as UserAvailabilityController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QrController as AdminQrController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AttendanceFormController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\AppointmentRequestController;

Route::get('/', function () {
    return view('landing_page');
});

// Signup endpoint used by the landing page signup form
Route::post('/signup', [RegisterController::class, 'store'])->name('signup');

// Login endpoint used by the landing page login modal
Route::post('/login', [LoginController::class, 'store'])->name('login');

// Admin and User home routes (basic closures that check auth and usertype)
Route::get('/admin/home', function () {
    if (!Auth::check() || Auth::user()->usertype_id != 1) {
        return redirect('/');
    }
    return view('admin.home');
})->name('admin.home');

// Admin section pages
Route::middleware(['web'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::post('/admin/users/{user}/delete', [UserController::class, 'delete'])->name('admin.users.delete');
    Route::post('/admin/users/{user}/type', [UserController::class, 'updateType'])->name('admin.users.updateType');

    Route::get('/admin/qr', [AdminQrController::class, 'index'])->name('admin.qr');
    Route::get('/admin/qr/tokens', [AdminQrController::class, 'getTokens'])->name('admin.qr.tokens');

    Route::get('/admin/calendar', function () {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return redirect('/');
        }
        return view('admin.calendar');
    })->name('admin.calendar');

    Route::get('/admin/appointments', [AppointmentController::class, 'index'])->name('admin.appointments');
    Route::post('/admin/appointments/{appointmentRequest}/approve', [AppointmentController::class, 'approve'])->name('admin.appointments.approve');
    Route::post('/admin/appointments/{appointmentRequest}/reject', [AppointmentController::class, 'reject'])->name('admin.appointments.reject');
    Route::post('/admin/appointments/{appointmentRequest}/archive', [AppointmentController::class, 'archive'])->name('admin.appointments.archive');
    Route::post('/admin/appointments/{appointmentRequest}/delete', [AppointmentController::class, 'delete'])->name('admin.appointments.delete');

    Route::get('/admin/attendance', [AttendanceController::class, 'index'])->name('admin.attendance');

    // Attendance Forms
    Route::get('/admin/attendance-forms', [AttendanceFormController::class, 'index'])->name('admin.attendance_forms.index');
    Route::get('/admin/attendance-forms/create', [AttendanceFormController::class, 'create'])->name('admin.attendance_forms.create');
    Route::post('/admin/attendance-forms', [AttendanceFormController::class, 'store'])->name('admin.attendance_forms.store');
    Route::get('/admin/attendance-forms/{attendanceForm}', [AttendanceFormController::class, 'show'])->name('admin.attendance_forms.show');
    Route::delete('/admin/attendance-forms/{attendanceForm}', [AttendanceFormController::class, 'destroy'])->name('admin.attendance_forms.destroy');
    Route::post('/admin/attendance-forms/validate-qr', [AttendanceFormController::class, 'validateQr'])->name('admin.attendance_forms.validateQr');
    Route::post('/admin/attendance-forms/save-record', [AttendanceFormController::class, 'saveRecord'])->name('admin.attendance_forms.saveRecord');
    Route::delete('/admin/attendance-records/{attendanceRecord}', [AttendanceFormController::class, 'deleteRecord'])->name('admin.attendance_records.delete');

    Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs');
});

Route::get('/user/home', [HomeController::class, 'index'])->name('user.home');

// User profile CRUD (protected) and public profile + QR
Route::middleware(['web'])->group(function () {
    Route::get('/user/profile', [ProfileController::class, 'show'])->name('user.profile');
    Route::post('/user/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('/user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user/profile/qr', [ProfileController::class, 'qr'])->name('profile.qr');

    // Public profile view (used as QR target)
    Route::get('/profile/{id}', [ProfileController::class, 'publicShow'])->name('profile.view');

    // Ephemeral QR code generation & validation (60s TTL)
    Route::get('/user/qr', [QrController::class, 'show'])->name('qr.generate');
    Route::get('/user/qr/refresh', [QrController::class, 'refresh'])->name('qr.refresh');
    Route::get('/user/qr/status', [QrController::class, 'checkStatus'])->name('qr.status');
    Route::get('/qr/validate', [QrController::class, 'validateToken'])->name('qr.validate');
        // Client-side expiry notification to mark token expired in DB
        Route::post('/qr/expire', [QrController::class, 'expire'])->name('qr.expire');

    // Appointment requests
    Route::get('/user/appointment', function () {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return redirect('/');
        }
        return view('user.request_appointment');
    })->name('appointment.show');
    Route::post('/user/appointment', function (Request $request) {
        if (!Auth::check() || Auth::user()->usertype_id != 2) {
            return redirect('/');
        }
        // TODO: Implement appointment store logic
        return redirect()->route('user.home')->with('status', 'Appointment request submitted successfully!');
    })->name('appointment.store');
});

// Email verification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
});

// Verification link (can be accessed by anyone via email link)
Route::get('/email/verify/{id}/{hash}', function (Request $request) {
    $user = \App\Models\User::findOrFail($request->route('id'));
    
    if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
        return redirect('/')->with('error', 'Invalid verification link');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    // If user is logged in and it's their own account, redirect to profile
    if (Auth::check() && Auth::id() === $user->id) {
        return redirect('/user/profile')->with('status', 'Email verified successfully!');
    }

    // Otherwise show success alert and redirect to login
    return view('auth.verify-success');
})->name('verification.verify');

// Logout route used by the UI
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->wantsJson() || $request->expectsJson()) {
        return response()->json(['message' => 'Logged out']);
    }

    return redirect('/');
})->name('logout');

// Admin availability API (JSON)
Route::middleware(['web'])->group(function () {
    Route::get('/admin/availability', [AvailabilityController::class, 'index'])->name('admin.availability.index');
    Route::post('/admin/availability', [AvailabilityController::class, 'upsert'])->name('admin.availability.upsert');
    Route::delete('/admin/availability', [AvailabilityController::class, 'destroy'])->name('admin.availability.destroy');
});

// User availability and appointment requests API (JSON)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/user/availability', [UserAvailabilityController::class, 'index'])->name('user.availability.index');
    Route::post('/user/appointment-request', [AppointmentRequestController::class, 'store'])->name('user.appointment.store');
    Route::post('/user/send-verification-email', [UserUserController::class, 'sendVerificationEmail'])->name('user.send-verification');
});
