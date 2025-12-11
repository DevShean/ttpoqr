@extends('layouts.app')

@section('title','Generate QR Code')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4">
    <div class="w-full max-w-full md:max-w-xl lg:max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Ephemeral QR Generator</h1>
            <p class="text-gray-600 mt-2">Secure, time-limited QR codes that expire automatically</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Status Indicator -->
            <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse mr-2"></div>
                        <span class="text-sm font-medium text-gray-700">Active Session</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-semibold text-gray-600">Expires: 60 sec</span>
                    </div>
                </div>
            </div>

            <!-- QR Code Display Area -->
            <div class="p-4 sm:p-6">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-4 sm:p-6 border-2 border-dashed border-gray-200 transition-all duration-300 hover:border-blue-200">
                    <div id="qr-wrapper" class="min-h-[200px] flex flex-col items-center justify-center">
                        @if($qrSvg)
                            <div class="relative">
                                <div class="w-full flex items-center justify-center transform transition-transform hover:scale-105 duration-300" style="max-width: 250px; margin: 0 auto;">
                                    {!! $qrSvg !!}
                                </div>
                                <div class="absolute -top-2 -right-2">
                                    <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full animate-pulse">
                                        LIVE
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center space-y-4">
                                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">No QR code generated yet</p>
                                <p class="text-gray-400 text-xs">Click the button below to create a new secure QR code</p>
                            </div>
                        @endif
                    </div>

                    <!-- Countdown Timer -->
                    <div id="countdownRow" class="mt-4 text-center {{ $qrSvg ? '' : 'hidden' }}">
                        <div class="inline-flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-full">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Token expires in</span>
                            <span id="countdown" class="font-mono font-semibold text-gray-900 text-sm bg-white px-3 py-1 rounded-lg shadow-sm"></span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-8 text-center">
                        @php
                            $profile = auth()->user()->profile;
                            $profileComplete = $profile && 
                                !empty($profile->fname) && 
                                !empty($profile->lname) && 
                                !empty($profile->contactnum) && 
                                !empty($profile->address) && 
                                !empty($profile->city) && 
                                !empty($profile->state) && 
                                !empty($profile->zip) && 
                                !empty($profile->civil_status) && 
                                !empty($profile->gender);
                        @endphp
                        
                        @if(!$profileComplete)
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="text-left">
                                        <p class="text-yellow-800 font-medium text-sm">Profile Incomplete</p>
                                        <p class="text-yellow-700 text-xs mt-1">Please complete your profile with personal information before generating a QR code.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <button id="generateBtn" 
                                {{ !$profileComplete ? 'disabled' : '' }}
                                class="group relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-xl transition-all duration-300 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ !$profileComplete ? 'opacity-50 cursor-not-allowed' : 'hover:from-blue-700 hover:to-blue-800 hover:-translate-y-0.5 active:translate-y-0 shadow-lg hover:shadow-xl active:shadow-md' }}">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 {{ !$profileComplete ? '' : 'group-hover:scale-110' }} transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Generate Secure QR
                            </span>
                            <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-blue-400 rounded-full opacity-0 {{ !$profileComplete ? '' : 'group-hover:opacity-100' }} transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- How it works -->
            <div class="bg-gray-50 border-t border-gray-100 px-6 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    How it works
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-semibold text-sm mr-3">1</div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">Temporary Token</h4>
                                <p class="text-gray-600 text-xs mt-1">A unique token is embedded in the QR URL</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-semibold text-sm mr-3">2</div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">Auto-Expiry</h4>
                                <p class="text-gray-600 text-xs mt-1">Token expires automatically after 60 seconds</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-semibold text-sm mr-3">3</div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">One-time Validation</h4>
                                <p class="text-gray-600 text-xs mt-1">Scanning validates and marks the token as used</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-200 transition-colors">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-semibold text-sm mr-3">4</div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">Anti-Replay Protection</h4>
                                <p class="text-gray-600 text-xs mt-1">Reuse or screenshots after expiry are rejected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 flex items-center justify-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Each QR code is cryptographically signed and time-limited
            </p>
        </div>
    </div>
</div>

