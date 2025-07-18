
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ec4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>@yield('title', 'ChicChevron Beauty - Premium Beauty Products')</title>
    
    <meta name="description" content="@yield('description', 'Discover premium beauty products at ChicChevron Beauty. Shop skincare, cosmetics, and beauty essentials with fast delivery.')">
    <meta name="keywords" content="@yield('keywords', 'beauty products, skincare, cosmetics, makeup, sri lanka')">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'ChicChevron Beauty')">
    <meta property="og:description" content="@yield('og_description', 'Premium beauty products for everyone')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo-og.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Schema.org structured data -->
    @stack('schema')
</head>

<body class="font-sans antialiased bg-gray-50" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
             style="display: none;">
        </div>

        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50 safe-top">
            <!-- Top Bar - Hidden on Mobile -->
            <div class="hidden md:block bg-primary-600 text-white text-sm">
                <div class="container-responsive">
                    <div class="flex flex-wrap justify-between items-center py-2">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                            <a href="tel:+94112345678" class="flex items-center hover:text-primary-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                +94 11 234 5678
                            </a>
                            <a href="mailto:hello@chicchevron.com" class="flex items-center hover:text-primary-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                hello@chicchevron.com
                            </a>
                        </div>
                        <div class="flex items-center gap-x-4">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Free delivery over Rs. 5,000
                            </span>
                            @guest
                                <a href="{{ route('login') }}" class="hover:text-primary-200 transition-colors">Login</a>
                                <a href="{{ route('register') }}" class="hover:text-primary-200 transition-colors">Register</a>
                            @else
                                <a href="{{ route('user.account.index') }}" class="hover:text-primary-200 transition-colors">My Account</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-primary-200 transition-colors">Logout</button>
                                </form>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="container-responsive">
                <div class="flex items-center justify-between py-3 md:py-4">
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="lg:hidden touch-target -ml-2 text-gray-700 hover:text-primary-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-xl md:text-2xl font-bold text-primary-600 hover:text-primary-700 transition-colors">
                            ChicChevron Beauty
                        </a>
                    </div>

                    <!-- Desktop Search Bar -->
                    <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                        <div class="relative w-full">
                            <form action="{{ route('search') }}" method="GET" class="flex">
                                <input 
                                    type="search" 
                                    name="q" 
                                    value="{{ request('q') }}"
                                    placeholder="Search products, brands, ingredients..." 
                                    class="form-input rounded-r-none focus:z-10"
                                    autocomplete="off"
                                    id="desktop-search-input"
                                >
                                <button 
                                    type="submit" 
                                    class="btn-primary rounded-l-none px-4 md:px-6"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </form>
                            
                            <!-- Search Suggestions Dropdown -->
                            <div id="desktop-search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg hidden z-50 max-h-96 overflow-y-auto">
                                <!-- Suggestions will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center gap-2 md:gap-3">
                        <!-- Mobile Search Button -->
                        <button @click="searchOpen = !searchOpen" 
                                class="lg:hidden touch-target text-gray-700 hover:text-primary-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <!-- Wishlist -->
                        @auth
                        <a href="{{ route('wishlist.index') }}" class="touch-target text-gray-700 hover:text-primary-600 transition-colors relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="wishlist-count" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </a>
                        @endauth

                        <!-- Shopping Cart -->
                        <a href="{{ route('cart.index') }}" class="touch-target text-gray-700 hover:text-primary-600 transition-colors relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </a>

                        <!-- Desktop User Menu -->
                        <div class="hidden lg:flex items-center ml-2">
                            @guest
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-primary-600 transition-colors mr-3">Login</a>
                                <a href="{{ route('register') }}" class="btn-primary btn-sm">Register</a>
                            @else
                                <div class="relative" x-data="{ userMenuOpen: false }">
                                    <button @click="userMenuOpen = !userMenuOpen" 
                                            @click.away="userMenuOpen = false"
                                            class="flex items-center text-sm text-gray-700 hover:text-primary-600 transition-colors">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="userMenuOpen"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50"
                                         style="display: none;">
                                        <a href="{{ route('user.account.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Account</a>
                                        <a href="{{ route('user.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Orders</a>
                                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Wishlist</a>
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Mobile Search Bar -->
                <div x-show="searchOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="lg:hidden pb-3"
                     style="display: none;">
                    <form action="{{ route('search') }}" method="GET" class="relative">
                        <input 
                            type="search" 
                            name="q" 
                            value="{{ request('q') }}"
                            placeholder="Search products..." 
                            class="form-input pr-10"
                            autocomplete="off"
                            id="mobile-search-input"
                        >
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-600 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Category Navigation - Desktop -->
            <div class="hidden lg:block border-t border-gray-200 bg-gray-50">
                <div class="container-responsive">
                    <nav class="flex items-center gap-8 overflow-x-auto scrollbar-hide -mx-4 px-4">
                        <a href="{{ route('products.index') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-colors">
                            All Products
                        </a>
                        @foreach(\App\Models\Category::active()->ordered()->limit(8)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                               class="py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('about') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-colors">
                            About
                        </a>
                        <a href="{{ route('contact') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-colors">
                            Contact
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 w-80 max-w-full bg-white shadow-xl z-50 overflow-y-auto lg:hidden"
             style="display: none;">
            
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Menu</h2>
                <button @click="mobileMenuOpen = false" class="touch-target -mr-2 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Content -->
            <div class="py-4">
                @auth
                    <!-- User Info -->
                    <div class="px-4 pb-4 border-b">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('user.account.index') }}" class="mobile-menu-item">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            My Account
                        </a>
                        <a href="{{ route('user.orders.index') }}" class="mobile-menu-item">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            My Orders
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="mobile-menu-item">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            My Wishlist
                        </a>
                    </div>
                @else
                    <!-- Guest Menu Items -->
                    <div class="py-2 border-b">
                        <a href="{{ route('login') }}" class="mobile-menu-item">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="mobile-menu-item">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register
                        </a>
                    </div>
                @endauth

                <!-- Categories -->
                <div class="py-2 border-b">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Categories</h3>
                    <a href="{{ route('products.index') }}" class="mobile-menu-item">All Products</a>
                    @foreach(\App\Models\Category::active()->ordered()->limit(10)->get() as $category)
                        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="mobile-menu-item">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Other Pages -->
                <div class="py-2">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Information</h3>
                    <a href="{{ route('about') }}" class="mobile-menu-item">About Us</a>
                    <a href="{{ route('contact') }}" class="mobile-menu-item">Contact</a>
                    <a href="{{ route('faq') }}" class="mobile-menu-item">FAQ</a>
                    <a href="{{ route('terms') }}" class="mobile-menu-item">Terms & Conditions</a>
                    <a href="{{ route('privacy') }}" class="mobile-menu-item">Privacy Policy</a>
                </div>

                @auth
                    <!-- Logout -->
                    <div class="py-2 border-t">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mobile-menu-item w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Breadcrumbs -->
        @if(!request()->routeIs('home'))
            <div class="bg-white border-b border-gray-200">
                <div class="container-responsive py-3">
                    <nav class="text-xs md:text-sm">
                        @yield('breadcrumbs')
                    </nav>
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500" x-data="{ show: true }" x-show="show">
                <div class="container-responsive py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-700 text-sm md:text-base">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500" x-data="{ show: true }" x-show="show">
                <div class="container-responsive py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-red-700 text-sm md:text-base">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('partials.footer')
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

