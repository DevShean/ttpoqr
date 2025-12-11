@extends('admin_layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-xl p-6">
        <h1 class="text-2xl font-semibold text-gray-900">Welcome, Admin</h1>
        <p class="mt-2 text-gray-600">Manage users, profiles, and QR operations here.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="text-lg font-medium text-gray-900">Overview</h2>
            <p class="mt-2 text-sm text-gray-600">Quick stats and recent activity (placeholder).</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="text-lg font-medium text-gray-900">Users</h2>
            <p class="mt-2 text-sm text-gray-600">User management shortcuts (placeholder).</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="text-lg font-medium text-gray-900">QR</h2>
            <p class="mt-2 text-sm text-gray-600">QR monitoring tools (placeholder).</p>
        </div>
    </div>
</div>
@endsection
