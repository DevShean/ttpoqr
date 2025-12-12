@extends('admin_layout.app')

@section('title', 'Appointment Requests')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Appointment Requests</h1>
                <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">Review and manage incoming appointment requests</p>
            </div>
            <div class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg whitespace-nowrap">
                <span class="text-xs sm:text-sm font-medium text-blue-900">Total:</span>
                <span class="text-lg sm:text-xl md:text-2xl font-bold text-blue-600">{{ $appointments->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-lg sm:text-3xl font-bold text-yellow-600 mt-1 sm:mt-2">{{ count($appointments->where('status', 'pending')) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-yellow-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-hourglass text-lg sm:text-2xl text-yellow-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-lg sm:text-3xl font-bold text-green-600 mt-1 sm:mt-2">{{ count($appointments->where('status', 'approved')) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-check text-lg sm:text-2xl text-green-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-lg sm:text-3xl font-bold text-red-600 mt-1 sm:mt-2">{{ count($appointments->where('status', 'rejected')) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-cross text-lg sm:text-2xl text-red-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-lg sm:text-3xl font-bold text-blue-600 mt-1 sm:mt-2">0</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-calendar text-lg sm:text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 md:p-6 shadow-sm mb-4 sm:mb-6 md:mb-8">
        <form method="GET" action="{{ route('admin.appointments') }}" class="space-y-4 sm:space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Search User</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ $currentFilters['search'] }}"
                           placeholder="Name or email..."
                           class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" 
                            name="status"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="all" {{ $currentFilters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ $currentFilters['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $currentFilters['status'] === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $currentFilters['status'] === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Archive Filter -->
                <div>
                    <label for="archive" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Archive</label>
                    <select id="archive" 
                            name="archive"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="active" {{ $currentFilters['archive'] === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="archived" {{ $currentFilters['archive'] === 'archived' ? 'selected' : '' }}>Archived Only</option>
                        <option value="all" {{ $currentFilters['archive'] === 'all' ? 'selected' : '' }}>All (Active & Archived)</option>
                    </select>
                </div>

                <!-- Purpose Filter -->
                <div>
                    <label for="purpose" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Purpose</label>
                    <select id="purpose" 
                            name="purpose"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="all" {{ $currentFilters['purpose'] === 'all' ? 'selected' : '' }}>All Purposes</option>
                        @foreach($purposes as $purpose)
                            <option value="{{ $purpose }}" {{ $currentFilters['purpose'] === $purpose ? 'selected' : '' }}>{{ $purpose }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Sort</label>
                    <select id="sort" 
                            name="sort"
                            class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="latest" {{ $currentFilters['sort'] === 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ $currentFilters['sort'] === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="date_asc" {{ $currentFilters['sort'] === 'date_asc' ? 'selected' : '' }}>Date (Earliest)</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="sm:col-span-2 lg:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-end gap-2">
                    <button type="submit" 
                            class="flex-1 px-3 sm:px-4 py-2.5 sm:py-2 bg-blue-600 text-white text-sm sm:text-base font-medium rounded-lg hover:bg-blue-700 transition active:scale-[0.98]">
                        <i class="fi fi-rr-search mr-2"></i><span>Filter</span>
                    </button>
                    <a href="{{ route('admin.appointments') }}" 
                       class="flex-1 px-3 sm:px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 text-sm sm:text-base font-medium rounded-lg hover:bg-gray-200 transition text-center">
                        <i class="fi fi-rr-refresh mr-2"></i><span>Reset</span>
                    </a>
                </div>
            </div>

            <!-- Date Range (Collapsible) -->
            <details class="border-t border-gray-200 pt-4">
                <summary class="cursor-pointer font-medium text-gray-700 hover:text-gray-900 text-sm sm:text-base">Date Range Filter</summary>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-4">
                    <div>
                        <label for="from_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" 
                               id="from_date" 
                               name="from_date" 
                               value="{{ $currentFilters['from_date'] }}"
                               class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label for="to_date" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" 
                               id="to_date" 
                               name="to_date" 
                               value="{{ $currentFilters['to_date'] }}"
                               class="w-full px-3 sm:px-4 py-2 text-sm rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>
            </details>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        @if($appointments->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">User</th>
                            <th class="hidden sm:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Date</th>
                            <th class="hidden md:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Purpose</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Status</th>
                            <th class="hidden lg:table-cell px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Requested</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs md:text-sm font-semibold text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs md:text-sm flex-shrink-0">
                                            {{ substr($appointment->user->profile->fname ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 text-xs md:text-sm truncate">
                                                @if($appointment->user->profile)
                                                    {{ $appointment->user->profile->fname }} {{ $appointment->user->profile->lname }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500 truncate">{{ $appointment->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs md:text-sm font-medium text-gray-900">
                                        @if($appointment->availability && $appointment->availability->date)
                                            {{ $appointment->availability->date->format('M d, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        @if($appointment->availability && $appointment->availability->date)
                                            {{ $appointment->availability->date->format('l') }}
                                        @endif
                                    </p>
                                </td>
                                <td class="hidden md:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <span class="inline-block px-3 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium rounded-full">
                                        {{ $appointment->purpose }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    @if($appointment->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-medium rounded-full">
                                            <i class="fi fi-rr-hourglass text-xs hidden md:inline"></i>
                                            <span>Pending</span>
                                        </span>
                                    @elseif($appointment->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-full">
                                            <i class="fi fi-rr-check text-xs hidden md:inline"></i>
                                            <span>Approved</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 md:px-3 py-1 bg-red-50 border border-red-200 text-red-700 text-xs font-medium rounded-full">
                                            <i class="fi fi-rr-cross text-xs hidden md:inline"></i>
                                            <span>Rejected</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="hidden lg:table-cell px-4 md:px-6 py-3 md:py-4">
                                    <div class="text-xs md:text-sm text-gray-600">
                                        <span class="block">{{ $appointment->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $appointment->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="flex items-center gap-1.5">
                                        @if($appointment->status === 'pending')
                                            <button onclick="window.approveAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                                    class="px-2.5 py-1.5 bg-green-50 text-green-700 border border-green-200 text-xs font-medium rounded-lg hover:bg-green-100 transition active:scale-95">
                                                <i class="fi fi-rr-check"></i><span class="hidden md:inline ml-1">Approve</span>
                                            </button>
                                            <button onclick="window.rejectAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                                    class="px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition active:scale-95">
                                                <i class="fi fi-rr-cross"></i><span class="hidden md:inline ml-1">Reject</span>
                                            </button>
                                        @else
                                            <button onclick="window.archiveAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                                    class="px-2.5 py-1.5 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-medium rounded-lg hover:bg-purple-100 transition active:scale-95">
                                                <i class="fi fi-rr-box"></i><span class="hidden md:inline ml-1">Archive</span>
                                            </button>
                                            <button onclick="window.deleteAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                                    class="px-2.5 py-1.5 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition active:scale-95">
                                                <i class="fi fi-rr-trash"></i><span class="hidden md:inline ml-1">Delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($appointment->user->profile->fname ?? 'U', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 text-sm truncate">
                                    @if($appointment->user->profile)
                                        {{ $appointment->user->profile->fname }} {{ $appointment->user->profile->lname }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $appointment->user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Date</p>
                                <p class="text-xs text-gray-600 font-medium mt-1">
                                    @if($appointment->availability && $appointment->availability->date)
                                        {{ $appointment->availability->date->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Purpose</p>
                                <span class="inline-block px-2 py-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium rounded-full mt-1">
                                    {{ $appointment->purpose }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</p>
                            @if($appointment->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-50 border border-yellow-200 text-yellow-700 text-xs font-medium rounded-full">
                                    <i class="fi fi-rr-hourglass text-xs"></i>
                                    <span>Pending</span>
                                </span>
                            @elseif($appointment->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 border border-green-200 text-green-700 text-xs font-medium rounded-full">
                                    <i class="fi fi-rr-check text-xs"></i>
                                    <span>Approved</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 border border-red-200 text-red-700 text-xs font-medium rounded-full">
                                    <i class="fi fi-rr-cross text-xs"></i>
                                    <span>Rejected</span>
                                </span>
                            @endif
                        </div>

                        <div class="flex gap-2 pt-2">
                            @if($appointment->status === 'pending')
                                <button onclick="window.approveAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                        class="flex-1 px-3 py-2 bg-green-50 text-green-700 border border-green-200 text-xs font-medium rounded-lg hover:bg-green-100 transition text-center">
                                    <i class="fi fi-rr-check mr-1"></i>Approve
                                </button>
                                <button onclick="window.rejectAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                        class="flex-1 px-3 py-2 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition text-center">
                                    <i class="fi fi-rr-cross mr-1"></i>Reject
                                </button>
                            @else
                                <button onclick="window.archiveAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                        class="flex-1 px-3 py-2 bg-purple-50 text-purple-700 border border-purple-200 text-xs font-medium rounded-lg hover:bg-purple-100 transition text-center">
                                    <i class="fi fi-rr-box mr-1"></i>Archive
                                </button>
                                <button onclick="window.deleteAppointment({{ $appointment->id }}, '{{ addslashes($appointment->user->profile->fname ?? 'User') }} {{ addslashes($appointment->user->profile->lname ?? '') }}')"
                                        class="flex-1 px-3 py-2 bg-red-50 text-red-700 border border-red-200 text-xs font-medium rounded-lg hover:bg-red-100 transition text-center">
                                    <i class="fi fi-rr-trash mr-1"></i>Delete
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-6 py-4 sm:py-6 border-t border-gray-200 overflow-x-auto">
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
                        border: 1px solid #e5e7eb;
                        border-radius: 0.5rem;
                        text-decoration: none;
                        transition: all 0.2s;
                    }
                    .pagination a:hover {
                        background-color: #eff6ff;
                        border-color: #3b82f6;
                        color: #3b82f6;
                    }
                    .pagination span.active {
                        background-color: #3b82f6;
                        color: white;
                        border-color: #3b82f6;
                    }
                    .pagination span:disabled,
                    .pagination span.disabled {
                        color: #9ca3af;
                        cursor: not-allowed;
                    }
                    @media (max-width: 640px) {
                        .pagination a, 
                        .pagination span {
                            padding: 0.375rem 0.5rem;
                            font-size: 0.75rem;
                        }
                    }
                </style>
                {{ $appointments->links() }}
            </div>
        @else
            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-calendar text-5xl sm:text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Appointments Found</h3>
                <p class="text-xs sm:text-base text-gray-600">There are no appointment requests matching your filters.</p>
            </div>
        @endif
    </div>

<div x-data="appointmentManager()">
    <!-- Hidden CSRF token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</div>

<script>
    function appointmentManager() {
        return {
            approveAppointment(id, userName) {
                Swal.fire({
                    title: 'Approve Appointment?',
                    html: `<p>Are you sure you want to approve <strong>${userName}'s</strong> appointment request?</p>
                           <p class="text-sm text-red-600 mt-2">⚠️ This will mark the time slot as unavailable for other users.</p>
                           <label for="appointment_time" class="block text-sm font-medium text-gray-700 mt-4 mb-2">Appointment Time</label>
                           <input type="time" id="appointment_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    preConfirm: () => {
                        const time = document.getElementById('appointment_time').value;
                        if (!time) {
                            Swal.showValidationMessage('Please select an appointment time');
                            return false;
                        }
                        return time;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitApprove(id, userName, result.value);
                    }
                });
            },
            submitApprove(id, userName, appointmentTime) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/appointments/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        appointment_time: appointmentTime
                    })
                })
                .then(r => {
                    if (!r.ok) return r.json().then(e => Promise.reject(e));
                    return r.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: `${userName}'s appointment has been approved.`,
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to approve appointment',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            },
            rejectAppointment(id, userName) {
                Swal.fire({
                    title: 'Reject Appointment?',
                    html: `<p>Are you sure you want to reject <strong>${userName}'s</strong> appointment request?</p>
                           <label for="reason" class="block text-sm font-medium text-gray-700 mt-3 mb-2">Reason for rejection (optional)</label>
                           <textarea id="reason" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Enter reason..."></textarea>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Reject',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    preConfirm: () => {
                        const reason = document.getElementById('reason').value;
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitReject(id, userName, result.value);
                    }
                });
            },
            submitReject(id, userName, reason) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/appointments/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        reason: reason
                    })
                })
                .then(r => {
                    if (!r.ok) return r.json().then(e => Promise.reject(e));
                    return r.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: `${userName}'s appointment has been rejected.`,
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to reject appointment',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            },
            viewDetails(id) {
                // Could be expanded to show more detailed information
                console.log('View details for appointment', id);
            },
            archiveAppointment(id, userName) {
                Swal.fire({
                    title: 'Archive Appointment?',
                    html: `<p>Are you sure you want to archive <strong>${userName}'s</strong> appointment request?</p>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Archive',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#a855f7',
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitArchive(id, userName);
                    }
                });
            },
            submitArchive(id, userName) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/appointments/${id}/archive`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    if (!r.ok) return r.json().then(e => Promise.reject(e));
                    return r.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: `${userName}'s appointment has been archived.`,
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to archive appointment',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            },
            deleteAppointment(id, userName) {
                Swal.fire({
                    title: 'Delete Appointment?',
                    html: `<p>Are you sure you want to delete <strong>${userName}'s</strong> appointment request?</p>
                           <p class="text-sm text-red-600 mt-2">⚠️ This action cannot be undone.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submitDelete(id, userName);
                    }
                });
            },
            submitDelete(id, userName) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                fetch(`/admin/appointments/${id}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    if (!r.ok) return r.json().then(e => Promise.reject(e));
                    return r.json();
                })
                .then(data => {
                    Swal.fire({
                        title: 'Success!',
                        text: `${userName}'s appointment has been deleted.`,
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: error.message || 'Failed to delete appointment',
                        icon: 'error',
                        confirmButtonColor: '#EF4444'
                    });
                });
            }
        }
    }

    // Make functions globally available
    window.approveAppointment = function(id, userName) {
        return appointmentManager().approveAppointment(id, userName);
    };
    window.rejectAppointment = function(id, userName) {
        return appointmentManager().rejectAppointment(id, userName);
    };
    window.archiveAppointment = function(id, userName) {
        return appointmentManager().archiveAppointment(id, userName);
    };
    window.deleteAppointment = function(id, userName) {
        return appointmentManager().deleteAppointment(id, userName);
    };
    window.viewDetails = function(id) {
        return appointmentManager().viewDetails(id);
    };
</script>

@endsection
