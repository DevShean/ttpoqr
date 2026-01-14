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
                                $avatarUrl = (!empty($profile?->avatar_path) && file_exists(storage_path('app/public/' . $profile->avatar_path)))
                                    ? Storage::url($profile->avatar_path)
                                    : asset('assets/img/default_pfp.jpg');
                                $avatarVer = ($profile?->updated_at ? $profile->updated_at->timestamp : time());
                            @endphp
                            <div class="w-40 h-40 rounded-full overflow-hidden mx-auto border-4 border-white shadow-lg">
                                <img src="{{ $avatarUrl }}?v={{ $avatarVer }}" alt="Profile Photo" class="w-full h-full object-cover" id="avatarPreview" onerror="this.src='{{ asset('assets/img/default_pfp.jpg') }}'" />
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

                    {{-- Avatar Upload Card (moved inside form) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Profile Photo</h3>
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input type="file" name="avatar" accept="image/*" class="sr-only" id="avatarInput" />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>

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
                                <div class="space-y-2 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Contact Number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="tel" 
                                               name="contactnum" 
                                               value="{{ old('contactnum', $profile->contactnum ?? '') }}"
                                               placeholder="09123456789"
                                               maxlength="11"
                                               pattern="[0-9]{11}"
                                               inputmode="numeric"
                                               class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 placeholder-gray-400"
                                               required 
                                               onkeydown="return /[0-9]/.test(event.key) || ['Backspace','Delete','ArrowLeft','ArrowRight','Tab'].includes(event.key)" />
                                        <button type="button"
                                                class="px-4 py-3 sm:px-6 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-medium rounded-lg shadow-sm hover:from-emerald-700 hover:to-emerald-800 transition duration-200 flex items-center justify-center sm:justify-start">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948.684l1.498 4.493a1 1 0 00.502.756l2.048 1.029a1 1 0 00.502.756l2.048 1.029a1 1 0 001.386-.27l1.498-4.493a1 1 0 00-.948-1.684H19a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                                            </svg>
                                            <span>Verify Number</span>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500">11 digits (e.g., 09123456789)</p>
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

                    {{-- Email Verification Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Email Verification</h3>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <div class="flex gap-2">
                                    @php
                                        $emailVerified = auth()->user()->email_verified_at;
                                    @endphp
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="email" 
                                               name="email"
                                               readonly
                                               value="{{ auth()->user()->email ?? '' }}" 
                                               {{ $emailVerified ? 'readonly' : '' }}
                                               class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 {{ $emailVerified ? 'bg-gray-50 text-gray-600' : '' }}" />
                                        @if(!$emailVerified)
                                            <button type="button" onclick="sendVerificationCode()"
                                                    id="verifyEmailBtn"
                                                    class="px-4 py-3 sm:px-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg shadow-sm hover:from-blue-700 hover:to-blue-800 transition duration-200 flex items-center justify-center sm:justify-start">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                <span id="verifyBtnText">Send Code</span>
                                            </button>
                                        @endif
                                    </div>
                            </div>
                            <div class="pt-2">
                                <div class="flex items-center gap-2 p-3 rounded-lg {{ $emailVerified ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                                    @if($emailVerified)
                                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-800">Email Verified</p>
                                            <p class="text-xs text-green-700">{{ $emailVerified->format('M d, Y') }}</p>
                                        </div>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800">Email Not Verified</p>
                                            <p class="text-xs text-yellow-700">Please verify your email to access all features</p>
                                        </div>
                                    @endif
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
            const profileForm = document.getElementById('profileForm');

            // Add form submission logging
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    console.log('Form submitted');
                    console.log('Avatar input files:', avatarInput?.files?.length);
                    if (avatarInput?.files?.length > 0) {
                        console.log('Avatar file:', avatarInput.files[0].name);
                    }
                });
            }

            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) {
                        console.log('Avatar file selected:', file.name, 'Size:', file.size);
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

        // Email verification with 4-digit code
        window.sendVerificationCode = function() {
            const btn = document.getElementById('verifyEmailBtn');
            const btnText = document.getElementById('verifyBtnText');
            const originalText = btnText.textContent;
            
            btn.disabled = true;
            btnText.textContent = 'Sending...';
            
            fetch('/user/send-verification-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(r => {
                if (!r.ok) {
                    if (r.status === 429) {
                        return r.json().then(data => {
                            throw new Error(data.message || 'Please wait before requesting another code');
                        });
                    }
                    throw new Error('Failed to send verification code');
                }
                return r.json();
            })
            .then(data => {
                showVerificationCodeModal();
            })
            .catch(err => {
                console.error('Error:', err);
                Swal.fire({
                    title: 'Error!',
                    text: err.message || 'Failed to send verification code. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            })
            .finally(() => {
                btn.disabled = false;
                btnText.textContent = originalText;
            });
        }

        // Show verification code modal
        window.showVerificationCodeModal = function() {
            Swal.fire({
                title: 'Enter Verification Code',
                text: 'A 4-digit code has been sent to your email. Please enter it below.',
                icon: 'info',
                html: `
                    <div style="margin: 20px 0;">
                        <input type="text" id="codeInput" class="swal2-input" placeholder="0000" maxlength="4" inputmode="numeric" style="text-align: center; font-size: 24px; letter-spacing: 12px; font-weight: bold;" />
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: true,
                confirmButtonText: 'Verify Code',
                confirmButtonColor: '#3B82F6',
                didOpen: () => {
                    const input = document.getElementById('codeInput');
                    input.focus();
                    // Only allow numbers
                    input.addEventListener('keypress', function(e) {
                        if (!/[0-9]/.test(e.key)) {
                            e.preventDefault();
                        }
                    });
                    input.addEventListener('input', function(e) {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                },
                preConfirm: () => {
                    const code = document.getElementById('codeInput').value;
                    if (!code) {
                        Swal.showValidationMessage('Please enter the verification code');
                        return false;
                    }
                    if (code.length !== 4) {
                        Swal.showValidationMessage('Code must be exactly 4 digits');
                        return false;
                    }
                    return code;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    verifyCode(result.value);
                }
            });
        }

        // Verify the 4-digit code
        window.verifyCode = function(code) {
            Swal.fire({
                title: 'Verifying...',
                text: 'Please wait while we verify your code.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: (modal) => {
                    Swal.showLoading();
                }
            });

            fetch('/user/verify-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    code: code
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.message && (data.message.includes('verified successfully') || data.message.includes('already verified'))) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your email has been verified successfully!',
                        icon: 'success',
                        confirmButtonColor: '#3B82F6'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Invalid Code',
                        text: data.message || 'The code you entered is invalid or has expired. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#EF4444',
                        confirmButtonText: 'Try Again'
                    }).then(() => {
                        showVerificationCodeModal();
                    });
                }
            })
            .catch(err => {
                console.error('Error:', err);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while verifying the code. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'Try Again'
                }).then(() => {
                    showVerificationCodeModal();
                });
            });
        }

        // Email verification function (old method - kept for compatibility)
        window.sendVerificationEmail = function() {
            const btn = document.getElementById('verifyEmailBtn');
            const btnText = document.getElementById('verifyBtnText');
            const originalText = btnText.textContent;
            
            btn.disabled = true;
            btnText.textContent = 'Sending...';
            
            // Show loading alert
            Swal.fire({
                title: 'Sending...',
                text: 'Please wait while we send the verification email.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: (modal) => {
                    Swal.showLoading();
                }
            });
            
            fetch('/user/send-verification-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(r => {
                if (!r.ok) throw new Error('Failed to send verification email');
                return r.json();
            })
            .then(data => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Verification email has been sent to your email address. Please check your inbox and click the link to verify.',
                    icon: 'success',
                    confirmButtonColor: '#3B82F6'
                });
            })
            .catch(err => {
                console.error('Error:', err);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to send verification email. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#EF4444'
                });
            })
            .finally(() => {
                btn.disabled = false;
                btnText.textContent = originalText;
            });
        }
    </script>
    @endpush
@endsection