@extends('admin_layout.app')

@section('title', 'QR Generated')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">QR Tokens Monitor</h1>
                <p class="mt-1 sm:mt-2 text-sm sm:text-base text-gray-600">Live view of all active QR tokens with countdown timers</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-sm font-medium text-blue-700">Auto-refreshing</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-4 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Active Tokens</p>
                    <p class="text-lg sm:text-3xl font-bold text-blue-600 mt-1 sm:mt-2" id="activeCount">0</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-qrcode text-lg sm:text-2xl text-blue-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Expired</p>
                    <p class="text-lg sm:text-3xl font-bold text-red-600 mt-1 sm:mt-2" id="expiredCount">0</p>
                </div>
                <div class="p-2 sm:p-3 bg-red-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-clock text-lg sm:text-2xl text-red-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Used</p>
                    <p class="text-lg sm:text-3xl font-bold text-green-600 mt-1 sm:mt-2" id="usedCount">0</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <i class="fi fi-rr-check-circle text-lg sm:text-2xl text-green-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Tokens Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div id="tokensContainer" class="divide-y divide-gray-200">
            <div class="px-4 sm:px-6 py-12 text-center">
                <div class="mb-4">
                    <svg class="w-12 h-12 mx-auto text-gray-300 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <p class="text-gray-600">Loading active tokens...</p>
            </div>
        </div>
    </div>
</div>

<script>
    let refreshInterval = null;
    const ttlSeconds = 60;

    function initLiveView() {
        loadTokens();
        // Refresh every 2 seconds
        if (refreshInterval) clearInterval(refreshInterval);
        refreshInterval = setInterval(loadTokens, 2000);
    }

    function loadTokens() {
        fetch("{{ route('admin.qr.tokens') }}", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(r => {
            if (!r.ok) throw new Error('Failed to load tokens');
            return r.json();
        })
        .then(data => {
            renderTokens(data);
            updateStats(data);
        })
        .catch(error => {
            console.error('Error loading tokens:', error);
        });
    }

    function updateStats(data) {
        const active = data.tokens.filter(t => t.status === 'active').length;
        const expired = data.tokens.filter(t => t.status === 'expired').length;
        const used = data.tokens.filter(t => t.status === 'used').length;

        document.getElementById('activeCount').textContent = active;
        document.getElementById('expiredCount').textContent = expired;
        document.getElementById('usedCount').textContent = used;
    }

    function renderTokens(data) {
        const container = document.getElementById('tokensContainer');
        const currentTime = Math.floor(Date.now() / 1000);

        if (!data.tokens || data.tokens.length === 0) {
            container.innerHTML = `
                <div class="px-4 sm:px-6 py-12 text-center">
                    <div class="mb-4">
                        <i class="fi fi-rr-qrcode text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Active Tokens</h3>
                    <p class="text-gray-600">No QR tokens have been generated yet.</p>
                </div>`;
            return;
        }

        container.innerHTML = data.tokens.map(token => {
            const timeRemaining = token.expires_at - currentTime;
            const isExpired = timeRemaining <= 0 || token.status === 'expired';
            const isUsed = token.status === 'used';
            const statusColor = isUsed ? 'bg-green-50 border-green-200' : (isExpired ? 'bg-red-50 border-red-200' : 'bg-blue-50 border-blue-200');
            const statusTextColor = isUsed ? 'text-green-700' : (isExpired ? 'text-red-700' : 'text-blue-700');
            const statusBadgeColor = isUsed ? 'bg-green-100 text-green-800' : (isExpired ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');

            return `
                <div class="px-4 sm:px-6 py-4 hover:bg-gray-50 transition">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <!-- User Info -->
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    ${token.user.profile?.fname?.charAt(0).toUpperCase() || 'U'}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 text-sm truncate">
                                        ${token.user.profile?.fname || 'N/A'} ${token.user.profile?.lname || ''}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">${token.user.email}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusBadgeColor}">
                                        ${isUsed ? 'Used' : (isExpired ? 'Expired' : 'Active')}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Token:</span>
                                    <span class="text-xs text-gray-500 font-mono">${token.token.substring(0, 12)}...</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Generated:</span>
                                    <span class="text-xs text-gray-500">${new Date(token.created_at).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timer & QR -->
                        <div class="flex flex-col items-center justify-center">
                            <!-- Countdown Timer -->
                            <div class="mb-4 text-center">
                                <div class="text-4xl font-mono font-bold ${isUsed ? 'text-green-600' : (isExpired ? 'text-red-600' : 'text-blue-600')}">
                                    ${formatTime(timeRemaining)}
                                </div>
                                <p class="text-xs text-gray-500 mt-2">${isUsed ? 'Token Used' : (isExpired ? 'Token Expired' : 'Expires in')}</p>
                            </div>

                            <!-- QR Code if active -->
                            ${!isExpired && !isUsed ? `
                                <div class="p-3 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                                    <svg class="w-20 h-20 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm8-2v8h8V3h-8zm6 6h-4V5h4v4zM3 21h8v-8H3v8zm2-6h4v4H5v-4zm13-6h-2v3h-3v2h3v3h2v-3h3v-2h-3v-3z"/>
                                    </svg>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function formatTime(seconds) {
        if (seconds <= 0) return '00:00';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initLiveView);

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) clearInterval(refreshInterval);
    });
</script>

@endsection