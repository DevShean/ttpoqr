@extends('admin_layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-3 sm:py-6 md:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <div>
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->profile->fname ?? 'Admin' }}</h1>
            <p class="mt-1 text-xs sm:text-sm md:text-base text-gray-600">Manage your system and monitor key metrics</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6 md:mb-8">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-blue-600">Total Users</p>
                    <p class="text-lg sm:text-2xl md:text-3xl font-bold text-blue-900 mt-1 sm:mt-2">
                        {{ \App\Models\User::count() }}
                    </p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-500 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-users text-lg sm:text-xl md:text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- QR Tokens Generated -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-green-600">QR Generated</p>
                    <p class="text-lg sm:text-2xl md:text-3xl font-bold text-green-900 mt-1 sm:mt-2">
                        {{ \App\Models\QrToken::count() }}
                    </p>
                </div>
                <div class="p-2 sm:p-3 bg-green-500 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-qrcode text-lg sm:text-xl md:text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Appointment Requests -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-purple-600">Appointments</p>
                    <p class="text-lg sm:text-2xl md:text-3xl font-bold text-purple-900 mt-1 sm:mt-2">
                        {{ \App\Models\AppointmentRequest::count() }}
                    </p>
                </div>
                <div class="p-2 sm:p-3 bg-purple-500 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-calendar text-lg sm:text-xl md:text-2xl text-white"></i>
                </div>
            </div>
        </div>

        <!-- Attendance Forms -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200 p-3 sm:p-4 md:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-orange-600">Attendance</p>
                    <p class="text-lg sm:text-2xl md:text-3xl font-bold text-orange-900 mt-1 sm:mt-2">
                        {{ \App\Models\AttendanceForm::count() }}
                    </p>
                </div>
                <div class="p-2 sm:p-3 bg-orange-500 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-clipboard text-lg sm:text-xl md:text-2xl text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-4 sm:mb-6 md:mb-8">
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3 md:gap-4">
            <!-- Users Management -->
            <a href="{{ route('admin.users') }}" 
               class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-blue-300 transition group text-center">
                <div class="p-2 sm:p-3 bg-blue-50 rounded-lg flex justify-center mb-2 sm:mb-3 group-hover:bg-blue-100 transition">
                    <i class="fi fi-rr-users text-lg sm:text-xl text-blue-600"></i>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-900 group-hover:text-blue-600 transition">Users</p>
            </a>

            <!-- Attendance Forms -->
            <a href="{{ route('admin.attendance_forms.index') }}" 
               class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4 hover:shadow-md hover:border-orange-300 transition group text-center">
                <div class="p-2 sm:p-3 bg-orange-50 rounded-lg flex justify-center mb-2 sm:mb-3 group-hover:bg-orange-100 transition">
                    <i class="fi fi-rr-clipboard text-lg sm:text-xl text-orange-600"></i>
                </div>
                <p class="text-xs sm:text-sm font-medium text-gray-900 group-hover:text-orange-600 transition">Attendance</p>
            </a>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Recent Users</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                    <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm flex-shrink-0">
                                {{ substr($user->profile->fname ?? $user->email, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                    {{ $user->profile->fname ?? 'N/A' }} {{ $user->profile->lname ?? '' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 sm:px-6 py-6 text-center">
                        <p class="text-xs sm:text-sm text-gray-500">No users yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-sm sm:text-base font-semibold text-gray-900">Recent Appointments</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse(\App\Models\AppointmentRequest::latest()->take(5)->get() as $appointment)
                    <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between gap-2 sm:gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                    {{ $appointment->user->profile->fname ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if($appointment->appointment_date)
                                        {{ $appointment->appointment_date->format('M d, Y') }}
                                    @else
                                        No date set
                                    @endif
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($appointment->status === 'approved')
                                    <span class="inline-flex px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Approved</span>
                                @elseif($appointment->status === 'rejected')
                                    <span class="inline-flex px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Rejected</span>
                                @else
                                    <span class="inline-flex px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 sm:px-6 py-6 text-center">
                        <p class="text-xs sm:text-sm text-gray-500">No appointments yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
