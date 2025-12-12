<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', 'Admin Dashboard')</title>
	@vite('resources/css/app.css')
	<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
	@yield('head')
	@stack('head')
</head>
<body class="antialiased bg-gray-50 font-sans" x-data="{ sidebarOpen: false }">
	<div class="flex h-screen">
		@include('admin_layout.sidebar')

		<div class="flex-1 flex flex-col lg:pl-0">
			<!-- Header -->
			<header class="w-full sticky top-0 z-40 bg-gradient-to-r from-[#6a0000] to-[#4a0000] text-white shadow">
				<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
					<!-- Mobile menu button -->
					<button @click="sidebarOpen = true" 
							class="p-2 rounded-md hover:bg-white/10 lg:hidden transition" aria-label="Open sidebar">
						<svg xmlns="http://www.w3.org/2000/svg" 
							 class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								  d="M4 6h16M4 12h16M4 18h16"/>
						</svg>
					</button>

					<!-- Title -->
					<div class="flex-1 text-center">
						<span class="text-lg font-semibold tracking-wide">@yield('title', 'Admin Dashboard')</span>
					</div>

					<!-- Profile shortcut -->
					<div class="flex items-center gap-3">
						<a href="{{ route('admin.home') }}" 
						   class="flex items-center gap-2 group" title="Go to Admin Home">
							<span class="hidden sm:block text-sm font-medium group-hover:underline">Admin</span>
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
	@stack('scripts')
</body>
</html>
