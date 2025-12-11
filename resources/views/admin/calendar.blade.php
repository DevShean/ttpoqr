@extends('admin_layout.app')

@section('title', 'Calendar')

@section('content')
<div class="min-h-screen bg-gray-50 p-3 md:p-6" x-data="calendarAvailability()" x-init="init()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Calendar Availability</h1>
                    <p class="mt-1 text-sm md:text-base text-gray-600">Tap a day to toggle availability. All days start as unavailable.</p>
                </div>
                
                <!-- Stats Badges -->
                <div class="flex flex-wrap gap-2">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-100">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></div>
                        <span class="text-sm font-medium text-emerald-700" x-text="`${availableCount} Available`"></span>
                    </div>
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-red-50 border border-red-100">
                        <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-sm font-medium text-red-700" x-text="`${unavailableCount} Unavailable`"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="bg-white rounded-2xl shadow-lg p-4 md:p-8">
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

                <!-- Filter & Status -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="available-only"
                               class="sr-only"
                               x-model="onlyAvailable" @change="applyFilter()">
                        <label for="available-only"
                               class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer select-none">
                            <div class="w-10 h-5 flex items-center rounded-full p-0.5 transition-colors duration-200"
                                 :class="onlyAvailable ? 'bg-emerald-500' : 'bg-gray-300'">
                                <div class="w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200"
                                     :class="onlyAvailable ? 'translate-x-5' : 'translate-x-0'"></div>
                            </div>
                            <span>Show only available</span>
                        </label>
                    </div>
                    <div class="text-sm text-gray-500 flex items-center gap-2" x-text="status">
                        <span x-show="status === 'Loading…'" class="inline-block w-4 h-4 border-2 border-gray-300 border-t-gray-600 rounded-full animate-spin"></span>
                    </div>
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
                        :disabled="!cell.inMonth || !cell.canEdit"
                        @click="toggleDay(cell.date)"
                        :class="{
                            'opacity-30': !cell.inMonth,
                            'opacity-50 cursor-not-allowed': cell.inMonth && !cell.canEdit,
                            'hover:scale-[1.02] active:scale-95 transition-transform duration-150': cell.inMonth && cell.canEdit,
                            'bg-emerald-50 border-2 border-emerald-200 hover:border-emerald-300': cell.inMonth && cell.available && cell.canEdit,
                            'bg-red-50 border-2 border-red-200 hover:border-red-300': cell.inMonth && !cell.available && cell.canEdit,
                            'bg-gray-100 border border-gray-300': cell.inMonth && !cell.canEdit,
                            'bg-gray-50 border border-gray-200': !cell.inMonth
                        }"
                        class="relative rounded-xl aspect-square p-1 md:p-2 flex flex-col items-center justify-center transition-all duration-200">
                        
                        <!-- Date Number -->
                        <span class="text-base md:text-lg font-semibold mb-0.5"
                              :class="{
                                'text-emerald-700': cell.inMonth && cell.available,
                                'text-red-700': cell.inMonth && !cell.available,
                                'text-gray-400': !cell.inMonth
                              }"
                              x-text="cell.day">
                        </span>
                        
                        <!-- Availability Status -->
                        <div class="flex items-center justify-center">
                            <template x-if="cell.inMonth">
                                <div class="flex items-center gap-1">
                                    <span class="hidden md:inline text-xs font-medium"
                                          :class="{
                                              'text-emerald-600': cell.available && cell.canEdit,
                                              'text-red-600': !cell.available && cell.canEdit,
                                              'text-gray-400': !cell.canEdit
                                          }"
                                          x-text="cell.canEdit ? (cell.available ? 'Available' : 'Unavailable') : 'Past'">
                                    </span>
                                    <span class="md:hidden">
                                        <svg x-show="cell.available && cell.canEdit" class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="!cell.available && cell.canEdit" class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg x-show="!cell.canEdit" class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
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
                        <div class="w-4 h-4 rounded bg-gray-50 border border-gray-300"></div>
                        <span>Other Month</span>
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
        function calendarAvailability() {
            return {
                current: new Date(),
                monthLabel: '',
                cells: [],
                visibleCells: [],
                availableCount: 0,
                unavailableCount: 0,
                onlyAvailable: false,
                status: '',
                init() { 
                    this.build(); 
                    this.fetch(); 
                },
                build() {
                    const year = this.current.getFullYear();
                    const month = this.current.getMonth();
                    const first = new Date(year, month, 1);
                    const startDay = first.getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const prevDays = new Date(year, month, 0).getDate();

                    this.monthLabel = first.toLocaleString(undefined, { 
                        month: 'long', 
                        year: 'numeric' 
                    }).replace(/(\w+) (\d+)/, '$1, $2');
                    
                    const cells = [];

                    // Previous month spill - all unavailable
                    for (let i = startDay - 1; i >= 0; i--) {
                        const d = prevDays - i;
                        const date = new Date(year, month - 1, d);
                        cells.push({ 
                            key: `p-${d}`, 
                            date: this.toLocalISO(date), 
                            day: d, 
                            inMonth: false, 
                            available: false 
                        });
                    }

                    // Current month - ALL DAYS UNAVAILABLE BY DEFAULT
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
                            available: false,  // Changed from true to false
                            canEdit: !isPast
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
                            available: false 
                        });
                    }

                    this.cells = cells;
                    this.applyFilter();
                },
                fetch() {
                    const start = this.cells[0].date;
                    const end = this.cells[this.cells.length - 1].date;
                    this.status = 'Loading…';
                    
                    fetch(`/admin/availability?start=${start}&end=${end}`, { 
                        headers: { 
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const items = Array.isArray(data) ? data : data.availabilities;
                        const appointedDates = Array.isArray(data) ? [] : (data.appointedDates || []);
                        
                        const map = new Map(items.map(i => [
                            i.date.split('T')[0],
                            (i.is_available === true || i.is_available === 1 || i.is_available === '1')
                        ]));
                        
                        this.cells = this.cells.map(c => {
                            // If date has an appointment, mark as unavailable
                            if (appointedDates.includes(c.date)) {
                                return { ...c, available: false };
                            }
                            // Only update if the date exists in the response, otherwise keep as unavailable
                            return { ...c, available: map.has(c.date) ? map.get(c.date) : c.available };
                        });
                        
                        this.updateCounts();
                        this.applyFilter();
                        this.status = '';
                    })
                    .catch(() => { 
                        this.status = 'Failed to load'; 
                        setTimeout(() => this.status = '', 3000);
                    });
                },
                toggleDay(date) {
                    const cell = this.cells.find(c => c.date === date);
                    if (!cell || !cell.inMonth || !cell.canEdit) return;
                    
                    const next = !cell.available;
                    this.status = 'Saving…';
                    
                    fetch('/admin/availability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ 
                            date, 
                            is_available: next 
                        })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Network response was not ok');
                        return r.json();
                    })
                    .then(() => { 
                        cell.available = next; 
                        this.updateCounts(); 
                        this.applyFilter(); 
                        this.status = 'Saved!';
                        setTimeout(() => this.status = '', 1500);
                    })
                    .catch(() => { 
                        this.status = 'Save failed'; 
                        setTimeout(() => this.status = '', 3000);
                    });
                },
                setAllAvailable() {
                    if (!confirm('Make all days in this month available?')) return;
                    
                    this.status = 'Updating All...';
                    const currentMonthCells = this.cells.filter(c => c.inMonth);
                    const updates = currentMonthCells.map(cell => ({
                        date: cell.date,
                        is_available: true
                    }));
                    
                    fetch('/admin/availability/bulk', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ updates })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Network response was not ok');
                        return r.json();
                    })
                    .then(() => {
                        currentMonthCells.forEach(cell => cell.available = true);
                        this.updateCounts();
                        this.applyFilter();
                        this.status = 'All days set to available!';
                        setTimeout(() => this.status = '', 2000);
                    })
                    .catch(() => {
                        this.status = 'Update failed';
                        setTimeout(() => this.status = '', 3000);
                    });
                },
                setAllUnavailable() {
                    if (!confirm('Make all days in this month unavailable?')) return;
                    
                    this.status = 'Updating All...';
                    const currentMonthCells = this.cells.filter(c => c.inMonth);
                    const updates = currentMonthCells.map(cell => ({
                        date: cell.date,
                        is_available: false
                    }));
                    
                    fetch('/admin/availability/bulk', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ updates })
                    })
                    .then(r => {
                        if (!r.ok) throw new Error('Network response was not ok');
                        return r.json();
                    })
                    .then(() => {
                        currentMonthCells.forEach(cell => cell.available = false);
                        this.updateCounts();
                        this.applyFilter();
                        this.status = 'All days set to unavailable!';
                        setTimeout(() => this.status = '', 2000);
                    })
                    .catch(() => {
                        this.status = 'Update failed';
                        setTimeout(() => this.status = '', 3000);
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
                    this.availableCount = inMonth.filter(c => c.available).length;
                    this.unavailableCount = inMonth.length - this.availableCount;
                },
                applyFilter() {
                    this.updateCounts();
                    this.visibleCells = this.onlyAvailable
                        ? this.cells.filter(c => !c.inMonth || (c.inMonth && c.available))
                        : this.cells;
                }
            }
        }
    </script>
</div>
@endsection
