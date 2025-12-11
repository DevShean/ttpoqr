<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flaticon-uicons/css/uicons-rounded-regular.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @yield('head')
</head>
<body class="antialiased bg-gray-50 font-sans" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col lg:pl-0">
            <!-- Header -->
            <header class="w-full sticky top-0 z-40 bg-gradient-to-r from-[#6a0000] to-[#4a0000] text-white shadow">
                <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
                    
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            class="p-2 rounded-md hover:bg-white/10 lg:hidden transition">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Title -->
                    <div class="flex-1 text-center">
                        <span class="text-lg font-semibold tracking-wide">@yield('title', 'Dashboard')</span>
                    </div>

                    <!-- Profile -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.profile') }}" 
                           class="flex items-center gap-2 group">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center group-hover:opacity-80 transition overflow-hidden">
                                @if(Auth::user() && Auth::user()->profile && Auth::user()->profile->avatar_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile->avatar_path) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span class="hidden sm:block text-sm font-medium group-hover:underline">Profile</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-6 overflow-auto bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
</body>
@stack('scripts')
</html>