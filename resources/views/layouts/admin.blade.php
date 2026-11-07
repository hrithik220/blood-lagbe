<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — Blood Lagbe Admin</title>
    <meta name="description" content="Blood Lagbe Admin Panel">

    <!-- Preconnect for fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden">

    <!-- ═══════════════════════════════════════════
         SIDEBAR OVERLAY (mobile)
    ═══════════════════════════════════════════ -->
    <div
        x-show="sidebarOpen && window.innerWidth < 1024"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        style="display:none;"
    ></div>

    <!-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ -->
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition-transform ease-in-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform ease-in-out duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 flex flex-col lg:static lg:translate-x-0 lg:flex"
        style="display:flex;"
    >
        <!-- Sidebar Header -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-700/60">
            <div class="w-9 h-9 bg-red-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                <i data-lucide="shield" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-base font-bold text-white leading-tight">Blood Lagbe</h1>
                <p class="text-xs text-gray-400">Admin Panel</p>
            </div>
            <!-- Mobile close -->
            <button @click="sidebarOpen = false" class="ml-auto p-1 text-gray-400 hover:text-white lg:hidden">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">

            <!-- Overview -->
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-widest">Overview</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="home" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Management -->
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-widest">Management</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.users.*') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="users" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.requests.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.requests.*') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="droplet" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Blood Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.contacts.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.contacts.*') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="mail" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Contact Messages</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.testimonials.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.testimonials.*') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="star" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Testimonials</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.faqs.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                  {{ request()->routeIs('admin.faqs.*') ? 'bg-red-700 text-white' : 'text-gray-300 hover:bg-gray-700/60 hover:text-white' }}">
                            <i data-lucide="help-circle" class="w-4 h-4 flex-shrink-0"></i>
                            <span>FAQs</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- System -->
            <div>
                <p class="px-3 mb-2 text-xs font-semibold text-gray-500 uppercase tracking-widest">System</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:bg-gray-700/60 hover:text-white transition-all duration-200">
                            <i data-lucide="globe" class="w-4 h-4 flex-shrink-0"></i>
                            <span>View Public Site</span>
                            <i data-lucide="external-link" class="w-3 h-3 ml-auto text-gray-500"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar Footer: Admin User Info -->
        <div class="border-t border-gray-700/60 px-4 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-700 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Logout"
                        class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-gray-700 rounded-lg transition-all">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════
         MAIN CONTENT AREA
    ═══════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- TOP BAR -->
        <header class="bg-white border-b border-gray-200 shadow-sm z-10 flex-shrink-0">
            <div class="flex items-center justify-between px-4 sm:px-6 h-16">
                <!-- Left: Hamburger + Title/Breadcrumb -->
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 leading-tight truncate">
                            @yield('title', 'Dashboard')
                        </h2>
                        @hasSection('breadcrumb')
                        <nav class="text-xs text-gray-500 flex items-center gap-1 mt-0.5" aria-label="Breadcrumb">
                            @yield('breadcrumb')
                        </nav>
                        @endif
                    </div>
                </div>

                <!-- Right: Notifications + Admin name -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <!-- Notifications Bell -->
                    <a href="{{ route('admin.contacts.index') }}"
                       class="relative p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </a>
                    <!-- Admin name (desktop) -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-xl">
                        <div class="w-7 h-7 bg-red-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- FLASH MESSAGES -->
        <div class="px-4 sm:px-6 pt-4 space-y-3 flex-shrink-0">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="alert-success flex items-start gap-3 p-4 rounded-xl border">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                <p class="flex-1 text-sm">{{ session('success') }}</p>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="alert-error flex items-start gap-3 p-4 rounded-xl border">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                <p class="flex-1 text-sm">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif
            @if(session('warning'))
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="alert-warning flex items-start gap-3 p-4 rounded-xl border">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                <p class="flex-1 text-sm">{{ session('warning') }}</p>
                <button @click="show = false" class="text-yellow-600 hover:text-yellow-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif
            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                 class="alert-error flex items-start gap-3 p-4 rounded-xl border">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-semibold mb-1">Please fix the following errors:</p>
                    <ul class="text-sm list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            @endif
        </div>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div><!-- /main area -->
</div><!-- /flex wrapper -->

<!-- Init Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>

@stack('scripts')
</body>
</html>
