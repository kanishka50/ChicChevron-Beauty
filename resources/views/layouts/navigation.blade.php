<!-- Modern Navigation Component -->
<header class="sticky top-0 z-50 bg-white shadow-lg shadow-gray-100/20 border-b border-gray-100">
    <!-- Main Navigation -->
    <div class="container-responsive">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" 
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Logo/Brand - Desktop -->
            <div class="hidden lg:flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <!-- Modern SVG Logo -->
                    <svg class="w-8 h-8 lg:w-10 lg:h-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="18" class="fill-primary-100 group-hover:fill-primary-200 transition-colors"/>
                        <path d="M20 8C20 8 12 12 12 20C12 28 20 32 20 32C20 32 28 28 28 20C28 12 20 8 20 8Z" class="fill-primary-500 group-hover:fill-primary-600 transition-colors"/>
                        <path d="M20 14L22.5 19L28 20L24 24L25 29L20 26.5L15 29L16 24L12 20L17.5 19L20 14Z" class="fill-white"/>
                    </svg>
                    <!-- Brand Name -->
                    <div>
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-800">
                            ChicChevron
                        </h1>
                        <p class="text-xs text-gray-500 -mt-1">Beauty & Cosmetics</p>
                    </div>
                </a>
            </div>

            <!-- Mobile Brand Name (Center) -->
            <div class="lg:hidden flex-1 text-center">
                <h1 class="text-lg font-bold text-primary-600">ChicChevron</h1>
            </div>

            <!-- Desktop Search -->
            <div class="hidden lg:flex flex-1 max-w-xl mx-auto px-8">
                <form action="{{ route('search') }}" method="GET" class="w-full">
                    <label for="desktop-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" 
                               id="desktop-search"
                               name="q" 
                               value="{{ request('q') }}"
                               class="block w-full p-3 pl-10 pr-20 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500" 
                               placeholder="Search products..." 
                               required />
                        <button type="submit" 
                                class="text-white absolute right-2 bottom-2 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-3 py-1.5">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Desktop User Actions -->
            <div class="hidden lg:flex items-center gap-3">
                <!-- Wishlist -->
                <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth" 
                   class="relative p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                    <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @auth
                    <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                    @endauth
                </a>

                <!-- Shopping Cart -->
                <a href="{{ route('cart.index') }}" 
                   class="relative p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                    <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                </a>

                <!-- User Menu -->
                <div class="relative ml-2">
                    @guest
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-full hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-200/50 transition-all duration-200">
                                Sign Up
                            </a>
                        </div>
                    @else
                        <button id="userMenuBtn" 
                                class="flex items-center gap-2 p-2 rounded-full hover:bg-gray-50 transition-all duration-200">
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white font-medium text-sm shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="userMenuArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown -->
                        <div id="userDropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden">
                            <div class="bg-gray-50 p-4 border-b border-gray-100">
                                <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-600 mt-0.5">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-700 transition-colors">
                                    <div class="w-9 h-9 bg-primary-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 818 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">My Account</span>
                                </a>
                                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-700 transition-colors">
                                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">My Orders</span>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-700 transition-colors">
                                    <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">My Wishlist</span>
                                </a>
                            </div>
                            <div class="p-2 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span class="font-medium">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            <!-- Mobile Search Toggle -->
            <button id="mobileSearchBtn" 
                    class="lg:hidden p-2 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobileSearchBar" class="lg:hidden pb-4 hidden">
            <form action="{{ route('search') }}" method="GET">
                <label for="mobile-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" 
                           id="mobile-search"
                           name="q" 
                           value="{{ request('q') }}"
                           class="block w-full p-3 pl-10 pr-20 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500" 
                           placeholder="Search products..." 
                           required />
                    <button type="submit" 
                            class="text-white absolute right-2 bottom-2 bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-3 py-1.5">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden hidden"></div>

<!-- Mobile Menu Drawer -->
<div id="mobileMenuDrawer" class="fixed inset-y-0 left-0 w-80 max-w-full bg-white shadow-2xl z-50 overflow-y-auto lg:hidden transform -translate-x-full transition-transform duration-300">
    <!-- Mobile Menu Header -->
    <div class="sticky top-0 bg-primary-600 p-4 z-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Menu</h2>
            <button id="closeMobileMenu" 
                    class="w-8 h-8 rounded-lg bg-white/20 text-white hover:bg-white/30 transition-colors flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Content -->
    <div class="pb-20">
        @auth
            <!-- User Info -->
            <div class="p-4 bg-primary-50 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- User Menu Items -->
            <div class="py-2">
                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-primary-50 transition-colors">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 818 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Account</span>
                </a>
                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-primary-50 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Orders</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-primary-50 transition-colors">
                    <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Wishlist</span>
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-primary-50 transition-colors relative">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Cart</span>
                    <span class="absolute right-4 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                </a>
            </div>
        @else
            <!-- Guest Menu Items -->
            <div class="p-4 space-y-3">
                <a href="{{ route('login') }}" 
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary-600 text-white rounded-xl font-medium hover:bg-primary-700 hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Register
                </a>
                
                <!-- Guest Cart Button -->
                <a href="{{ route('cart.index') }}" 
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-white border-2 border-primary-600 text-primary-600 rounded-xl font-medium hover:bg-primary-50 transition-colors relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    View Cart
                    <span class="absolute right-4 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                </a>
            </div>
        @endauth

        @auth
            <!-- Logout -->
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-red-50 text-red-600 rounded-xl font-medium hover:bg-red-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40 safe-bottom">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('home') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs">Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('products.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="text-xs">Shop</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors relative {{ request()->routeIs('cart.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-xs">Cart</span>
            <span class="absolute top-1 right-4 bg-primary-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold">0</span>
        </a>
        <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span class="text-xs">Wishlist</span>
        </a>
        <a href="@auth{{ route('user.account.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('user.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 818 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs">Account</span>
        </a>
    </div>
</nav>

<style>
/* Custom scrollbar hide */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Safe area for mobile devices */
.safe-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeMobileMenu = document.getElementById('closeMobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuDrawer = document.getElementById('mobileMenuDrawer');
    
    function openMobileMenu() {
        mobileMenuOverlay.classList.remove('hidden');
        mobileMenuDrawer.classList.remove('-translate-x-full');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenuFunc() {
        mobileMenuOverlay.classList.add('hidden');
        mobileMenuDrawer.classList.add('-translate-x-full');
        document.body.style.overflow = 'auto';
    }
    
    mobileMenuBtn.addEventListener('click', openMobileMenu);
    closeMobileMenu.addEventListener('click', closeMobileMenuFunc);
    mobileMenuOverlay.addEventListener('click', closeMobileMenuFunc);
    
    // Mobile search toggle
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    
    mobileSearchBtn.addEventListener('click', function() {
        mobileSearchBar.classList.toggle('hidden');
    });
    
    // User menu dropdown
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const userMenuArrow = document.getElementById('userMenuArrow');
    
    if (userMenuBtn) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
            if (userMenuArrow) {
                userMenuArrow.classList.toggle('rotate-180');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            if (!userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
                if (userMenuArrow) {
                    userMenuArrow.classList.remove('rotate-180');
                }
            }
        });
        
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Sticky header scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        const header = document.querySelector('header');
        
        if (currentScroll > 100) {
            header.classList.add('shadow-lg');
        } else {
            header.classList.remove('shadow-lg');
        }
        
        lastScroll = currentScroll;
    });
});
</script>