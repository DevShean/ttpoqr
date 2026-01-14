<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\AppointmentRequest;
use App\Models\Availability;
use App\Mail\AppointmentApprovedNotification;
use App\Mail\AppointmentRejectedNotification;

class AppointmentController extends Controller
{
    use LogsAdminActions;
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return redirect('/');
        }

        $query = AppointmentRequest::with(['user', 'availability']);

        // Filter by archive status
        $archive = $request->get('archive', 'active');
        if ($archive === 'archived') {
            $query->where('is_archived', true);
        } elseif ($archive === 'active') {
            $query->where('is_archived', false);
        }
        // If 'all', no filter is applied

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereHas('availability', function($q) use ($request) {
                $q->where('date', '>=', $request->from_date);
            });
        }

        if ($request->filled('to_date')) {
            $query->whereHas('availability', function($q) use ($request) {
                $q->where('date', '<=', $request->to_date);
            });
        }

        // Filter by purpose
        if ($request->filled('purpose') && $request->purpose !== 'all') {
            $query->where('purpose', $request->purpose);
        }

        // Filter by user search
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->whereHas('user', function($q) use ($search) {
                $q->where('email', 'LIKE', $search)
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
            $query->orderBy('created_at', 'ASC');
        } elseif ($sort === 'date_asc') {
            $query->join('availabilities', 'appointment_requests.availability_id', '=', 'availabilities.id')
                  ->select('appointment_requests.*')
                  ->orderBy('availabilities.date', 'ASC');
        } else {
            $query->orderBy('created_at', 'DESC');
        }

        $appointments = $query->paginate(20);

        // Get unique purposes for filter dropdown
        $purposes = AppointmentRequest::distinct()->pluck('purpose');

        return view('admin.appointments', [
            'appointments' => $appointments,
            'purposes' => $purposes,
            'currentFilters' => [
                'status' => $request->get('status', 'all'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
                'purpose' => $request->get('purpose', 'all'),
                'search' => $request->get('search'),
                'sort' => $sort,
                'archive' => $request->get('archive', 'active'),
            ]
        ]);
    }

    public function approve(Request $request, AppointmentRequest $appointmentRequest)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($appointmentRequest->status !== 'pending') {
            return response()->json(['message' => 'Only pending requests can be approved'], 400);
        }

        $validated = $request->validate([
            'appointment_time' => 'required|date_format:H:i'
        ]);

        // Check if this availability slot is still available
        $availability = $appointmentRequest->availability;
        if (!$availability->is_available) {
            return response()->json(['message' => 'This availability slot has been marked unavailable'], 400);
        }

        // Start transaction to ensure data consistency
        \DB::beginTransaction();

        try {
            // Decrement slots if they exist, otherwise mark as unavailable
            if ($availability->slots !== null && $availability->slots > 0) {
                $newSlots = $availability->slots - 1;
                $availability->update([
                    'slots' => $newSlots,
                    'is_available' => $newSlots > 0 // Mark unavailable only when slots reach 0
                ]);
            } else {
                // No slots set, just mark as unavailable (backward compatibility)
                $availability->update(['is_available' => false]);
            }

            // Mark the appointment request as approved with the time
            $appointmentRequest->update([
                'status' => 'approved',
                'appointment_time' => $validated['appointment_time']
            ]);

            // Send approval email to user
            try {
                Mail::to($appointmentRequest->user->email)->send(
                    new AppointmentApprovedNotification($appointmentRequest)
                );
            } catch (\Exception $mailError) {
                // Log the error but don't fail the approval
                \Log::warning('Failed to send appointment approval email: ' . $mailError->getMessage());
            }

            \DB::commit();

            // Log the approval action
            $this->logAdminAction(
                'Appointment Approved',
                'approved',
                'Approved appointment for ' . $appointmentRequest->user->email . ' at ' . $validated['appointment_time'],
                'AppointmentRequest',
                $appointmentRequest->id
            );

            return response()->json([
                'message' => 'Appointment approved successfully',
                'appointment' => $appointmentRequest->load(['user', 'availability'])
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Error approving appointment: ' . $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, AppointmentRequest $appointmentRequest)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($appointmentRequest->status !== 'pending') {
            return response()->json(['message' => 'Only pending requests can be rejected'], 400);
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        $appointmentRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'] ?? null,
            'rejected_at' => now()
        ]);

        // Send rejection email to user
        try {
            Mail::to($appointmentRequest->user->email)->send(
                new AppointmentRejectedNotification($appointmentRequest)
            );
        } catch (\Exception $mailError) {
            // Log the error but don't fail the rejection
            \Log::warning('Failed to send appointment rejection email: ' . $mailError->getMessage());
        }

        // Log the rejection action
        $this->logAdminAction(
            'Appointment Rejected',
            'rejected',
            'Rejected appointment for ' . $appointmentRequest->user->email . '. Reason: ' . ($validated['reason'] ?? 'No reason provided'),
            'AppointmentRequest',
            $appointmentRequest->id
        );

        return response()->json([
            'message' => 'Appointment rejected successfully',
            'appointment' => $appointmentRequest
        ]);
    }

    public function archive(Request $request, AppointmentRequest $appointmentRequest)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Archive by soft deleting or marking as archived
        $appointmentRequest->update(['is_archived' => true]);

        // Log the archive action
        $this->logAdminAction(
            'Appointment Archived',
            'archived',
            'Archived appointment for ' . $appointmentRequest->user->email,
            'AppointmentRequest',
            $appointmentRequest->id
        );

        return response()->json([
            'message' => 'Appointment archived successfully',
            'appointment' => $appointmentRequest
        ]);
    }

    public function delete(Request $request, AppointmentRequest $appointmentRequest)
    {
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Log the deletion action before deleting
        $this->logAdminAction(
            'Appointment Deleted',
            'deleted',
            'Deleted appointment for ' . $appointmentRequest->user->email,
            'AppointmentRequest',
            $appointmentRequest->id
        );

        // Permanently delete the appointment
        $appointmentRequest->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
