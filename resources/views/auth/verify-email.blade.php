@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Verify Your Email</h2>
                <p class="mt-2 text-sm text-gray-600">We've sent a verification link to your email address.</p>
            </div>

            <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">
                            Please click the link in your email to verify your email address. If you didn't receive an email, you can request a new verification link.
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Request New Verification Link
                </button>
            </form>
        </div>
    </div>
@endsection
