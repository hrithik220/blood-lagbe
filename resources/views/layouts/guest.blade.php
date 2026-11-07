<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Blood Lagbe') }} — Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 p-4">
            <div class="mb-6">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-xl shadow-gray-200/50 rounded-2xl p-6 sm:p-8 border border-gray-100">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400 text-center">
                &copy; {{ date('Y') }} Blood Lagbe. Saving lives together.
            </p>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.lucide) lucide.createIcons();
            });
        </script>
    </body>
</html>