<script>
    const ttlSeconds = 60;
    // Persisted state keys
    const LS_QR_SVG = 'ephemeral_qr_svg';
    const LS_EXPIRES_AT = 'ephemeral_qr_expires_at'; // milliseconds epoch
    const LS_TOKEN = 'ephemeral_qr_token';

    // Initialize expiry from server or localStorage (favor server if provided)
    let serverExpiresAtMs = {{ $expiresAt ? $expiresAt : 'null' }} ? {{ $expiresAt ? $expiresAt : 'null' }} * 1000 : null;
    let expiresAt = serverExpiresAtMs;

    let countdownInterval = null;
    const countdownEl = document.getElementById('countdown');
    const countdownRow = document.getElementById('countdownRow');
    const btn = document.getElementById('generateBtn');
    const qrWrapper = document.getElementById('qr-wrapper');

    async function notifyServerExpired(){
        try {
            const token = localStorage.getItem(LS_TOKEN);
            await fetch("{{ route('qr.expire') }}", {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token })
            });
        } catch(_) { /* silent fail */ }
    }

    function startCountdown(){
        if(countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(updateCountdown, 1000);
        countdownRow.classList.remove('hidden');
        startStatusCheck();
        updateCountdown();
    }
    
    function updateCountdown(){
        if(!expiresAt){
            countdownEl.textContent = '';
            // No active QR -> show generate button
            btn.style.display = '';
            stopStatusCheck();
            return;
        }
        const diff = expiresAt - Date.now();
        if(diff <= 0){
            stopStatusCheck();
            expireQR();
            return;
        }
        const secs = Math.floor(diff/1000);
        countdownEl.classList.remove('text-red-600', 'animate-pulse');
        const m = Math.floor(secs/60);
        const s = secs%60;
        // Show seconds prominently for 60s TTL
        countdownEl.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        
        // During active QR -> hide generate button to prevent new cache
        btn.style.display = 'none';

        // Color warning when time is low
        if(diff < 30000){ // 30 seconds
            countdownEl.classList.add('text-red-500');
        } else if(diff < 60000){ // 1 minute
            countdownEl.classList.add('text-orange-500');
        }
    }

    function expireQR(){
        countdownEl.textContent = 'EXPIRED';
        countdownEl.classList.add('text-red-600', 'animate-pulse');
        btn.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>Generate Secure QR`;
        // Clear persisted QR when expired
        try {
            localStorage.removeItem(LS_QR_SVG);
            localStorage.removeItem(LS_EXPIRES_AT);
            localStorage.removeItem(LS_TOKEN);
        } catch(_) {}
        // Show generate button again when expired
        btn.style.display = '';
        // Immediately clear QR from page
        qrWrapper.innerHTML = `
            <div class="text-center space-y-4">
                <div class="w-20 h-20 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">No QR code generated yet</p>
                <p class="text-gray-400 text-xs">Click the button below to create a new secure QR code</p>
            </div>`;
        // Notify server to mark current token expired
        notifyServerExpired();
        expiresAt = null;
        
        // Reload page after 2 seconds
        setTimeout(() => {
            location.reload();
        }, 2000);
    }

    let statusCheckInterval = null;

    function startStatusCheck(){
        // Check status every 2 seconds while QR is active
        if(statusCheckInterval) clearInterval(statusCheckInterval);
        statusCheckInterval = setInterval(checkTokenStatus, 2000);
        checkTokenStatus(); // Check immediately
    }

    function stopStatusCheck(){
        if(statusCheckInterval) clearInterval(statusCheckInterval);
        statusCheckInterval = null;
    }

    async function checkTokenStatus(){
        if(!expiresAt) return;
        
        try {
            const res = await fetch("{{ route('qr.status') }}", {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if(res.ok){
                const data = await res.json();
                // If server says no active token, mark as expired
                if(!data.has_active && expiresAt){
                    stopStatusCheck();
                    expireQR();
                }
            }
        } catch(_) { /* silent fail */ }
    }

    async function generateOrRefresh(){
        if(btn.disabled){
            Swal.fire({
                title: 'Profile Incomplete',
                text: 'Please complete your profile with all personal information before generating a QR code.',
                icon: 'warning',
                confirmButtonColor: '#3B82F6'
            }).then(() => {
                window.location.href = "{{ route('user.profile') }}";
            });
            return;
        }
        
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        
        try {
            // Add loading state
            const originalText = btn.innerHTML;
            btn.innerHTML = `<span class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generating...
            </span>`;

            const res = await fetch("{{ route('qr.refresh') }}", {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if(!res.ok){
                let msg = 'HTTP ' + res.status;
                try {
                    const err = await res.json();
                    if(err && err.error) msg = err.error + ' (' + res.status + ')';
                } catch(_) { /* ignore */ }
                throw new Error(msg);
            }
            const data = await res.json();
            
            // Fade animation for QR update
            qrWrapper.style.opacity = '0.5';
            setTimeout(() => {
                qrWrapper.innerHTML = `
                    <div class="relative">
                        <div class="w-full flex items-center justify-center transform transition-all duration-500 animate-fade-in">
                            ${data.qr_svg}
                        </div>
                        <div class="absolute -top-2 -right-2">
                            <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full animate-pulse">
                                LIVE
                            </div>
                        </div>
                    </div>`;
                qrWrapper.style.opacity = '1';
            }, 300);
            
            // If server provides an absolute expires_at, use it; else default to 60s
            expiresAt = (data.expires_at ? data.expires_at * 1000 : (Date.now() + ttlSeconds * 1000));
            // Persist new QR payload and expiry (single active cache)
            try {
                localStorage.setItem(LS_QR_SVG, data.qr_svg || '');
                localStorage.setItem(LS_EXPIRES_AT, String(expiresAt));
                if (data.token) localStorage.setItem(LS_TOKEN, data.token);
            } catch(_) {}
            
            // Keep button label as Generate Secure QR
            btn.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>Generate Secure QR`;
            
            // Hide generate button while QR is active
            btn.style.display = 'none';
            startCountdown();
            
            // Add success animation
            qrWrapper.classList.add('animate-pulse');
            setTimeout(() => qrWrapper.classList.remove('animate-pulse'), 1000);
            
        } catch(e){
            // Error state
            console.error('Failed:', e.message);
            qrWrapper.innerHTML = `
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 mx-auto bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-red-600 font-medium">Generation Failed</p>
                    <p class="text-gray-500 text-sm">Please try again</p>
                </div>`;
            
            btn.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>Try Again`;
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    }
    
    btn.addEventListener('click', generateOrRefresh);

    // Restore QR from localStorage on load if still valid
    (function restorePersistedQR(){
        if(expiresAt == null){
            try {
                const storedExp = localStorage.getItem(LS_EXPIRES_AT);
                const storedSvg = localStorage.getItem(LS_QR_SVG);
                const expMs = storedExp ? parseInt(storedExp, 10) : null;
                if(storedSvg && expMs && expMs > Date.now()){
                    expiresAt = expMs;
                    // Render stored QR
                    qrWrapper.innerHTML = `
                        <div class="relative">
                            <div class="w-full flex items-center justify-center transform transition-all duration-500 animate-fade-in">
                                ${storedSvg}
                            </div>
                            <div class="absolute -top-2 -right-2">
                                <div class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full animate-pulse">
                                    LIVE
                                </div>
                            </div>
                        </div>`;
                    // Show countdown row; hide button while active
                    countdownRow.classList.remove('hidden');
                    btn.innerHTML = `<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>Generate Secure QR`;
                    btn.style.display = 'none';
                    startCountdown();
                } else {
                    // If expired, clear persisted state
                    if(storedExp) {
                        try {
                            localStorage.removeItem(LS_QR_SVG);
                            localStorage.removeItem(LS_EXPIRES_AT);
                        } catch(_) {}
                    }
                    // No active QR -> show button
                    btn.style.display = '';
                }
            } catch(_) {}
        } else {
            // Server provided current token; start countdown (no DOM caching here)
            countdownRow.classList.remove('hidden');
            // If there is a server-provided QR, consider it active and hide button
            btn.style.display = 'none';
            startCountdown();
        }
    })();

    // If server rendered an existing token, start its countdown immediately
    // Do not start countdown until a token is generated via button

    // Add CSS for fade-in animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection