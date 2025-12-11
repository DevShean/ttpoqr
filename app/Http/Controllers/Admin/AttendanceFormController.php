<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceForm;
use App\Models\AttendanceRecord;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceFormController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $forms = AttendanceForm::where('admin_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.attendance_forms.index', ['forms' => $forms]);
    }

    public function create()
    {
        return view('admin.attendance_forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activities_conducted' => 'required|string',
            'date' => 'required|date',
            'venue' => 'required|string|max:255',
        ]);

        $form = AttendanceForm::create([
            'admin_id' => Auth::id(),
            'activities_conducted' => $validated['activities_conducted'],
            'date' => $validated['date'],
            'venue' => $validated['venue'],
        ]);

        return redirect()->route('admin.attendance_forms.show', $form)->with('success', 'Attendance form created successfully');
    }

    public function show(AttendanceForm $attendanceForm)
    {
        $this->authorize('view', $attendanceForm);
        $records = $attendanceForm->records()->paginate(15);

        return view('admin.attendance_forms.show', [
            'form' => $attendanceForm,
            'records' => $records,
        ]);
    }

    public function validateQr(Request $request)
    {
        $token = $request->input('token');
        $formId = $request->input('form_id');

        if (!$token) {
            return response()->json(['valid' => false, 'message' => 'Invalid token'], 400);
        }

        // Look up QR token in qr_tokens table
        $qrToken = QrToken::where('token', $token)->first();

        if (!$qrToken) {
            return response()->json(['valid' => false, 'message' => 'QR code not found']);
        }

        // Verify QR token has a user_id (owner)
        if (!$qrToken->user_id) {
            return response()->json(['valid' => false, 'message' => 'QR code has no associated user']);
        }

        // Check if QR token owner (user) exists
        if (!$qrToken->user) {
            return response()->json(['valid' => false, 'message' => 'QR code owner not found']);
        }

        // Check if expired by status first
        if ($qrToken->status === 'expired') {
            return response()->json(['valid' => false, 'message' => 'QR code expired']);
        }

        // Check if expired by time
        if ($qrToken->expires_at <= now()->timestamp) {
            return response()->json(['valid' => false, 'message' => 'QR code expired']);
        }

        // Check if already recorded in this form
        $existing = AttendanceRecord::where('attendance_form_id', $formId)
            ->where('qr_id', $qrToken->id)
            ->first();

        if ($existing) {
            return response()->json(['valid' => false, 'message' => 'This attendee is already recorded']);
        }

        // Get user data from QR token owner
        $user = $qrToken->user;
        $profile = $user->profile;

        if (!$profile) {
            return response()->json(['valid' => false, 'message' => 'User profile not found']);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'qr_id' => $qrToken->id,
                'user_id' => $qrToken->user_id, // Track which user owns this QR
                'name' => trim($profile->fname . ' ' . $profile->mname . ' ' . $profile->lname),
                'gender' => strtolower($profile->gender ?? 'male'),
                'address' => $profile->address ?? '',
                'signature' => $user->email,
                'contact_number' => $profile->contactnum ?? '',
            ]
        ]);
    }

    public function saveRecord(Request $request)
    {
        $validated = $request->validate([
            'attendance_form_id' => 'required|exists:attendance_forms,id',
            'qr_id' => 'required|exists:qr_tokens,id',
            'name' => 'required|string',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'signature' => 'required|string',
            'family_support' => 'nullable|string',
            'contact_number' => 'required|string',
        ]);

        // Verify ownership
        $form = AttendanceForm::findOrFail($validated['attendance_form_id']);
        $this->authorize('update', $form);

        // Check if already exists
        $exists = AttendanceRecord::where('attendance_form_id', $form->id)
            ->where('qr_id', $validated['qr_id'])
            ->first();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Record already exists'], 422);
        }

        AttendanceRecord::create($validated);

        return response()->json(['success' => true, 'message' => 'Attendance recorded successfully']);
    }

    public function deleteRecord(AttendanceRecord $record)
    {
        $this->authorize('delete', $record);
        $formId = $record->attendance_form_id;
        $record->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted']);
    }

    public function destroy(AttendanceForm $form)
    {
        $this->authorize('delete', $form);
        $form->delete();

        return redirect()->route('admin.attendance_forms.index')->with('success', 'Attendance form deleted');
    }
}
