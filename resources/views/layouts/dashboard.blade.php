<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Blood Lagbe</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-50 font-inter antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        style="display: none;"
    ></div>

    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 z-30 h-full w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-300 ease-in-out shadow-lg"
    >
        <!-- Sidebar Header / Logo -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
            <div class="flex-shrink-0 w-9 h-9 bg-red-600 rounded-xl flex items-center justify-center shadow">
                <i data-lucide="droplets" class="w-5 h-5 text-white"></i>
            </div>
            <div class="flex-1 min-w-0">
                <span class="text-lg font-bold text-gray-900 leading-tight block">Blood Lagbe</span>
                <span class="text-xs text-red-500 font-medium">Save a Life Today</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</p>

            <!-- Dashboard -->
            <a href="{{ route('dashboard.index') }}"
               class="{{ request()->routeIs('dashboard.index') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="home" class="w-4 h-4 flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>

            <!-- My Requests -->
            <a href="{{ route('dashboard.requests.index') }}"
               class="{{ request()->routeIs('dashboard.requests.*') && !request()->routeIs('dashboard.requests.create') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="droplet" class="w-4 h-4 flex-shrink-0"></i>
                <span>My Requests</span>
            </a>

            <!-- New Request -->
            <a href="{{ route('dashboard.requests.create') }}"
               class="{{ request()->routeIs('dashboard.requests.create') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="plus-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span>New Request</span>
            </a>

            <!-- Donor Profile -->
            <a href="{{ route('dashboard.donor-profile') }}"
               class="{{ request()->routeIs('dashboard.donor-profile*') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="heart" class="w-4 h-4 flex-shrink-0"></i>
                <span>
                    @auth
                        @if(auth()->user()->isDonor())
                            Donor Profile
                        @else
                            Become a Donor
                        @endif
                    @endauth
                </span>
            </a>

            <div class="my-3 border-t border-gray-100"></div>
            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>

            <!-- Notifications -->
            <a href="{{ route('dashboard.notifications.index') }}"
               class="{{ request()->routeIs('dashboard.notifications.*') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="bell" class="w-4 h-4 flex-shrink-0"></i>
                <span class="flex-1">Notifications</span>
                @php
                    $unreadNotifCount = auth()->user()?->unreadNotifications()->count() ?? 0;
                @endphp
                @if($unreadNotifCount > 0)
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-red-600 text-white text-xs font-bold rounded-full">
                        {{ $unreadNotifCount > 99 ? '99+' : $unreadNotifCount }}
                    </span>
                @endif
            </a>

            <!-- Profile Settings -->
            <a href="{{ route('dashboard.profile.edit') }}"
               class="{{ request()->routeIs('dashboard.profile.*') ? 'nav-item-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
                <span>Profile Settings</span>
            </a>

        </nav>

        <!-- Sidebar Footer -->
        <div class="border-t border-gray-100 p-4">
            @auth
            <div class="flex items-center gap-3 mb-3">
                <!-- Avatar Initials -->
                <div class="w-9 h-9 rounded-full bg-red-100 border-2 border-red-200 flex items-center justify-center flex-shrink-0">
                    <span class="text-red-700 text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name, ' '), 1, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium
                        @if(auth()->user()->role === 'admin') bg-purple-100 text-purple-700
                        @elseif(auth()->user()->role === 'donor') bg-green-100 text-green-700
                        @else bg-blue-100 text-blue-700 @endif">
                        {{ ucfirst(auth()->user()->role ?? 'user') }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150 font-medium">
                    <i data-lucide="log-out" class="w-4 h-4 flex-shrink-0"></i>
                    <span>Sign Out</span>
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Mobile Top Bar -->
        <header class="lg:hidden sticky top-0 z-10 bg-white border-b border-gray-200 flex items-center justify-between px-4 py-3 shadow-sm">
            <button @click="sidebarOpen = true" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-red-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="droplets" class="w-4 h-4 text-white"></i>
                </div>
                <span class="text-base font-bold text-gray-900">Blood Lagbe</span>
            </div>
            <a href="{{ route('dashboard.notifications.index') }}" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors relative">
                <i data-lucide="bell" class="w-5 h-5"></i>
                @if(isset($unreadNotifCount) && $unreadNotifCount > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-600 rounded-full"></span>
                @endif
            </a>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert-success flex items-start gap-3 mb-6" x-data="{ show: true }" x-show="show" x-transition>
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ session('success') }}</div>
                    <button @click="show = false" class="flex-shrink-0 opacity-70 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error flex items-start gap-3 mb-6" x-data="{ show: true }" x-show="show" x-transition>
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ session('error') }}</div>
                    <button @click="show = false" class="flex-shrink-0 opacity-70 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert-warning flex items-start gap-3 mb-6" x-data="{ show: true }" x-show="show" x-transition>
                    <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ session('warning') }}</div>
                    <button @click="show = false" class="flex-shrink-0 opacity-70 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert-info flex items-start gap-3 mb-6" x-data="{ show: true }" x-show="show" x-transition>
                    <i data-lucide="info" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ session('info') }}</div>
                    <button @click="show = false" class="flex-shrink-0 opacity-70 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto px-4 sm:px-6 lg:px-8 py-4 border-t border-gray-200 bg-white">
            <p class="text-xs text-center text-gray-400">&copy; {{ date('Y') }} Blood Lagbe. Saving lives, one donation at a time.</p>
        </footer>
    </div>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
