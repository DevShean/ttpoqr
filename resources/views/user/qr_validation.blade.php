@extends('layouts.app')

@section('title','QR Validation')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-2xl shadow-sm border text-center">
    @if($valid)
        <h1 class="text-xl font-semibold text-green-700">Valid Token</h1>
        <p class="text-sm text-gray-600 mt-2">Access granted.</p>
        @if($user_id)
            <p class="text-xs text-gray-500 mt-2">User ID: {{ $user_id }}</p>
        @endif
    @else
        <h1 class="text-xl font-semibold text-red-600">Invalid / Expired</h1>
        <p class="text-sm text-gray-600 mt-2">{{ $reason }}</p>
    @endif
    <p class="text-xs text-gray-400 mt-6">Tokens expire after one use or 60 seconds.</p>
</div>
@endsection