<!-- Scripts -->
@stack('scripts')

<script>
    // Global flag for checkout
    window._isCheckoutInProgress = false;

    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // Define updateCartCounter function
    async function updateCartCounter() {
            // Your existing updateCartCounter function
            if (window._isCheckoutInProgress || 
                window.location.pathname.includes('/checkout') || 
                document.querySelector('#checkout-form')) {
                console.log('Cart update blocked - checkout in progress');
                return;
            }
            
            try {
                const response = await fetch('/cart/count');
                const data = await response.json();
                
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.count || 0;
                    if (data.count > 0) {
                        cartCount.style.display = 'flex';
                        cartCount.classList.remove('hidden');
                    } else {
                        cartCount.style.display = 'none';
                        cartCount.classList.add('hidden');
                    }
                }
                
                document.querySelectorAll('.cart-count').forEach(counter => {
                    counter.textContent = data.count || 0;
                    counter.style.display = data.count > 0 ? 'flex' : 'none';
                });
                
            } catch (error) {
                console.error('Error updating cart counter:', error);
            }
        }

    async function updateWishlistCounter() {
            // Your existing updateWishlistCounter function
            if (window._isCheckoutInProgress || 
                window.location.pathname.includes('/checkout') || 
                document.querySelector('#checkout-form')) {
                console.log('Wishlist update blocked - checkout in progress');
                return;
            }
            
            try {
                const response = await fetch('/wishlist/count');
                const data = await response.json();
                
                const wishlistCount = document.getElementById('wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.count || 0;
                    if (data.count > 0) {
                        wishlistCount.style.display = 'flex';
                        wishlistCount.classList.remove('hidden');
                    } else {
                        wishlistCount.style.display = 'none';
                        wishlistCount.classList.add('hidden');
                    }
                }
                
            } catch (error) {
                console.error('Error updating wishlist counter:', error);
            }
        }

    // Search suggestions
    const searchInput = document.getElementById('search-input');
    const suggestionsDiv = document.getElementById('search-suggestions');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(suggestions => {
                        if (suggestions.length > 0) {
                            let html = '';
                            suggestions.forEach(suggestion => {
                                html += `
                                    <a href="${suggestion.url}" class="flex items-center p-3 hover:bg-gray-50 border-b border-gray-100">
                                        ${suggestion.image ? `<img src="${suggestion.image}" class="w-8 h-8 rounded mr-3" alt="">` : ''}
                                        <div>
                                            <div class="font-medium text-gray-900">${suggestion.text}</div>
                                            ${suggestion.subtitle ? `<div class="text-sm text-gray-500">${suggestion.subtitle}</div>` : ''}
                                        </div>
                                    </a>
                                `;
                            });
                            suggestionsDiv.innerHTML = html;
                            suggestionsDiv.classList.remove('hidden');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                        }
                    })
                    .catch(() => {
                        suggestionsDiv.classList.add('hidden');
                    });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    }

    // Listen for cart updates
    window.addEventListener('cart-updated', function(e) {
        if (!window._isCheckoutInProgress) {
            updateCartCounter();
        }
    });

    // Listen for wishlist updates  
    window.addEventListener('wishlist-updated', function(e) {
        if (!window._isCheckoutInProgress) {
            updateWishlistCounter();
        }
    });

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        // Only update counters if not on checkout page
        if (!window.location.pathname.includes('/checkout')) {
            updateCartCounter();
            updateWishlistCounter();
        }
    });

     // Enhanced mobile-friendly toast function
        window.showToast = function(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            toast.className = `transform transition-all duration-300 ease-in-out p-4 rounded-lg shadow-lg max-w-sm w-full ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            } text-white translate-x-full`;
            
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'success' 
                                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                            }
                        </svg>
                        <span class="text-sm">${message}</span>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="ml-3 text-white hover:text-gray-200 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        };

