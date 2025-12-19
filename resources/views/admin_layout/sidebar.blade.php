<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<!-- Mobile sidebar overlay -->
<div x-show="sidebarOpen" 
	 x-transition.opacity
	 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-30 lg:hidden"
	 @click="sidebarOpen = false"
	 aria-hidden="true">
</div>

<!-- Sidebar -->
<aside x-data="{ expanded: true }"
	   :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'"
	   class="fixed z-40 inset-y-0 left-0 w-72 bg-gradient-to-b from-white to-gray-50/80 border-r border-gray-100 transform lg:translate-x-0 lg:static lg:inset-auto transition-all duration-300 ease-in-out flex flex-col lg:shadow-sm pt-16 lg:pt-0">

	<!-- Logo section -->
	<div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-white to-blue-50/50">
		<div class="flex items-center justify-between">
			<div class="flex items-center gap-3">
				<div class="relative">
					<img src="/assets/img/paplogo.png" alt="Admin Portal Logo" class="w-12 h-12 rounded-xl shadow-md ring-2 ring-white ring-offset-2" />
					<div class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 rounded-full border-2 border-white"></div>
				</div>
				<div>
					<h1 class="font-bold text-gray-900 text-lg tracking-tight">Admin</h1>
					<p class="text-xs text-gray-500 font-medium">Administration Panel</p>
				</div>
			</div>

			<!-- Mobile close button -->
			<button @click="sidebarOpen = false" 
					class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-all active:scale-95"
					aria-label="Close sidebar">
				<i class="fi fi-rr-cross text-gray-500 text-lg"></i>
			</button>
		</div>
	</div>

	<!-- Navigation -->
	<nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
		<div class="px-3 mb-4">
			<h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</h2>
		</div>

		<a href="{{ route('admin.home') }}" 
		   class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
				  {{ request()->routeIs('admin.home') 
					 ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' 
					 : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
			<div class="{{ request()->routeIs('admin.home') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-blue-100 p-2 rounded-lg transition-colors' }}">
			<svg class="w-6 h-6 {{ request()->routeIs('admin.home') ? 'text-white' : 'text-gray-500 group-hover:text-blue-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
				<path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z"/>
			</svg>
			</div>
			<span class="font-medium">Dashboard</span>
			@if(request()->routeIs('admin.home'))
				<div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
			@endif
		</a>

		<!-- Manage User dropdown -->
		<div x-data="{ open: {{ request()->routeIs('admin.users*') || request()->routeIs('admin.qr*') ? 'true' : 'false' }} }" class="mt-1">
			<button @click="open = !open" class="w-full group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100/80 hover:shadow-sm transition-all">
				<div class="p-2 rounded-lg group-hover:bg-blue-100">
					<svg class="w-6 h-6 text-gray-500 group-hover:text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
				</div>
				<span class="font-medium">Manage User</span>
			<svg class="w-5 h-5 ml-auto text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
				</svg>
			</button>
			<div x-show="open" x-transition class="ml-12 mt-1 space-y-1">
				<a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
					<svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
					Users
				</a>
				<a href="{{ route('admin.qr') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.qr') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
					<svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M3 11h8V3H3v8zm0 8h8v-8H3v8zm10 0h8v-8h-8v8zm0-10v2h8V9h-8z"/>
					</svg>
					QR Generated
				</a>
			</div>
		</div>

		<!-- Manage Appointment dropdown -->
		<div x-data="{ open: {{ request()->routeIs('admin.calendar') || request()->routeIs('admin.appointments') ? 'true' : 'false' }} }" class="mt-1">
			<button @click="open = !open" class="w-full group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100/80 hover:shadow-sm transition-all">
				<div class="p-2 rounded-lg group-hover:bg-emerald-100">
					<svg class="w-6 h-6 text-gray-500 group-hover:text-emerald-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
					</svg>
				</div>
				<span class="font-medium">Manage Appointment</span>
			<svg class="w-5 h-5 ml-auto text-gray-400 transition-transform" :class="open ? 'rotate-90' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
				</svg>
			</button>
			<div x-show="open" x-transition class="ml-12 mt-1 space-y-1">
				<a href="{{ route('admin.calendar') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.calendar') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
					<svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"/>
					</svg>
					Calendar
				</a>
				<a href="{{ route('admin.appointments') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.appointments') ? 'bg-emerald-50 text-emerald-700 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
					<svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
						<path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
					</svg>
					Appointment Request
				</a>
			</div>
		</div>

		<!-- Attendance Forms -->
		<a href="{{ route('admin.attendance_forms.index') }}" 
		   class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
				  {{ request()->routeIs('admin.attendance_forms*') 
					 ? 'bg-gradient-to-r from-cyan-500 to-cyan-600 text-white shadow-lg shadow-cyan-500/25' 
					 : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
			<div class="{{ request()->routeIs('admin.attendance_forms*') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-cyan-100 p-2 rounded-lg transition-colors' }}">
				<svg class="w-6 h-6 {{ request()->routeIs('admin.attendance_forms*') ? 'text-white' : 'text-gray-500 group-hover:text-cyan-500' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
				</svg>
			</div>
			<span class="font-medium">Attendance Forms</span>
			@if(request()->routeIs('admin.attendance_forms*'))
				<div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
			@endif
		</a>

		<!-- System Logs -->
		<a href="{{ route('admin.logs') }}" 
		   class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.logs') ? 'bg-gradient-to-r from-gray-700 to-gray-800 text-white shadow-lg shadow-gray-700/25' : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
			<div class="{{ request()->routeIs('admin.logs') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-gray-200 p-2 rounded-lg transition-colors' }}">
				<svg class="w-6 h-6 {{ request()->routeIs('admin.logs') ? 'text-white' : 'text-gray-500 group-hover:text-gray-700' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
					<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM9 9h2v2H9V9zm4 0h2v2h-2V9zM9 13h2v2H9v-2zm4 0h2v2h-2v-2z"/>
				</svg>
			</div>
			<span class="font-medium">System Logs</span>
		</a>
	</nav>

	<!-- Logout section -->
	<div class="p-4 border-t border-gray-100 bg-gradient-to-r from-white to-gray-50/50">
		<form method="POST" action="{{ route('logout') }}">
			@csrf
			<button type="submit" 
					class="group w-full flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:shadow-sm active:scale-[0.98]"
					x-data="{ hover: false }"
					@mouseenter="hover = true"
					@mouseleave="hover = false">
				<div class="relative">
					<div class="p-2 rounded-lg bg-gradient-to-r from-red-500/10 to-red-500/5 group-hover:from-red-500/20 group-hover:to-red-500/10 transition-all">
						<svg class="w-6 h-6 text-red-500 group-hover:text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
						</svg>
					</div>
					<div x-show="hover" 
						 x-transition:enter="transition ease-out duration-200"
						 x-transition:enter-start="opacity-0 scale-95"
						 x-transition:enter-end="opacity-100 scale-100"
						 class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
						<svg class="w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
							<path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
						</svg>
					</div>
				</div>
				<span class="font-medium text-red-600 group-hover:text-red-700">Logout</span>
				<span class="ml-auto text-xs text-gray-400 group-hover:text-red-400 transition-colors">Exit</span>
			</button>
		</form>
		<div class="mt-4 pt-4 border-t border-gray-100/50">
			<div class="flex items-center gap-3">
				<div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
					{{ substr(Auth::user()->name ?? 'A', 0, 1) }}
				</div>
				<div class="flex-1 min-w-0">
					<p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
					<p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@example.com' }}</p>
				</div>
			</div>
		</div>
	</div>
</aside>

<!-- Add custom scrollbar styles -->
<style>
	html {
		scrollbar-gutter: stable;
	}

	body {
		scrollbar-gutter: stable;
	}

	.scrollbar-thin::-webkit-scrollbar {
		width: 4px;
	}
	.scrollbar-thin::-webkit-scrollbar-track {
		background: transparent;
	}
	.scrollbar-thin::-webkit-scrollbar-thumb {
		background-color: rgba(156, 163, 175, 0.5);
		border-radius: 20px;
	}
	.scrollbar-thin::-webkit-scrollbar-thumb:hover {
		background-color: rgba(156, 163, 175, 0.7);
	}
</style>
