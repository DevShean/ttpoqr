@extends('admin_layout.app')

@section('title', 'System Logs')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">System Logs</h1>
                <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">View all system activities and events</p>
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

                <!-- Log Type Filter -->
                <div>
                    <label for="type" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Log Type</label>
                    <select id="type" 
                            name="type"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">All Types</option>
                        <option value="info" {{ request('type') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="warning" {{ request('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('type') === 'error' ? 'selected' : '' }}>Error</option>
                        <option value="success" {{ request('type') === 'success' ? 'selected' : '' }}>Success</option>
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
                        <i class="fi fi-rr-search mr-1"></i><span class="hidden sm:inline">Search</span>
                    </button>
                    <a href="{{ route('admin.logs') }}" class="flex-1 px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-300 transition text-center">
                        <i class="fi fi-rr-refresh mr-1"></i><span class="hidden sm:inline">Clear</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Terminal Log View -->
    <div class="bg-gray-900 rounded-lg border border-gray-700 shadow-sm overflow-hidden">
        <!-- Terminal Header -->
        <div class="bg-gray-800 border-b border-gray-700 px-2 sm:px-4 py-2 sm:py-3 flex items-center justify-between gap-2 overflow-x-auto">
            <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-red-500"></div>
                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-green-500"></div>
                </div>
                <span class="text-[10px] sm:text-xs lg:text-sm text-gray-400 font-mono whitespace-nowrap">logs@sys</span>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <button type="button" onclick="downloadLogs()" title="Download logs" class="p-1.5 sm:p-2 text-gray-400 hover:text-gray-200 transition active:scale-95 touch-manipulation">
                    <i class="fi fi-rr-download text-xs sm:text-sm"></i>
                </button>
                <button type="button" onclick="clearTerminal()" title="Clear terminal" class="p-1.5 sm:p-2 text-gray-400 hover:text-gray-200 transition active:scale-95 touch-manipulation">
                    <i class="fi fi-rr-trash text-xs sm:text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Terminal Content -->
        <div class="bg-gray-900 p-2 sm:p-3 md:p-4 font-mono text-[11px] sm:text-xs md:text-sm overflow-y-auto max-h-64 sm:max-h-96 md:max-h-[600px]" id="logTerminal">
            @if(isset($logs) && is_countable($logs) && count($logs) > 0)
                @foreach($logs as $log)
                    <div class="mb-2 sm:mb-3 leading-relaxed log-entry break-words" data-type="{{ strtolower($log['type'] ?? 'info') }}">
                        <span class="text-gray-500 whitespace-nowrap">[{{ substr($log['timestamp'] ?? now()->format('Y-m-d H:i:s'), 5) }}]</span>
                        <span class="log-type {{ 
                            strtolower($log['type'] ?? 'info') === 'error' ? 'text-red-400' :
                            (strtolower($log['type'] ?? 'info') === 'warning' ? 'text-yellow-400' :
                            (strtolower($log['type'] ?? 'info') === 'success' ? 'text-green-400' : 'text-blue-400'))
                        }}">
                            [{{ substr(strtoupper($log['type'] ?? 'INFO'), 0, 4) }}]
                        </span>
                        <span class="text-gray-300">
                            {{ Str::limit($log['message'] ?? 'Unknown event', 60, '...') }}
                        </span>
                        @if($log['user'] ?? null)
                            <span class="text-gray-500 hidden sm:inline">by</span>
                            <span class="text-purple-400 hidden sm:inline text-[10px] sm:text-xs md:text-sm">{{ Str::limit($log['user'], 20, '...') }}</span>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-gray-500 text-center py-4 sm:py-6">
                    <p class="text-[10px] sm:text-xs">$ ls logs/</p>
                    <p class="mt-2 text-[10px] sm:text-xs">No logs found</p>
                </div>
            @endif
            
            <!-- Prompt -->
            <div class="text-gray-400 pt-1 sm:pt-2 text-[10px] sm:text-xs">
                $ <span class="animate-pulse">_</span>
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($logs) && method_exists($logs, 'links'))
        <div class="px-3 sm:px-4 md:px-6 py-4 md:py-6 border-t border-gray-700 overflow-x-auto bg-gray-800">
            <style>
                .pagination { 
                    display: flex; 
                    justify-content: center; 
                    gap: 0.25rem;
                    flex-wrap: wrap;
                }
                .pagination a, 
                .pagination span {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.875rem;
                    border: 1px solid #374151;
                    background-color: #1f2937;
                    color: #d1d5db;
                    border-radius: 0.375rem;
                    text-decoration: none;
                    transition: all 0.2s;
                    font-family: monospace;
                }
                .pagination a:hover {
                    background-color: #374151;
                    border-color: #60a5fa;
                    color: #60a5fa;
                }
                .pagination span.active {
                    background-color: #3b82f6;
                    color: white;
                    border-color: #3b82f6;
                }
                .pagination span:disabled,
                .pagination span.disabled {
                    color: #6b7280;
                    cursor: not-allowed;
                    background-color: #111827;
                }
                @media (max-width: 640px) {
                    .pagination a, 
                    .pagination span {
                        padding: 0.375rem 0.5rem;
                        font-size: 0.75rem;
                    }
                }
            </style>
            {{ $logs->links() }}
        </div>
        @endif
    </div>

    <!-- Log Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mt-4 sm:mt-6 md:mt-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Logs</p>
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
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Errors</p>
                    <p class="text-lg sm:text-2xl font-bold text-red-600 mt-1">{{ $errorCount ?? 0 }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-100 rounded-lg">
                    <i class="fi fi-rr-circle-exclamation text-lg sm:text-xl text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Warnings</p>
                    <p class="text-lg sm:text-2xl font-bold text-yellow-600 mt-1">{{ $warningCount ?? 0 }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-yellow-100 rounded-lg">
                    <i class="fi fi-rr-exclamation text-lg sm:text-xl text-yellow-600"></i>
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
            text: 'Choose a format to download the logs',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'CSV',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3B82F6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create CSV download link
                const terminal = document.getElementById('logTerminal');
                const text = terminal.innerText;
                const element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
                element.setAttribute('download', `logs-${new Date().toISOString()}.txt`);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }
        });
    }

    function clearTerminal() {
        Swal.fire({
            title: 'Clear Terminal?',
            text: 'This will only clear the view, not delete the actual logs',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Clear',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#EF4444'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logTerminal').innerHTML = `
                    <div class="text-gray-500 text-center py-8">
                        <p>$ clear</p>
                        <p class="mt-8">Terminal cleared</p>
                    </div>
                    <div class="text-gray-400 pt-2">
                        $ <span class="animate-pulse">_</span>
                    </div>
                `;
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