// Wishlist functions
function addToWishlist(productId) {
    @auth
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the heart icon
                updateWishlistButton(productId, true);
                showToast(data.message, 'success'); // Changed from showNotification
                // Update wishlist count in header
                updateWishlistCounter(); // Changed to use existing function
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding to wishlist', 'error');
        });
    @else
        // Redirect to login if not authenticated
        window.location.href = '{{ route("login") }}?redirect=' + window.location.pathname;
    @endauth
}

function removeFromWishlist(productId) {
    @auth
        fetch('/wishlist/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the heart icon
                updateWishlistButton(productId, false);
                showToast(data.message, 'success');
                // Update wishlist count in header
                updateWishlistCounter();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing from wishlist', 'error');
        });
    @else
        window.location.href = '{{ route("login") }}?redirect=' + window.location.pathname;
    @endauth
}

function toggleWishlist(productId) {
    const button = document.querySelector(`[data-product-id="${productId}"]`);
    if (button && button.classList.contains('in-wishlist')) {
        removeFromWishlist(productId);
    } else {
        addToWishlist(productId);
    }
}

function updateWishlistButton(productId, isInWishlist) {
    const buttons = document.querySelectorAll(`[data-product-id="${productId}"]`);
    buttons.forEach(button => {
        if (isInWishlist) {
            button.classList.add('in-wishlist');
            button.classList.add('text-red-500');
            button.classList.remove('text-gray-400');
            // Update icon - check if using FontAwesome or SVG
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.remove('far');
                icon.classList.add('fas');
            } else {
                // If using SVG, update the fill
                button.innerHTML = `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                </svg>`;
            }
        } else {
            button.classList.remove('in-wishlist');
            button.classList.remove('text-red-500');
            button.classList.add('text-gray-400');
            // Update icon
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.remove('fas');
                icon.classList.add('far');
            } else {
                // If using SVG, update to outline
                button.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>`;
            }
        }
    });
}



</script>
</body>
</html>