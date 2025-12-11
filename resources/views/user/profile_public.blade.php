@extends('layouts.app')

@section('title', 'Public Profile')

@section('content')
    <section class="bg-white p-6 rounded shadow-sm max-w-2xl">
        <h1 class="text-2xl font-bold mb-2">{{ ($profile->fname ?? '') . ' ' . ($profile->lname ?? '') }}</h1>
        <div class="text-sm text-gray-600 mb-4">Profile created: {{ $profile->profile_created ?? '' }}</div>

        <div class="space-y-2">
            <div><strong>First name:</strong> {{ $profile->fname }}</div>
            <div><strong>Middle name:</strong> {{ $profile->mname }}</div>
            <div><strong>Last name:</strong> {{ $profile->lname }}</div>
            <div><strong>Contact:</strong> {{ $profile->contactnum }}</div>
            <div><strong>Address:</strong> {{ $profile->address }}</div>
            <div><strong>City:</strong> {{ $profile->city }}</div>
            <div><strong>State:</strong> {{ $profile->state }}</div>
            <div><strong>ZIP:</strong> {{ $profile->zip }}</div>
            <div><strong>Civil status:</strong> {{ $profile->civil_status }}</div>
            <div><strong>Gender:</strong> {{ $profile->gender }}</div>
        </div>
    </section>
@endsection
