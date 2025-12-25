<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'ChicChevron Beauty'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex justify-center mb-8">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-plum-800">ChicChevron</h1>
                        <p class="text-xs text-gray-500 tracking-wider">BEAUTY</p>
                    </div>
                </a>

                <!-- Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    @yield('content')
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center">
                    <div class="flex items-center justify-center gap-4 text-sm text-gray-500">
                        <a href="{{ route('home') }}" class="hover:text-plum-600 transition-colors">Home</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('contact') }}" class="hover:text-plum-600 transition-colors">Contact</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('privacy') }}" class="hover:text-plum-600 transition-colors">Privacy</a>
                    </div>
                    <p class="mt-4 text-xs text-gray-400">
                        &copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.
                    </p>
                </div>
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
