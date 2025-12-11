@extends('admin_layout.app')

@section('title', 'Create Attendance Form')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-2 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create Attendance Form</h1>
        <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Start recording attendance for an event</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.attendance_forms.store') }}" class="space-y-6">
            @csrf

            <!-- Activities Conducted -->
            <div>
                <label for="activities_conducted" class="block text-sm font-medium text-gray-700 mb-2">Activities Conducted</label>
                <textarea id="activities_conducted" 
                          name="activities_conducted"
                          rows="4"
                          placeholder="Describe the activities conducted..."
                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('activities_conducted') border-red-500 @enderror"
                          required>{{ old('activities_conducted') }}</textarea>
                @error('activities_conducted')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="date" 
                       id="date" 
                       name="date"
                       value="{{ old('date') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('date') border-red-500 @enderror"
                       required>
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Venue -->
            <div>
                <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                <input type="text" 
                       id="venue" 
                       name="venue"
                       placeholder="e.g., Conference Room A"
                       value="{{ old('venue') }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('venue') border-red-500 @enderror"
                       required>
                @error('venue')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition active:scale-[0.98]">
                    <i class="fi fi-rr-check mr-2"></i>Create Form
                </button>
                <a href="{{ route('admin.attendance_forms.index') }}" 
                   class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
