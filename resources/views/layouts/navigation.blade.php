<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50 safe-top">
    <!-- Top Bar - Hidden on Mobile -->
    <div class="hidden md:block bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm">
        <div class="container-responsive">
            <div class="flex flex-wrap justify-between items-center py-2">
                <div class="flex flex-wrap items-center gap-x-6 gap-y-1">
                    <a href="tel:+94112345678" class="flex items-center hover:text-white/80 transition-all duration-200 group">
                        <div class="p-1.5 bg-white/10 rounded-full mr-2 group-hover:bg-white/20 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <span class="text-sm">+94 11 234 5678</span>
                    </a>
                    <a href="mailto:hello@chicchevron.com" class="flex items-center hover:text-white/80 transition-all duration-200 group">
                        <div class="p-1.5 bg-white/10 rounded-full mr-2 group-hover:bg-white/20 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm">hello@chicchevron.com</span>
                    </a>
                </div>
                <div class="flex items-center gap-x-4">
                    <span class="flex items-center bg-white/10 px-3 py-1 rounded-full">
                        <svg class="w-4 h-4 mr-2 text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="text-sm font-medium">Free delivery over Rs. 5,000</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="bg-white border-b border-gray-100">
        <div class="container-responsive">
            <div class="flex items-center justify-between py-4 md:py-5">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="lg:hidden relative p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors group">
                    <div class="flex flex-col justify-center items-center w-6 h-6">
                        <span class="block h-0.5 w-5 bg-gray-600 group-hover:bg-primary-600 transition-all duration-200 transform" 
                              :class="mobileMenuOpen ? 'rotate-45 translate-y-1.5' : ''"></span>
                        <span class="block h-0.5 w-5 bg-gray-600 group-hover:bg-primary-600 transition-all duration-200 my-1" 
                              :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                        <span class="block h-0.5 w-5 bg-gray-600 group-hover:bg-primary-600 transition-all duration-200 transform" 
                              :class="mobileMenuOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                    </div>
                </button>

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="group">
                        <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent group-hover:from-primary-700 group-hover:to-primary-800 transition-all duration-300">
                            ChicChevron Beauty
                        </h1>
                    </a>
                </div>

                <!-- Desktop Search Bar -->
                <div class="hidden lg:flex flex-1 max-w-xl mx-8">
                    <div class="relative w-full group">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <div class="relative flex-1">
                                <input 
                                    type="search" 
                                    name="q" 
                                    value="{{ request('q') }}"
                                    placeholder="Search products, brands, ingredients..." 
                                    class="w-full pl-4 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-l-xl focus:bg-white focus:border-primary-400 focus:outline-none transition-all duration-200 text-sm"
                                    autocomplete="off"
                                    id="desktop-search-input"
                                >
                            </div>
                            <button 
                                type="submit" 
                                class="px-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-r-xl hover:from-primary-700 hover:to-primary-800 transition-all duration-200 flex items-center justify-center group-hover:shadow-lg"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                        
                        <!-- Search Suggestions Dropdown -->
                        <div id="desktop-search-suggestions" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-xl hidden z-50 max-h-96 overflow-y-auto">
                            <!-- Suggestions will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="flex items-center gap-1 md:gap-2">
                    <!-- Mobile Search Button -->
                    <button @click="searchOpen = !searchOpen" 
                            class="lg:hidden p-2.5 rounded-lg hover:bg-gray-100 transition-colors group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Wishlist -->
                    <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}@endauth" 
                       class="relative p-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        @auth
                        <span id="wishlist-count" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium hidden animate-fadeIn">0</span>
                        @endauth
                    </a>

                    <!-- Shopping Cart -->
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span id="cart-count" class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium hidden animate-fadeIn">0</span>
                    </a>

                    <!-- Desktop User Menu -->
                    <div class="hidden lg:flex items-center ml-3">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors mr-3">Login</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-medium rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 hover:shadow-lg">
                                Register
                            </a>
                        @else
                            <div class="relative" x-data="{ userMenuOpen: false }">
                                <button @click="userMenuOpen = !userMenuOpen" 
                                        @click.away="userMenuOpen = false"
                                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors group">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden md:inline text-sm font-medium text-gray-700 group-hover:text-primary-600">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" 
                                         :class="{'rotate-180': userMenuOpen}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                     class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50"
                                     style="display: none;">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        My Account
                                    </a>
                                    <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        My Orders
                                    </a>
                                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        My Wishlist
                                    </a>
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
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
                 class="lg:hidden pb-4 px-4"
                 style="display: none;">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Search products..." 
                        class="w-full pl-4 pr-12 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary-400 focus:outline-none transition-all duration-200"
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
    </div>

    <!-- Category Navigation - Desktop -->
    <div class="hidden lg:block bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
        <div class="container-responsive">
            <nav class="flex items-center gap-8 overflow-x-auto scrollbar-hide -mx-4 px-4">
                <a href="{{ route('products.index') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group">
                    All Products
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
                @foreach(\App\Models\Category::active()->ordered()->limit(8)->get() as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group">
                        {{ $category->name }}
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                    </a>
                @endforeach
                <a href="{{ route('about') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group">
                    About
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
                <a href="{{ route('contact') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group">
                    Contact
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
            </nav>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div x-show="mobileMenuOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileMenuOpen = false"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
     style="display: none;">
</div>

<!-- Mobile Menu Drawer -->
<div x-show="mobileMenuOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 w-80 max-w-full bg-white shadow-2xl z-50 overflow-y-auto lg:hidden"
     style="display: none;">
    
    <!-- Mobile Menu Header -->
    <div class="flex items-center justify-between p-4 border-b border-gray-100 bg-gradient-to-r from-primary-600 to-primary-700">
        <h2 class="text-lg font-semibold text-white">Menu</h2>
        <button @click="mobileMenuOpen = false" class="p-2 rounded-lg bg-white/20 text-white hover:bg-white/30 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu Content -->
    <div class="py-4">
        @auth
            <!-- User Info -->
            <div class="px-4 pb-4 border-b border-gray-100">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- User Menu Items -->
            <div class="py-2">
                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Account</span>
                </a>
                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Orders</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Wishlist</span>
                </a>
            </div>
        @else
            <!-- Guest Menu Items -->
            <div class="py-2 border-b border-gray-100">
                <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Login</span>
                </a>
                <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Register</span>
                </a>
            </div>
        @endauth

        <!-- Categories -->
        <div class="py-2 border-b border-gray-100">
            <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Categories</h3>
            <a href="{{ route('products.index') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                <span class="font-medium">All Products</span>
            </a>
            @foreach(\App\Models\Category::active()->ordered()->limit(10)->get() as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        <!-- Other Pages -->
        <div class="py-2">
            <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Information</h3>
            <a href="{{ route('about') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">About Us</a>
            <a href="{{ route('contact') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">Contact</a>
            <a href="{{ route('faq') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">FAQ</a>
            <a href="{{ route('terms') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">Terms & Conditions</a>
            <a href="{{ route('privacy') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">Privacy Policy</a>
        </div>

        @auth
            <!-- Logout -->
            <div class="py-2 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>