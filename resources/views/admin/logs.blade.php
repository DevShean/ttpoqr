@extends('admin_layout.app')

@section('title', 'System Logs')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Admin Activity Log</h1>
                <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">Track all admin actions and approvals</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 md:p-6 shadow-sm mb-4 sm:mb-6 md:mb-8">
        <form method="GET" action="{{ route('admin.logs') }}" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search Logs</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search', '') }}"
                           placeholder="User, action, or message..."
                           class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Action Type Filter -->
                <div>
                    <label for="action_type" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Action Type</label>
                    <select id="action_type" 
                            name="action_type"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">All Actions</option>
                        <option value="approved" {{ request('action_type') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('action_type') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="scheduled" {{ request('action_type') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="created" {{ request('action_type') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action_type') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action_type') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="archived" {{ request('action_type') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <!-- Time Range -->
                <div>
                    <label for="days" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Time Range</label>
                    <select id="days" 
                            name="days"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="1" {{ request('days', '1') === '1' ? 'selected' : '' }}>Last 24 hours</option>
                        <option value="7" {{ request('days') === '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ request('days') === '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ request('days') === '90' ? 'selected' : '' }}>Last 90 days</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition active:scale-95">
                        <i class="fi fi-rr-search mr-1"></i>Search
                    </button>
                    <a href="{{ route('admin.logs') }}" class="flex-1 px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-300 transition text-center">
                        <i class="fi fi-rr-refresh mr-1"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <!-- Table Header with Actions -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Activity Logs</h3>
            <div class="flex items-center gap-2">
                <button type="button" onclick="downloadLogs()" title="Download logs" class="p-2 text-gray-400 hover:text-gray-600 transition">
                    <i class="fi fi-rr-download text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Description</th>
                        <th class="px-2 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($logs->count() > 0)
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                    <div class="sm:hidden">
                                        <div class="font-medium">{{ $log->created_at->format('M d, h:i A') }}</div>
                                        <div class="text-gray-500 text-xs">{{ $log->created_at->format('Y') }}</div>
                                    </div>
                                    <div class="hidden sm:block">
                                        {{ $log->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </td>
                                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-4 whitespace-nowrap">
                                    <span class="inline-flex px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full {{
                                        strtolower($log->action_type) === 'approved' ? 'bg-green-100 text-green-800' :
                                        (strtolower($log->action_type) === 'rejected' ? 'bg-red-100 text-red-800' :
                                        (strtolower($log->action_type) === 'scheduled' ? 'bg-yellow-100 text-yellow-800' :
                                        (strtolower($log->action_type) === 'deleted' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')))
                                    }}">
                                        {{ ucfirst($log->action_type) }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">
                                    <div class="max-w-24 sm:max-w-none truncate">{{ $log->action }}</div>
                                    <div class="sm:hidden text-gray-500 text-xs mt-1">
                                        {{ $log->description ? Str::limit($log->description, 40, '...') : '' }}
                                    </div>
                                </td>
                                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                    {{ $log->description ? Str::limit($log->description, 60, '...') : '-' }}
                                </td>
                                <td class="px-2 sm:px-4 md:px-6 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                    @if($log->admin)
                                        <div class="max-w-20 sm:max-w-none truncate">{{ Str::limit($log->admin->email, 20, '...') }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-2 sm:px-4 md:px-6 py-8 sm:py-12 text-center text-xs sm:text-sm text-gray-500">
                                No admin actions logged
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- Log Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mt-4 sm:mt-6 md:mt-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Actions</p>
                    <p class="text-lg sm:text-2xl font-bold text-gray-900 mt-1">{{ $totalLogs ?? 0 }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-gray-100 rounded-lg">
                    <i class="fi fi-rr-document text-lg sm:text-xl text-gray-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-lg sm:text-2xl font-bold text-green-600 mt-1">{{ $approvedCount ?? 0 }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-100 rounded-lg">
                    <i class="fi fi-rr-check text-lg sm:text-xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-lg sm:text-2xl font-bold text-red-600 mt-1">{{ $rejectedCount ?? 0 }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-100 rounded-lg">
                    <i class="fi fi-rr-circle-xmark text-lg sm:text-xl text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Last Updated</p>
                    <p class="text-xs sm:text-sm font-medium text-gray-900 mt-1">{{ $lastUpdated ?? 'N/A' }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-100 rounded-lg">
                    <i class="fi fi-rr-clock text-lg sm:text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadLogs() {
        Swal.fire({
            title: 'Download Logs',
            text: 'Download the logs as CSV file?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Download',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3B82F6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Generate CSV from table data
                let csv = 'Timestamp,Status,Action,Description,Admin\n';

                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    if (cells.length >= 4) {
                        // Get timestamp (handle mobile format)
                        let timestamp = cells[0].textContent.trim();
                        if (timestamp.includes('\n')) {
                            // Mobile format: combine date and time
                            const parts = timestamp.split('\n');
                            timestamp = parts[0].trim() + ' ' + parts[1].trim();
                        }

                        const status = cells[1].textContent.trim();
                        const actionCell = cells[2];
                        let action = actionCell.querySelector('.truncate') ? actionCell.querySelector('.truncate').textContent.trim() : actionCell.textContent.trim();
                        let description = '';

                        // Check if description is in separate column or under action
                        if (cells.length === 5) {
                            // Desktop: description in separate column
                            description = cells[3].textContent.trim();
                        } else {
                            // Mobile: description under action
                            const descDiv = actionCell.querySelector('.sm\\:hidden');
                            if (descDiv) {
                                description = descDiv.textContent.trim();
                            }
                        }

                        const admin = cells[cells.length - 1].textContent.trim();

                        csv += `"${timestamp}","${status}","${action}","${description}","${admin}"\n`;
                    }
                });

                const element = document.createElement('a');
                element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
                element.setAttribute('download', `logs-${new Date().toISOString().split('T')[0]}.csv`);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
        });
    }

    // Auto-refresh logs every 30 seconds
    setInterval(function() {
        if (document.querySelector('[data-auto-refresh="true"]')) {
            location.reload();
        }
    }, 30000);
</script>

@endsection