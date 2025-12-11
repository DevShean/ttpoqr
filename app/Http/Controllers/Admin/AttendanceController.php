<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrToken;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = QrToken::with('user.profile')
            ->where('status', 'used')
            ->whereNotNull('used_at');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('used_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('used_at', '<=', $request->date_to);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('email', 'LIKE', $search)
                  ->orWhereHas('profile', function ($sq) use ($search) {
                      $sq->where('fname', 'LIKE', $search)
                        ->orWhere('mname', 'LIKE', $search)
                        ->orWhere('lname', 'LIKE', $search);
                  });
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        $query->orderBy('used_at', $sort === 'latest' ? 'desc' : 'asc');

        // Paginate
        $attendance = $query->paginate(15);

        // Calculate stats
        $stats = [
            'total' => QrToken::where('status', 'used')->count(),
            'today' => QrToken::where('status', 'used')->whereDate('used_at', Carbon::today())->count(),
            'week' => QrToken::where('status', 'used')->whereBetween('used_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'unique_users' => QrToken::where('status', 'used')->distinct('user_id')->count('user_id'),
        ];

        // Handle export
        if ($request->has('export') && $request->export === 'true') {
            return $this->exportAttendance($attendance, $request);
        }

        $currentFilters = [
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'search' => $request->search,
            'sort' => $sort,
        ];

        return view('admin.attendance', [
            'attendance' => $attendance,
            'stats' => $stats,
            'filters' => $currentFilters,
        ]);
    }

    private function exportAttendance($attendance, Request $request)
    {
        $filename = 'attendance_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($attendance, $request) {
            $file = fopen('php://output', 'w');
            
            // Write header row
            fputcsv($file, ['First Name', 'Middle Name', 'Last Name', 'Email', 'Check-in Date', 'Check-in Time']);

            // Write data rows
            foreach ($attendance as $record) {
                fputcsv($file, [
                    $record->user->profile?->fname ?? 'N/A',
                    $record->user->profile?->mname ?? '',
                    $record->user->profile?->lname ?? '',
                    $record->user->email,
                    $record->used_at->format('M d, Y'),
                    $record->used_at->format('h:i A'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
