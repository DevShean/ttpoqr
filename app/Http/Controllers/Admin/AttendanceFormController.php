<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceForm;
use App\Models\AttendanceRecord;
use App\Models\QrToken;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceFormController extends Controller
{
    use AuthorizesRequests, LogsAdminActions;
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

        // Log the form creation
        $this->logAdminAction(
            'Attendance Form Created',
            'created',
            'Created attendance form for ' . $validated['date'] . ' at ' . $validated['venue'],
            'AttendanceForm',
            $form->id
        );

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

        $record = AttendanceRecord::create($validated);

        // Log the record creation
        $this->logAdminAction(
            'Attendance Record Added',
            'created',
            'Added attendance record for ' . $validated['name'] . ' in form ID: ' . $validated['attendance_form_id'],
            'AttendanceRecord',
            $record->id
        );

        return response()->json(['success' => true, 'message' => 'Attendance recorded successfully']);
    }

    public function deleteRecord(AttendanceRecord $record)
    {
        try {
            \Log::info('=== deleteRecord METHOD CALLED ===');
            \Log::info('Method: ' . request()->method());
            \Log::info('Path: ' . request()->path());
            \Log::info('Record ID: ' . $record->id);
            \Log::info('Record Name: ' . $record->name);
            \Log::info('Record exists in DB: ' . (AttendanceRecord::where('id', $record->id)->exists() ? 'YES' : 'NO'));
            
            \Log::info('User ID: ' . Auth::id());
            \Log::info('User type: ' . (Auth::user() ? Auth::user()->usertype_id : 'NONE'));
            
            // Check authorization
            $this->authorize('delete', $record);
            \Log::info('Authorization PASSED');
            
            // Store the data before deletion
            $recordName = $record->name;
            $formId = $record->attendance_form_id;
            $recordId = $record->id;
            
            \Log::info('About to call $record->delete()');
            
            // Delete using Eloquent
            $result = $record->delete();
            
            \Log::info('Delete method returned: ' . ($result ? 'true' : 'false'));
            
            // Check if it's actually gone
            $stillExists = AttendanceRecord::where('id', $recordId)->exists();
            \Log::info('Record still exists after delete: ' . ($stillExists ? 'YES (PROBLEM!)' : 'NO (OK)'));
            
            // Log the deletion after storing the data
            $this->logAdminAction(
                'Attendance Record Deleted',
                'deleted',
                'Deleted attendance record for ' . $recordName . ' from form ID: ' . $formId,
                'AttendanceRecord',
                $recordId
            );
            
            \Log::info('=== deleteRecord COMPLETED SUCCESSFULLY ===');

            return response()->json(['success' => true, 'message' => 'Record deleted']);
        } catch (\Exception $e) {
            \Log::error('=== deleteRecord EXCEPTION ===');
            \Log::error('Exception class: ' . get_class($e));
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            
            return response()->json([
                'success' => false, 
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(AttendanceForm $form)
    {
        \Log::info('=== DESTROY METHOD CALLED ===');
        \Log::info('Form ID: ' . $form->id);
        \Log::info('Request method: ' . request()->method());
        \Log::info('Expects JSON: ' . (request()->expectsJson() ? 'yes' : 'no'));
        
        try {
            \Log::info('Auth check - Auth::check(): ' . (Auth::check() ? 'true' : 'false'));
            if (Auth::check()) {
                \Log::info('Auth user type: ' . Auth::user()->usertype_id);
            }
            
            // Delete all related records first
            $recordCount = $form->records()->count();
            \Log::info('Deleting ' . $recordCount . ' records');
            $form->records()->delete();
            
            // Then delete the form
            \Log::info('Deleting form');
            $form->delete();
            \Log::info('Form deleted successfully');
            
            // Try to log the deletion
            try {
                $this->logAdminAction(
                    'Attendance Form Deleted',
                    'deleted',
                    'Deleted attendance form',
                    'AttendanceForm',
                    $form->id
                );
            } catch (\Exception $logError) {
                \Log::warning('Logging failed: ' . $logError->getMessage());
            }

            // Return JSON response
            return response()->json([
                'success' => true, 
                'message' => 'Attendance form deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('=== EXCEPTION IN DESTROY ===');
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadPdf(AttendanceForm $attendanceForm)
    {
        // Get the form with its records
        $form = $attendanceForm->load('records');
        
        // Create PDF from blade view
        $pdf = \PDF::loadView('admin.attendance_forms.pdf', [
            'form' => $form
        ]);

        // Log the PDF download
        try {
            $this->logAdminAction(
                'Attendance Form PDF Downloaded',
                'exported',
                'Downloaded PDF for attendance form on ' . $form->date->format('M d, Y'),
                'AttendanceForm',
                $form->id
            );
        } catch (\Exception $e) {
            \Log::warning('Failed to log PDF download: ' . $e->getMessage());
        }

        // Download the PDF
        return $pdf->download('attendance_form_' . $form->date->format('Y-m-d') . '.pdf');
    }
}
