<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-slate-800">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile only) -->
        <div x-show="sidebarOpen" x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm md:hidden" style="display: none;">
        </div>

        <x-dashboard.sidebar :active="$activePage ?? ''" />

        <div class="flex-1 flex flex-col overflow-hidden w-full transition-all duration-300">
            <x-dashboard.navbar :user="auth()->user()" onMenuClick="sidebarOpen = !sidebarOpen" />

            <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
