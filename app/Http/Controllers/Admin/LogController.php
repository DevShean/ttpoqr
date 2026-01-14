<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->usertype_id != 1) {
            return redirect('/');
        }

        $query = AdminLog::with('admin')->orderBy('created_at', 'desc');

        // Search filter
        $search = request('search', '');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('admin', function($q) use ($search) {
                      $q->where('email', 'like', "%{$search}%");
                  });
            });
        }

        // Action type filter
        $actionType = request('action_type', '');
        if ($actionType) {
            $query->where('action_type', $actionType);
        }

        // Time range filter
        $days = request('days', 1);
        $query->where('created_at', '>=', now()->subDays($days));

        // Get paginated logs
        $logs = $query->paginate(15);

        // Calculate statistics
        $totalLogs = AdminLog::where('created_at', '>=', now()->subDays(30))->count();
        $approvedCount = AdminLog::where('action_type', 'approved')
            ->where('created_at', '>=', now()->subDays(30))->count();
        $rejectedCount = AdminLog::where('action_type', 'rejected')
            ->where('created_at', '>=', now()->subDays(30))->count();
        
        $lastLog = AdminLog::max('created_at');
        $lastUpdated = $lastLog ? \Carbon\Carbon::parse($lastLog)->format('M d, Y h:i A') : 'N/A';

        return view('admin.logs', [
            'logs' => $logs,
            'totalLogs' => $totalLogs,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'lastUpdated' => $lastUpdated,
        ]);
    }
}
