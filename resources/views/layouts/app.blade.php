<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Blood Lagbe') — Every Drop Counts. Every Donor Matters.</title>
    <meta name="description" content="@yield('description', 'Blood Lagbe connects blood donors with people in need across Bangladesh. Find donors, post blood requests, and save lives.')">
    <meta name="keywords" content="blood donation, blood donor, blood request, Bangladesh, রক্ত দান">
    <meta property="og:title" content="@yield('title', 'Blood Lagbe')">
    <meta property="og:description" content="Every Drop Counts. Every Donor Matters.">
    <meta property="og:type" content="website">

    <!-- Preconnect for fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm" x-data="{ open: false, scrolled: false }"
         @scroll.window="scrolled = window.scrollY > 20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-blood-600 rounded-xl flex items-center justify-center shadow-md group-hover:bg-blood-700 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C12 2 4 10.3 4 14.5C4 18.6 7.6 22 12 22C16.4 22 20 18.6 20 14.5C20 10.3 12 2 12 2Z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-blood-700">Blood Lagbe</span>
                        <p class="text-xs text-gray-400 hidden sm:block leading-tight">Every Drop Counts</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('home') ? 'text-blood-700 bg-blood-50' : 'text-gray-600 hover:text-blood-700 hover:bg-blood-50' }} transition-all duration-200">Home</a>
                    <a href="{{ route('donors.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('donors.*') ? 'text-blood-700 bg-blood-50' : 'text-gray-600 hover:text-blood-700 hover:bg-blood-50' }} transition-all duration-200">Find Donors</a>
                    <a href="{{ route('requests.index') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('requests.*') ? 'text-blood-700 bg-blood-50' : 'text-gray-600 hover:text-blood-700 hover:bg-blood-50' }} transition-all duration-200">Blood Requests</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('about') ? 'text-blood-700 bg-blood-50' : 'text-gray-600 hover:text-blood-700 hover:bg-blood-50' }} transition-all duration-200">About</a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('contact') ? 'text-blood-700 bg-blood-50' : 'text-gray-600 hover:text-blood-700 hover:bg-blood-50' }} transition-all duration-200">Contact</a>
                </div>

                <!-- Auth Actions -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn-outline btn-sm">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Admin Panel
                            </a>
                        @else
                            <!-- Notification Bell -->
                            <a href="{{ route('dashboard.notifications.index') }}" class="relative p-2 text-gray-500 hover:text-blood-600 hover:bg-blood-50 rounded-lg transition-all">
                                <i data-lucide="bell" class="w-5 h-5"></i>
                                @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                                @if($unread > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 bg-blood-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                        {{ $unread > 9 ? '9+' : $unread }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('dashboard.index') }}" class="btn-outline btn-sm">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                            </a>
                        @endif

                        <!-- User Menu -->
                        <div class="relative" x-data="{ menuOpen: false }">
                            <button @click="menuOpen = !menuOpen" class="flex items-center gap-2 p-1.5 pr-3 rounded-xl border border-gray-200 hover:border-blood-300 hover:bg-blood-50 transition-all">
                                <div class="w-7 h-7 bg-blood-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400"></i>
                            </button>

                            <div x-show="menuOpen" @click.away="menuOpen = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-50">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ ucfirst(auth()->user()->role) }}</p>
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                </div>
                                <a href="{{ route('dashboard.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-blood-700 hover:bg-blood-50 transition-colors">
                                    <i data-lucide="user" class="w-4 h-4"></i> My Profile
                                </a>
                                @if(auth()->user()->isDonor())
                                    <a href="{{ route('dashboard.donor-profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-blood-700 hover:bg-blood-50 transition-colors">
                                        <i data-lucide="heart" class="w-4 h-4"></i> Donor Profile
                                    </a>
                                @endif
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-blood-700 transition-colors px-3 py-2">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">
                            <i data-lucide="heart" class="w-4 h-4"></i> Become a Donor
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <i x-show="!open" data-lucide="menu" class="w-5 h-5"></i>
                    <i x-show="open" data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-gray-100 bg-white px-4 py-4 space-y-1">
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'nav-item-active' : '' }}">
                <i data-lucide="home" class="w-4 h-4"></i> Home
            </a>
            <a href="{{ route('donors.index') }}" class="nav-item {{ request()->routeIs('donors.*') ? 'nav-item-active' : '' }}">
                <i data-lucide="users" class="w-4 h-4"></i> Find Donors
            </a>
            <a href="{{ route('requests.index') }}" class="nav-item {{ request()->routeIs('requests.*') ? 'nav-item-active' : '' }}">
                <i data-lucide="droplet" class="w-4 h-4"></i> Blood Requests
            </a>
            <a href="{{ route('about') }}" class="nav-item">
                <i data-lucide="info" class="w-4 h-4"></i> About
            </a>
            <a href="{{ route('contact') }}" class="nav-item">
                <i data-lucide="mail" class="w-4 h-4"></i> Contact
            </a>
            <div class="pt-2 border-t border-gray-100">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="nav-item">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item w-full text-red-600 hover:bg-red-50">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-item">
                        <i data-lucide="log-in" class="w-4 h-4"></i> Sign In
                    </a>
                    <a href="{{ route('register') }}" class="nav-item text-blood-700 bg-blood-50">
                        <i data-lucide="heart" class="w-4 h-4"></i> Become a Donor
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('warning') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4" x-data="{ show: true }" x-show="show">
            @if(session('success'))
                <div class="alert-success mb-3">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-green-600"></i>
                    <p>{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-error mb-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-600"></i>
                    <p>{{ session('error') }}</p>
                    <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert-warning mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0 text-yellow-600"></i>
                    <p>{{ session('warning') }}</p>
                </div>
            @endif
            @if(session('info'))
                <div class="alert-info mb-3">
                    <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-blue-600"></i>
                    <p>{{ session('info') }}</p>
                </div>
            @endif
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blood-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C12 2 4 10.3 4 14.5C4 18.6 7.6 22 12 22C16.4 22 20 18.6 20 14.5C20 10.3 12 2 12 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Blood Lagbe</h3>
                            <p class="text-xs text-gray-400">Every Drop Counts. Every Donor Matters.</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed max-w-md">
                        Blood Lagbe is a platform connecting blood donors with patients in need across Bangladesh. 
                        Together we can save lives — one donation at a time.
                    </p>
                    <p class="mt-4 text-xs text-gray-500">
                        ⚕️ Medical Disclaimer: This platform facilitates connections only. Blood compatibility 
                        must be confirmed by qualified medical professionals.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">Home</a></li>
                        <li><a href="{{ route('donors.index') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">Find Donors</a></li>
                        <li><a href="{{ route('requests.index') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">Blood Requests</a></li>
                        <li><a href="{{ route('donors.register') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">Become a Donor</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Blood Groups -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Blood Groups</h4>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <a href="{{ route('donors.index', ['blood_group' => $group]) }}"
                               class="flex items-center justify-center h-8 w-full bg-gray-800 hover:bg-blood-700 rounded-lg text-xs font-bold text-gray-300 hover:text-white transition-all duration-200">
                                {{ $group }}
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="text-gray-400 hover:text-blood-400 text-sm transition-colors flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i> support@bloodlagbe.com
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">© {{ date('Y') }} Blood Lagbe. Built with ❤️ to save lives.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('about') }}" class="text-gray-500 hover:text-gray-400 text-xs">Privacy Policy</a>
                    <a href="{{ route('about') }}" class="text-gray-500 hover:text-gray-400 text-xs">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Init Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>

    @stack('scripts')
</body>
</html>
