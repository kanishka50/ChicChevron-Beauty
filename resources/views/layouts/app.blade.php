
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
        

        <!-- Header -->
        
        <!-- Navigation -->
        @include('layouts.navigation')

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
        @include('layouts.footer')
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