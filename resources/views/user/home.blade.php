@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, {{ optional(Auth::user())->name ?? 'User' }}</h1>
        <p class="text-sm text-gray-500">This is your dashboard.</p>
    </header>

    <section class="bg-white p-6 rounded shadow-sm">
        <p class="text-gray-700">Use the sidebar to navigate between pages.</p>
    </section>
@endsection