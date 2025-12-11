@extends('layouts.app')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('title', 'Request Appointment')

@section('content')
<div class="min-h-screen bg-gray-50 p-3 md:p-6" x-data="appointmentCalendar()" x-init="init()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Request an Appointment</h1>
                    <p class="mt-1 text-sm md:text-base text-gray-600">Select an available date from the calendar below to schedule your appointment</p>
                </div>

                <!-- Stats Badges -->
                <div class="flex flex-wrap gap-2">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>
                        <span class="text-sm font-medium text-emerald-700" x-text="`${availableCount} Available`"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if(session('status'))
            <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-700 font-medium">{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 shadow-sm">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-medium">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside mt-2 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Calendar Container -->
        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-8 mb-6">
            <!-- Calendar Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 md:mb-8">
                <div class="flex items-center justify-between sm:justify-start gap-3">
                    <div class="flex items-center gap-2">
                        <button @click="prevMonth()"
                                class="p-2 md:p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors duration-200 active:scale-95">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button @click="today()"
                                class="px-4 py-2 md:px-5 md:py-2.5 text-sm md:text-base font-medium rounded-xl bg-gray-900 text-white hover:bg-gray-800 transition-colors duration-200 active:scale-95">
                            Today
                        </button>
                        <button @click="nextMonth()"
                                class="p-2 md:p-3 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-colors duration-200 active:scale-95">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 ml-2 md:ml-4" x-text="monthLabel"></h2>
                </div>

                <!-- Status -->
                <div class="text-sm text-gray-500 flex items-center gap-2" x-text="status">
                    <span x-show="status === 'Loading…'" class="inline-block w-4 h-4 border-2 border-gray-300 border-t-gray-600 rounded-full animate-spin"></span>
                </div>
            </div>

            <!-- Days of Week Header -->
            <div class="grid grid-cols-7 gap-1 md:gap-2 mb-3">
                <template x-for="day in ['S', 'M', 'T', 'W', 'T', 'F', 'S']" :key="day">
                    <div class="text-center">
                        <div class="text-xs md:text-sm font-semibold text-gray-500 py-2" x-text="day"></div>
                    </div>
                </template>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 md:gap-2">
                <template x-for="cell in visibleCells" :key="cell.key">
                    <button
                        :disabled="!cell.inMonth || !cell.available || cell.hasRequest || cell.onCooldown"
                        @click="selectDate(cell.date)"
                        :class="{
                            'opacity-30': !cell.inMonth,
                            'opacity-50 cursor-not-allowed': cell.inMonth && (!cell.available || cell.hasRequest || cell.onCooldown),
                            'hover:scale-[1.02] active:scale-95 transition-transform duration-150': cell.inMonth && cell.available && !cell.hasRequest && !cell.onCooldown,
                            'bg-emerald-50 border-2 border-emerald-200 hover:border-emerald-300': cell.inMonth && cell.available && !cell.hasRequest && !cell.onCooldown,
                            'bg-red-50 border-2 border-red-200': cell.inMonth && !cell.available,
                            'bg-yellow-50 border-2 border-yellow-200': cell.inMonth && cell.available && cell.hasRequest,
                            'bg-blue-50 border-2 border-blue-200': cell.inMonth && cell.onCooldown,
                            'bg-gray-50 border border-gray-200': !cell.inMonth
                        }"
                        class="relative rounded-xl aspect-square p-1 md:p-2 flex flex-col items-center justify-center transition-all duration-200">
                        <!-- Date Number -->
                        <span class="text-base md:text-lg font-semibold mb-0.5"
                              :class="{
                                'text-emerald-700': cell.inMonth && cell.available && !cell.hasRequest && !cell.onCooldown,
                                'text-red-700': cell.inMonth && !cell.available,
                                'text-yellow-700': cell.inMonth && cell.available && cell.hasRequest,
                                'text-blue-700': cell.inMonth && cell.onCooldown,
                                'text-gray-400': !cell.inMonth
                              }"
                              x-text="cell.day">
                        </span>

                        <!-- Status Indicator -->
                        <div class="flex items-center justify-center">
                            <template x-if="cell.inMonth">
                                <div class="flex items-center gap-1">
                                    <span class="hidden md:inline text-xs font-medium"
                                          :class="{
                                              'text-emerald-600': cell.available && !cell.hasRequest && !cell.onCooldown,
                                              'text-red-600': !cell.available,
                                              'text-yellow-600': cell.available && cell.hasRequest,
                                              'text-blue-600': cell.onCooldown
                                          }"
                                          x-text="cell.onCooldown ? (Math.ceil(cell.cooldownRemaining / 60) + 'm') : (cell.available && !cell.hasRequest ? 'Available' : (!cell.available ? 'Unavailable' : 'Requested'))">
                                    </span>
                                    <span class="md:hidden">
                                        <svg x-show="cell.available && !cell.hasRequest && !cell.onCooldown" class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="!cell.available" class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="cell.available && cell.hasRequest && !cell.onCooldown" class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="cell.onCooldown" class="w-4 h-4 text-blue-500 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 1114.869 2.7c-.75.438-1.338 1.04-1.447 1.741-.111.712.821 1.469 1.829 1.469.556 0 1-.449 1-1.002A9 9 0 006 3a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>
                            </template>
                        </div>

                        <!-- Today Indicator -->
                        <div x-show="isToday(cell.date)"
                             class="absolute top-1 right-1 w-2 h-2 rounded-full bg-blue-500"></div>
                    </button>
                </template>
            </div>

            <!-- Legend -->
            <div class="mt-6 md:mt-8 pt-6 border-t border-gray-100">
                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-emerald-50 border-2 border-emerald-300"></div>
                        <span>Available</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-red-50 border-2 border-red-300"></div>
                        <span>Unavailable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-yellow-50 border-2 border-yellow-300"></div>
                        <span>Already Requested</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-blue-50 border-2 border-blue-300"></div>
                        <span>Cooldown (3 min)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <span>Today</span>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script>
        function appointmentCalendar() {
            return {
                current: new Date(),
                monthLabel: '',
                cells: [],
                visibleCells: [],
                availableCount: 0,
                status: '',
                cooldowns: {}, // Track cooldown timers
                init() {
                    console.log('Appointment calendar initializing...');
                    this.build();
                    this.fetch();
                    console.log('Appointment calendar initialized');
                },
                build() {
                    const year = this.current.getFullYear();
                    const month = this.current.getMonth();
                    const first = new Date(year, month, 1);
                    const startDay = first.getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();

                    this.monthLabel = first.toLocaleString(undefined, {
                        month: 'long',
                        year: 'numeric'
                    }).replace(/(\w+) (\d+)/, '$1, $2');

                    const cells = [];

                    // Previous month spill - all unavailable
                    const prevDays = new Date(year, month, 0).getDate();
                    for (let i = startDay - 1; i >= 0; i--) {
                        const d = prevDays - i;
                        const date = new Date(year, month - 1, d);
                        cells.push({
                            key: `p-${d}`,
                            date: this.toLocalISO(date),
                            day: d,
                            inMonth: false,
                            available: false,
                            hasRequest: false,
                            onCooldown: false,
                            cooldownRemaining: 0
                        });
                    }

                    // Current month - check availability and existing requests
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    for (let d = 1; d <= daysInMonth; d++) {
                        const date = new Date(year, month, d);
                        const isPast = date < today;
                        cells.push({
                            key: `c-${d}`,
                            date: this.toLocalISO(date),
                            day: d,
                            inMonth: true,
                            available: false,  // Default to unavailable until confirmed from database
                            hasRequest: false,
                            onCooldown: false,
                            cooldownRemaining: 0
                        });
                    }

                    // Next month spill - all unavailable
                    const total = 42;
                    const nextCount = total - cells.length;
                    for (let d = 1; d <= nextCount; d++) {
                        const date = new Date(year, month + 1, d);
                        cells.push({
                            key: `n-${d}`,
                            date: this.toLocalISO(date),
                            day: d,
                            inMonth: false,
                            available: false,
                            hasRequest: false,
                            onCooldown: false,
                            cooldownRemaining: 0
                        });
                    }

                    this.cells = cells;
                    this.applyFilter();
                },
                fetch() {
                    const start = this.cells[0].date;
                    const end = this.cells[this.cells.length - 1].date;
                    this.status = 'Loading…';

                    fetch(`/user/availability?start=${start}&end=${end}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => {
                        if (!r.ok) throw new Error(`HTTP error! status: ${r.status}`);
                        return r.json();
                    })
                    .then(data => {
                        console.log('Availability data:', data);
                        
                        const availabilityMap = new Map(data.availabilities.map(i => [
                            i.date.split('T')[0],
                            i.is_available
                        ]));

                        // Only count non-rejected requests
                        const requestMap = new Map(data.requests
                            .filter(i => i.status !== 'rejected')
                            .map(i => [
                                i.availability.date.split('T')[0],
                                true
                            ])
                        );

                        // Build cooldown map
                        const cooldownMap = new Map();
                        if (data.rejectedDates) {
                            for (const [date, cooldownInfo] of Object.entries(data.rejectedDates)) {
                                cooldownMap.set(date, cooldownInfo);
                            }
                        }

                        this.cells = this.cells.map(c => ({
                            ...c,
                            available: availabilityMap.has(c.date) ? availabilityMap.get(c.date) : c.available,
                            hasRequest: requestMap.has(c.date) || false,
                            onCooldown: cooldownMap.has(c.date) ? cooldownMap.get(c.date).on_cooldown : false,
                            cooldownRemaining: cooldownMap.has(c.date) ? cooldownMap.get(c.date).cooldown_remaining_seconds : 0
                        }));

                        this.updateCounts();
                        this.applyFilter();
                        this.startCooldownTimers();
                        this.status = '';
                    })
                    .catch(err => {
                        console.error('Availability fetch error:', err);
                        this.status = 'Failed to load';
                        setTimeout(() => this.status = '', 3000);
                    });
                },
                selectDate(date) {
                    const cell = this.cells.find(c => c.date === date);
                    if (!cell || !cell.inMonth || !cell.available || cell.hasRequest || cell.onCooldown) {
                        if (cell && cell.onCooldown) {
                            const minutes = Math.ceil(cell.cooldownRemaining / 60);
                            Swal.fire({
                                title: 'Cooldown Active',
                                text: `Please wait ${minutes} minute${minutes !== 1 ? 's' : ''} before requesting this date again.`,
                                icon: 'info',
                                confirmButtonColor: '#3B82F6'
                            });
                        }
                        return;
                    }

                    // Store reference to 'this' for the promise
                    const that = this;

                    // Show SweetAlert modal
                    Swal.fire({
                        title: 'Request Appointment',
                        html: `
                            <p class="mb-4 text-gray-600">Selected date: <strong>${this.formatDate(date)}</strong></p>
                            <label for="swal-purpose" class="block text-sm font-medium text-gray-700 mb-2">Purpose of Appointment</label>
                            <select id="swal-purpose" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a purpose</option>
                                <option value="Travel Permit">Travel Permit</option>
                                <option value="NBI Certificate">NBI Certificate</option>
                                <option value="Submit Clearance">Submit Clearance</option>
                                <option value="Conferencing">Conferencing</option>
                                <option value="Application on Parole">Application on Parole</option>
                                <option value="Application on Probation">Application on Probation</option>
                            </select>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit Request',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3B82F6',
                        didOpen: () => {
                            console.log('SweetAlert modal opened');
                        },
                        preConfirm: () => {
                            const purpose = document.getElementById('swal-purpose').value;
                            if (!purpose) {
                                Swal.showValidationMessage('Please select a purpose');
                                return false;
                            }
                            return purpose;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            that.submitRequest(date, result.value);
                        }
                    });
                },
                submitRequest(date, purpose) {
                    this.status = 'Submitting request...';

                    fetch('/user/appointment-request', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            date: date,
                            purpose: purpose
                        })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Network response was not ok');
                        return r.json();
                    })
                    .then(() => {
                        // Mark the cell as having a request
                        const cell = this.cells.find(c => c.date === date);
                        if (cell) {
                            cell.hasRequest = true;
                        }

                        this.updateCounts();
                        this.applyFilter();

                        // Show success message with SweetAlert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your appointment request has been submitted successfully.',
                            icon: 'success',
                            confirmButtonColor: '#3B82F6'
                        });
                        this.status = '';
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to submit your appointment request. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#EF4444'
                        });
                        this.status = '';
                    });
                },
                formatDate(dateStr) {
                    return new Date(dateStr).toLocaleDateString(undefined, {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                },
                prevMonth() {
                    this.current.setMonth(this.current.getMonth() - 1);
                    this.build();
                    this.fetch();
                },
                nextMonth() {
                    this.current.setMonth(this.current.getMonth() + 1);
                    this.build();
                    this.fetch();
                },
                today() {
                    this.current = new Date();
                    this.build();
                    this.fetch();
                },
                toLocalISO(d) {
                    const y = d.getFullYear();
                    const m = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    return `${y}-${m}-${day}`;
                },
                isToday(dateStr) {
                    const today = new Date();
                    const compare = new Date(dateStr);
                    return today.toDateString() === compare.toDateString();
                },
                updateCounts() {
                    const inMonth = this.cells.filter(c => c.inMonth);
                    this.availableCount = inMonth.filter(c => c.available && !c.hasRequest).length;
                },
                applyFilter() {
                    this.updateCounts();
                    this.visibleCells = this.cells;
                },
                startCooldownTimers() {
                    // Clear existing timers
                    Object.values(this.cooldowns).forEach(timer => clearInterval(timer));
                    this.cooldowns = {};

                    // Start timer for each cell on cooldown
                    this.cells.forEach(cell => {
                        if (cell.onCooldown) {
                            const timer = setInterval(() => {
                                cell.cooldownRemaining--;
                                if (cell.cooldownRemaining <= 0) {
                                    cell.onCooldown = false;
                                    clearInterval(timer);
                                    delete this.cooldowns[cell.date];
                                }
                            }, 1000);
                            this.cooldowns[cell.date] = timer;
                        }
                    });
                }
            }
        }
    </script>
</div>
@endsection
