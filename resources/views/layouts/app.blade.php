<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

<body class="font-sans antialiased bg-gray-50">
    <div id="app" class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <!-- Top Bar -->
            <div class="bg-pink-600 text-white text-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center space-x-4">
                            <span>📞 +94 11 234 5678</span>
                            <span>✉️ hello@chicchevron.com</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span>🚚 Free delivery over Rs. 5,000</span>
                            @guest
                                <a href="{{ route('login') }}" class="hover:text-pink-200">Login</a>
                                <a href="{{ route('register') }}" class="hover:text-pink-200">Register</a>
                            @else
                                <a href="{{ route('user.account.index') }}" class="hover:text-pink-200">My Account</a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-pink-200">Logout</button>
                                </form>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-pink-600">
                            ChicChevron Beauty
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                        <div class="relative w-full">
                            <form action="{{ route('search') }}" method="GET" class="flex">
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ request('q') }}"
                                    placeholder="Search products, brands, ingredients..." 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                                    autocomplete="off"
                                    id="search-input"
                                >
                                <button 
                                    type="submit" 
                                    class="px-6 py-2 bg-pink-600 text-white rounded-r-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                >
                                    🔍
                                </button>
                            </form>
                            
                            <!-- Search Suggestions Dropdown -->
                            <div id="search-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-lg hidden z-50">
                                <!-- Suggestions will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}" class="relative p-2 text-gray-600 hover:text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span id="wishlist-count" class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </a>

                        <!-- Shopping Cart -->
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-pink-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9h10.5m-9-9L5.4 5m1.6 8a2 2 0 11-4 0 2 2 0 014 0zm9 0a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </a>

                        <!-- Mobile Menu Button -->
                        <button type="button" class="md:hidden p-2 text-gray-600 hover:text-pink-600" id="mobile-menu-button">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category Navigation -->
            <div class="border-t border-gray-200 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex space-x-8 overflow-x-auto">
                        <a href="{{ route('products.index') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-pink-600 whitespace-nowrap">
                            All Products
                        </a>
                        @foreach(\App\Models\Category::active()->ordered()->limit(8)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="py-3 text-sm font-medium text-gray-700 hover:text-pink-600 whitespace-nowrap">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('about') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-pink-600 whitespace-nowrap">
                            About
                        </a>
                        <a href="{{ route('contact') }}" class="py-3 text-sm font-medium text-gray-700 hover:text-pink-600 whitespace-nowrap">
                            Contact
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden bg-white border-t border-gray-200 hidden">
                <div class="px-4 py-3">
                    <!-- Mobile Search -->
                    <form action="{{ route('search') }}" method="GET" class="mb-4">
                        <div class="flex">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Search..." 
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                            <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-r-lg">
                                🔍
                            </button>
                        </div>
                    </form>

                    <!-- Mobile Navigation Links -->
                    <div class="space-y-2">
                        @guest
                            <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-pink-600">Login</a>
                            <a href="{{ route('register') }}" class="block py-2 text-gray-700 hover:text-pink-600">Register</a>
                        @else
                            <a href="{{ route('user.account.index') }}" class="block py-2 text-gray-700 hover:text-pink-600">My Account</a>
                            <a href="{{ route('user.orders.index') }}" class="block py-2 text-gray-700 hover:text-pink-600">My Orders</a>
                        @endguest
                    </div>
                </div>
            </div>
        </header>

        <!-- Breadcrumbs -->
        @if(!request()->routeIs('home'))
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    @yield('breadcrumbs')
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 mt-4 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
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
                    <!-- Company Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">ChicChevron Beauty</h3>
                        <p class="text-gray-300 mb-4">Your premier destination for authentic beauty products in Sri Lanka.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white">📘</a>
                            <a href="#" class="text-gray-300 hover:text-white">📷</a>
                            <a href="#" class="text-gray-300 hover:text-white">🐦</a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">Contact</a></li>
                            <li><a href="{{ route('faq') }}" class="text-gray-300 hover:text-white">FAQ</a></li>
                            <li><a href="{{ route('terms') }}" class="text-gray-300 hover:text-white">Terms & Conditions</a></li>
                            <li><a href="{{ route('privacy') }}" class="text-gray-300 hover:text-white">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Categories</h3>
                        <ul class="space-y-2">
                            @foreach(\App\Models\Category::active()->ordered()->limit(6)->get() as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="text-gray-300 hover:text-white">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li>📍 123 Beauty Street, Colombo, Sri Lanka</li>
                            <li>📞 +94 11 234 5678</li>
                            <li>✉️ hello@chicchevron.com</li>
                            <li>🕐 Mon - Fri: 9:00 AM - 6:00 PM</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                    <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

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
        // ALWAYS check if we're in checkout process
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
        // ALWAYS check if we're in checkout process
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

    // Global toast function
    window.showToast = function(message, type = 'success') {
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 transform translate-y-full transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        } text-white max-w-sm`;
        
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-full');
        }, 100);

        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('translate-y-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    };
</script>
</body>
</html>