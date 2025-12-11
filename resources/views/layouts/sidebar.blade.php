<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flaticon-uicons/css/uicons-rounded-regular.css">

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
                    <img src="/assets/img/paplogo.png" alt="Office Portal Logo" class="w-12 h-12 rounded-xl shadow-md ring-2 ring-white ring-offset-2" />
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <h1 class="font-bold text-gray-900 text-lg tracking-tight">Dashboard</h1>
                    <p class="text-xs text-gray-500 font-medium">Office Portal</p>
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

        <a href="{{ route('user.home') }}" 
           class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('user.home') 
                     ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' 
                     : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
            <div class="{{ request()->routeIs('user.home') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-blue-100 p-2 rounded-lg transition-colors' }}">
                <i class="fi fi-rr-home text-base {{ request()->routeIs('user.home') ? 'text-white' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
            </div>
            <span class="font-medium">Home</span>
            @if(request()->routeIs('user.home'))
                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
            @endif
        </a>

        <a href="{{ route('user.profile') }}" 
           class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('user.profile') 
                     ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' 
                     : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
            <div class="{{ request()->routeIs('user.profile') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-blue-100 p-2 rounded-lg transition-colors' }}">
                <i class="fi fi-rr-user text-base {{ request()->routeIs('user.profile') ? 'text-white' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
            </div>
            <span class="font-medium">Profile</span>
            @if(request()->routeIs('user.profile'))
                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
            @endif
        </a>



        <a href="{{ route('qr.generate') }}" 
           class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('qr.generate') 
                     ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/25' 
                     : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
            <div class="{{ request()->routeIs('qr.generate') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-emerald-100 p-2 rounded-lg transition-colors' }}">
                <i class="fi fi-rr-qrcode text-base {{ request()->routeIs('qr.generate') ? 'text-white' : 'text-gray-500 group-hover:text-emerald-500' }}"></i>
            </div>
            <span class="font-medium">Generate QR</span>
            @if(request()->routeIs('qr.generate'))
                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
            @endif
        </a>

        <a href="{{ route('appointment.show') }}" 
           class="group flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('appointment.show') 
                     ? 'bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/25' 
                     : 'text-gray-700 hover:bg-gray-100/80 hover:shadow-sm hover:translate-x-1' }}">
            <div class="{{ request()->routeIs('appointment.show') ? 'bg-white/20 p-2 rounded-lg' : 'group-hover:bg-purple-100 p-2 rounded-lg transition-colors' }}">
                <i class="fi fi-rr-calendar text-base {{ request()->routeIs('appointment.show') ? 'text-white' : 'text-gray-500 group-hover:text-purple-500' }}"></i>
            </div>
            <span class="font-medium">Request Appointment</span>
            @if(request()->routeIs('appointment.show'))
                <div class="ml-auto w-2 h-2 bg-white rounded-full"></div>
            @endif
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
                        <i class="fi fi-rr-sign-out text-base text-red-500 group-hover:text-red-600"></i>
                    </div>
                    <div x-show="hover" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                        <i class="fi fi-rr-exit text-xs text-white"></i>
                    </div>
                </div>
                <span class="font-medium text-red-600 group-hover:text-red-700">Logout</span>
                <span class="ml-auto text-xs text-gray-400 group-hover:text-red-400 transition-colors">Exit</span>
            </button>
        </form>
        
        <!-- User info (optional - you can add dynamic user data) -->
        <div class="mt-4 pt-4 border-t border-gray-100/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if(Auth::user() && Auth::user()->profile && Auth::user()->profile->avatar_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile->avatar_path) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white text-xs font-bold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'user@example.com' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Add custom scrollbar styles -->
<style>
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
    
    /* Prevent layout shift when scrollbar appears */
    html {
        scrollbar-gutter: stable;
    }
    
    body {
        scrollbar-gutter: stable;
    }
</style>