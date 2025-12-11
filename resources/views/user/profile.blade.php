@extends('layouts.app')

@section('title', 'Profile Management')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-gray-600 mt-2">Update your personal information and profile photo</p>
        </div>

        {{-- Status Message --}}
        @if(session('status'))
            <div id="statusAlert" class="mb-6 p-4 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Profile Photo & Quick Actions --}}
            <div class="lg:col-span-1">
                {{-- Profile Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <div class="text-center mb-6">
                        <div class="relative inline-block">
                            @php
                                $avatarUrl = !empty($profile?->avatar_path)
                                    ? Storage::url($profile->avatar_path)
                                    : asset('assets/img/default-avatar.png');
                                $avatarVer = ($profile?->updated_at ? $profile->updated_at->timestamp : time());
                            @endphp
                            <div class="w-40 h-40 rounded-full overflow-hidden mx-auto border-4 border-white shadow-lg">
                                <img src="{{ $avatarUrl }}?v={{ $avatarVer }}" alt="Profile Photo" class="w-full h-full object-cover" id="avatarPreview" />
                            </div>
                            <div class="absolute bottom-2 right-2 bg-blue-500 rounded-full p-2 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h2 class="text-xl font-bold mt-4 text-gray-900">
                            {{ $profile->fname ?? '' }} {{ $profile->lname ?? '' }}
                        </h2>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->email ?? '' }}</p>
                    </div>

                    {{-- Upload Section --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Profile Photo</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-colors duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input type="file" name="avatar" accept="image/*" class="sr-only" id="avatarInput" form="profileForm" />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Stats --}}
                        <div class="pt-6 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Profile Completion</h3>
                            <div class="space-y-3">
                                @php
                                    $fields = ['fname', 'lname', 'contactnum', 'address', 'city', 'state', 'zip', 'civil_status', 'gender'];
                                    $filled = 0;
                                    foreach($fields as $field) {
                                        if (!empty($profile->$field ?? '')) $filled++;
                                    }
                                    $completion = count($fields) > 0 ? round(($filled / count($fields)) * 100) : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-medium text-blue-600">{{ $completion }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Form --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('profile.store') }}" class="space-y-8" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @if(isset($profile))
                        @method('PUT')
                    @endif

                    {{-- Personal Information Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Personal Information</h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- First Name --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        First Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="fname" value="{{ old('fname', $profile->fname ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="John" required />
                                </div>

                                {{-- Middle Name --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="mname" value="{{ old('mname', $profile->mname ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="Michael" />
                                </div>

                                {{-- Last Name --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Last Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="lname" value="{{ old('lname', $profile->lname ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="Doe" required />
                                </div>

                                {{-- Contact Number --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Contact Number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500">+63</span>
                                        </div>
                                        <input type="tel" name="contactnum" value="{{ old('contactnum', $profile->contactnum ?? '') }}"
                                               class="w-full pl-14 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                               placeholder="912 345 6789" required />
                                    </div>
                                </div>

                                {{-- Gender --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Gender
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="gender" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white">
                                        @php $g = old('gender', $profile->gender ?? '') @endphp
                                        <option value="">Select Gender</option>
                                        @foreach(['Male','Female','Other'] as $val)
                                            <option value="{{ $val }}" {{ $g===$val ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Civil Status --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Civil Status
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="civil_status" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white">
                                        @php $cs = old('civil_status', $profile->civil_status ?? '') @endphp
                                        <option value="">Select Status</option>
                                        @foreach(['Single','Married','Widowed','Annulled','Legally Separated'] as $s)
                                            <option value="{{ $s }}" {{ $cs===$s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address Information Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Address Information</h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            {{-- Address --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Complete Address
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}"
                                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                       placeholder="Street, Barangay" required />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- City --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        City
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="City" required />
                                </div>

                                {{-- State/Province --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        State/Province
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="state" value="{{ old('state', $profile->state ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="Province" required />
                                </div>

                                {{-- ZIP Code --}}
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        ZIP Code
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="zip" value="{{ old('zip', $profile->zip ?? '') }}"
                                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                           placeholder="1000" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                        <div class="text-sm text-gray-500">
                            <p>Fields marked with <span class="text-red-500">*</span> are required.</p>
                        </div>
                        <div class="flex space-x-4">
                            <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Save All Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');
            const dropArea = document.querySelector('.border-dashed');
            const statusAlert = document.getElementById('statusAlert');

            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(ev) {
                            avatarPreview.src = ev.target.result;
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (dropArea && avatarInput && avatarPreview) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    dropArea.classList.add('border-blue-400', 'bg-blue-50');
                }

                function unhighlight() {
                    dropArea.classList.remove('border-blue-400', 'bg-blue-50');
                }

                dropArea.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    avatarInput.files = files;
                    // Trigger preview
                    const event = new Event('change');
                    avatarInput.dispatchEvent(event);
                }
            }

            // Form reset function
            window.resetForm = function() {
                const form = document.getElementById('profileForm');
                if (form && confirm('Are you sure you want to reset all changes?')) {
                    form.reset();
                    const originalAvatar = "{{ $avatarUrl }}?v={{ $avatarVer }}";
                    if (avatarPreview) avatarPreview.src = originalAvatar;
                }
            }

            // Auto-hide status alert after 2 seconds
            if (statusAlert) {
                setTimeout(() => {
                    statusAlert.classList.add('opacity-0', 'transition-opacity');
                    // Remove from DOM after fade
                    setTimeout(() => statusAlert.remove(), 300);
                }, 2000);
            }
        });
    </script>
    @endpush
@endsection