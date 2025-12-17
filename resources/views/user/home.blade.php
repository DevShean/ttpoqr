@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ optional(Auth::user()->profile)->fname ?? Auth::user()->email }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </header>

    <!-- Stats Dashboard -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- QR Codes Generated -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">QR Codes Generated</p>
                    <p class="text-3xl font-bold mt-2">{{ $qrCount ?? 0 }}</p>
                </div>
                <svg class="w-12 h-12 text-blue-300 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zm-10 8h8v8H3v-8zm2 2v4h4v-4H5zm10 0h4v4h-4v-4zm2-2h2v2h-2v-2z"/>
                </svg>
            </div>
        </div>

        <!-- Appointment Requests -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Appointment Requests</p>
                    <p class="text-3xl font-bold mt-2">{{ $appointmentCount ?? 0 }}</p>
                </div>
                <svg class="w-12 h-12 text-purple-300 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.414 2.586a2 2 0 0 0-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 0 0 0-2.828z"/>
                    <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h4a1 1 0 0 1 0 2H4v10h10v-4a1 1 0 1 1 2 0v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        <!-- Pending Appointments -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Pending Requests</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingCount ?? 0 }}</p>
                </div>
                <svg class="w-12 h-12 text-green-300 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Quick Actions</h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('qr.generate') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600 group-hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Generate QR Code</p>
                        <p class="text-xs text-gray-500">Create a new QR code for check-in</p>
                    </div>
                </a>

                <a href="{{ route('appointment.show') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600 group-hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Request Appointment</p>
                        <p class="text-xs text-gray-500">Schedule a new appointment</p>
                    </div>
                </a>

                <a href="{{ route('user.profile') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-indigo-600 group-hover:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2m-3 7h3m-3 4h3"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">My Profile</p>
                        <p class="text-xs text-gray-500">View your profile information</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Status Overview</h2>
            </div>
            <div class="p-6 space-y-4">
                <!-- Approved -->
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Approved</p>
                        <p class="text-xs text-gray-500">Confirmed appointments</p>
                    </div>
                    <span class="text-2xl font-bold text-green-600">{{ $approvedCount ?? 0 }}</span>
                </div>

                <!-- Pending -->
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Pending</p>
                        <p class="text-xs text-gray-500">Awaiting confirmation</p>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600">{{ $pendingCount ?? 0 }}</span>
                </div>

                <!-- Rejected -->
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Rejected</p>
                        <p class="text-xs text-gray-500">Declined requests</p>
                    </div>
                    <span class="text-2xl font-bold text-red-600">{{ $rejectedCount ?? 0 }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Section 
@endsection