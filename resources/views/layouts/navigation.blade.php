<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ open: false, searchOpen: false, userOpen: false }">
    <!-- Primary Navigation Menu -->
    <div class="container-responsive">
        <div class="flex justify-between items-center h-16">
            <!-- Left Section: Logo & Desktop Nav -->
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button @click="open = !open" 
                        class="lg:hidden touch-target -ml-2 text-gray-600 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset rounded-lg transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" 
                              class="inline-flex" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" 
                              class="hidden" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center ml-2 lg:ml-0">
                    <a href="{{ route('home') }}" class="text-xl lg:text-2xl font-bold text-primary-600 hover:text-primary-700 transition-colors">
                        ChicChevron Beauty
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex lg:items-center lg:ml-10 space-x-8">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('home') ? 'border-b-2 border-primary-600 text-primary-600' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('products.*') ? 'border-b-2 border-primary-600 text-primary-600' : '' }}">
                        Products
                    </a>
                    <a href="{{ route('categories.index') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('categories.*') ? 'border-b-2 border-primary-600 text-primary-600' : '' }}">
                        Categories
                    </a>
                    <a href="{{ route('about') }}" 
                       class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('about') ? 'border-b-2 border-primary-600 text-primary-600' : '' }}">
                        About
                    </a>
                </div>
            </div>

            <!-- Right Section: Search & User Actions -->
            <div class="flex items-center space-x-2 lg:space-x-4">
                <!-- Search Button/Bar -->
                <div class="relative">
                    <!-- Mobile Search Toggle -->
                    <button @click="searchOpen = !searchOpen" 
                            class="lg:hidden touch-target text-gray-600 hover:text-primary-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Desktop Search Bar -->
                    <form action="{{ route('search') }}" method="GET" class="hidden lg:block">
                        <div class="relative">
                            <input type="search" 
                                   name="q" 
                                   value="{{ request('q') }}"
                                   placeholder="Search products..." 
                                   class="w-64 xl:w-80 form-input pr-10"
                                   autocomplete="off">
                            <button type="submit" 
                                    class="absolute right-0 top-0 h-full px-3 text-gray-600 hover:text-primary-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Wishlist -->
                @auth
                    <a href="{{ route('wishlist.index') }}" 
                       class="touch-target text-gray-600 hover:text-primary-600 transition-colors relative hidden sm:flex">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="wishlist-counter absolute -top-1 -right-1 h-4 w-4 bg-primary-600 text-white text-xs rounded-full flex items-center justify-center hidden">0</span>
                    </a>
                @endauth

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" 
                   class="touch-target text-gray-600 hover:text-primary-600 transition-colors relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="cart-counter absolute -top-1 -right-1 h-4 w-4 bg-primary-600 text-white text-xs rounded-full flex items-center justify-center">0</span>
                </a>

                <!-- User Dropdown -->
                @auth
                    <div class="relative hidden lg:block">
                        <button @click="userOpen = !userOpen" 
                                @click.away="userOpen = false"
                                class="flex items-center text-sm font-medium text-gray-600 hover:text-primary-600 transition-colors p-2">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="hidden xl:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="userOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-1 z-50"
                             style="display: none;">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('user.account.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">My Account</a>
                            <a href="{{ route('user.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">My Orders</a>
                            <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">My Wishlist</a>
                            <a href="{{ route('user.reviews.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">My Reviews</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-primary-600 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Register</a>
                    </div>
                @endauth
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
         class="lg:hidden border-t border-gray-100"
         style="display: none;">
        <div class="container-responsive py-3">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <input type="search" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Search products, brands..." 
                           class="form-input pr-10 w-full"
                           autocomplete="off"
                           autofocus>
                    <button type="submit" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-600 hover:text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" 
         class="lg:hidden border-t border-gray-100"
         style="display: none;">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Mobile Navigation Links -->
            <a href="{{ route('home') }}" 
               class="mobile-menu-item {{ request()->routeIs('home') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                Home
            </a>
            <a href="{{ route('products.index') }}" 
               class="mobile-menu-item {{ request()->routeIs('products.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                Products
            </a>
            <a href="{{ route('categories.index') }}" 
               class="mobile-menu-item {{ request()->routeIs('categories.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                Categories
            </a>
            <a href="{{ route('about') }}" 
               class="mobile-menu-item {{ request()->routeIs('about') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                About
            </a>
            
            @auth
                <a href="{{ route('wishlist.index') }}" 
                   class="mobile-menu-item sm:hidden {{ request()->routeIs('wishlist.*') ? 'bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    Wishlist
                    <span class="wishlist-counter-mobile inline-flex items-center justify-center ml-2 px-2 py-1 text-xs font-bold leading-none text-white bg-primary-600 rounded-full hidden">0</span>
                </a>
            @endauth
        </div>

        <!-- Mobile User Section -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            @auth
                <div class="px-4 mb-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('user.account.index') }}" class="mobile-menu-item">My Account</a>
                    <a href="{{ route('user.orders.index') }}" class="mobile-menu-item">My Orders</a>
                    <a href="{{ route('user.reviews.index') }}" class="mobile-menu-item">My Reviews</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-menu-item w-full text-left text-red-600 hover:text-red-700 hover:bg-red-50">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <a href="{{ route('login') }}" class="mobile-menu-item">Login</a>
                    <a href="{{ route('register') }}" class="mobile-menu-item bg-primary-600 text-white hover:bg-primary-700">Register</a>
                </div>
            @endguest
        </div>

        <!-- Categories Section in Mobile Menu -->
        @if(isset($mainCategories) && $mainCategories->count() > 0)
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-4 mb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Shop by Category</h3>
                </div>
                <div class="space-y-1">
                    @foreach($mainCategories as $mainCategory)
                        @if($mainCategory->categories->count() > 0)
                            <div x-data="{ mobileOpen: false }">
                                <button @click="mobileOpen = !mobileOpen" 
                                        class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">
                                    {{ $mainCategory->name }}
                                    <svg class="h-5 w-5 transition-transform duration-200" 
                                         :class="{'rotate-180': mobileOpen}" 
                                         fill="currentColor" 
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="mobileOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-1"
                                     class="bg-gray-50"
                                     style="display: none;">
                                    @foreach($mainCategory->categories as $category)
                                        <a href="{{ route('category.products', $category->slug) }}" 
                                           class="block py-2 pl-8 pr-4 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-100">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Search Modal/Dropdown -->
    <div x-show="searchOpen" 
         @click.away="searchOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-start justify-center min-h-screen pt-16 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white p-4">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="relative">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Search for products, brands, ingredients..." 
                                   class="form-input w-full pr-10"
                                   value="{{ request('q') }}"
                                   autofocus>
                            <button type="submit" 
                                    class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-gray-600 hover:text-primary-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Popular Searches -->
                    <div class="mt-4">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Popular Searches</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('search', ['q' => 'moisturizer']) }}" 
                               class="inline-block px-3 py-1 bg-gray-100 text-sm text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Moisturizer
                            </a>
                            <a href="{{ route('search', ['q' => 'serum']) }}" 
                               class="inline-block px-3 py-1 bg-gray-100 text-sm text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Serum
                            </a>
                            <a href="{{ route('search', ['q' => 'sunscreen']) }}" 
                               class="inline-block px-3 py-1 bg-gray-100 text-sm text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Sunscreen
                            </a>
                            <a href="{{ route('search', ['q' => 'cleanser']) }}" 
                               class="inline-block px-3 py-1 bg-gray-100 text-sm text-gray-700 rounded-full hover:bg-gray-200 transition-colors">
                                Cleanser
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Update cart and wishlist counters on page load -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update wishlist counter for mobile
    @auth
    const wishlistCounterMobile = document.querySelector('.wishlist-counter-mobile');
    const wishlistCounter = document.querySelector('.wishlist-counter');
    if (wishlistCounterMobile && wishlistCounter) {
        wishlistCounterMobile.textContent = wishlistCounter.textContent;
        if (parseInt(wishlistCounter.textContent) > 0) {
            wishlistCounterMobile.classList.remove('hidden');
        }
    }
    @endauth
});
</script>