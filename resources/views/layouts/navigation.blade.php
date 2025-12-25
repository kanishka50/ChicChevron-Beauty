<!-- Modern Luxury Plum Navigation Component -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-lg shadow-plum-900/5 border-b border-lilac-200">
    <!-- Main Navigation -->
    <div class="container-responsive">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn"
                    class="lg:hidden p-2 rounded-xl hover:bg-lilac-100 transition-all duration-200">
                <svg class="w-6 h-6 text-plum-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Logo/Brand - Desktop -->
            <div class="hidden lg:flex items-center">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset('images/logo/logo-purple.png') }}"
                         alt="ChicChevron Beauty"
                         class="h-12 lg:h-14 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                </a>
            </div>

            <!-- Mobile Brand Logo (Center) -->
            <div class="lg:hidden flex-1 flex justify-center">
                <a href="{{ route('home') }}" class="block">
                    <img src="{{ asset('images/logo/logo-purple.png') }}"
                         alt="ChicChevron Beauty"
                         class="h-10 w-auto object-contain">
                </a>
            </div>

            <!-- Desktop Search -->
            <div class="hidden lg:flex flex-1 max-w-xl mx-auto px-8">
                <form action="{{ route('products.index') }}" method="GET" class="w-full">
                    <label for="desktop-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-plum-400 group-focus-within:text-plum-600 transition-colors" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search"
                               id="desktop-search"
                               name="q"
                               value="{{ request('q') }}"
                               class="block w-full py-3 pl-12 pr-24 text-sm text-plum-900 bg-lilac-50 border border-lilac-200 rounded-full focus:ring-2 focus:ring-plum-500/20 focus:border-plum-400 transition-all duration-200 placeholder:text-plum-400"
                               placeholder="Search products, brands..."
                               autocomplete="off" />
                        <button type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2 bg-gradient-to-r from-plum-700 to-plum-800 text-white text-sm font-medium rounded-full hover:from-plum-800 hover:to-plum-900 hover:shadow-lg hover:shadow-plum-500/25 transition-all duration-200">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Desktop User Actions -->
            <div class="hidden lg:flex items-center gap-2">
                <!-- Wishlist -->
                <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth"
                   class="relative p-2.5 rounded-xl hover:bg-lilac-100 transition-all duration-200 group">
                    <svg class="w-5 h-5 text-plum-700 group-hover:text-plum-800 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    @auth
                    <span id="wishlist-count" class="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-sm">0</span>
                    @endauth
                </a>

                <!-- Shopping Cart -->
                <a href="{{ route('cart.index') }}"
                   class="relative p-2.5 rounded-xl hover:bg-lilac-100 transition-all duration-200 group">
                    <svg class="w-5 h-5 text-plum-700 group-hover:text-plum-800 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span id="cart-count" class="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-sm">0</span>
                </a>

                <!-- User Menu -->
                <div class="relative ml-2">
                    @guest
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-plum-700 hover:text-plum-900 transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-plum-700 to-plum-800 text-white text-sm font-medium rounded-full hover:from-plum-800 hover:to-plum-900 hover:shadow-lg hover:shadow-plum-500/25 transition-all duration-200">
                                Sign Up
                            </a>
                        </div>
                    @else
                        <button id="userMenuBtn"
                                class="flex items-center gap-2 p-1.5 pr-3 rounded-full hover:bg-lilac-100 transition-all duration-200">
                            <div class="w-9 h-9 bg-gradient-to-br from-plum-600 to-plum-800 rounded-full flex items-center justify-center text-white font-medium text-sm shadow-md">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-plum-500 transition-transform duration-200" id="userMenuArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- User Dropdown -->
                        <div id="userDropdown" class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-xl shadow-plum-900/15 border border-gray-100 overflow-hidden hidden opacity-0 transform translate-y-2 transition-all duration-200" style="transition: opacity 0.2s, transform 0.2s;">
                            <!-- User Header -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 bg-gradient-to-br from-plum-600 to-plum-800 rounded-full flex items-center justify-center text-white font-semibold text-base shadow-md shadow-plum-500/30">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1.5">
                                <a href="{{ route('user.account.index') }}" class="group flex items-center gap-3 px-4 py-2.5 hover:bg-plum-50 transition-colors">
                                    <div class="w-9 h-9 bg-plum-50 group-hover:bg-plum-100 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-[18px] h-[18px] text-plum-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-plum-700">My Account</span>
                                        <p class="text-[11px] text-gray-400">Profile & settings</p>
                                    </div>
                                </a>
                                <a href="{{ route('user.orders.index') }}" class="group flex items-center gap-3 px-4 py-2.5 hover:bg-plum-50 transition-colors">
                                    <div class="w-9 h-9 bg-plum-50 group-hover:bg-plum-100 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-[18px] h-[18px] text-plum-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-plum-700">My Orders</span>
                                        <p class="text-[11px] text-gray-400">Track & manage orders</p>
                                    </div>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="group flex items-center gap-3 px-4 py-2.5 hover:bg-plum-50 transition-colors">
                                    <div class="w-9 h-9 bg-plum-50 group-hover:bg-plum-100 rounded-lg flex items-center justify-center transition-colors">
                                        <svg class="w-[18px] h-[18px] text-plum-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-plum-700">My Wishlist</span>
                                        <p class="text-[11px] text-gray-400">Saved items</p>
                                    </div>
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="px-3 py-2 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="group flex items-center gap-3 w-full px-3 py-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Sign out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            <!-- Mobile Search Toggle -->
            <button id="mobileSearchBtn"
                    class="lg:hidden p-2 rounded-xl hover:bg-lilac-100 transition-all duration-200">
                <svg class="w-5 h-5 text-plum-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobileSearchBar" class="lg:hidden pb-4 hidden">
            <form action="{{ route('products.index') }}" method="GET">
                <label for="mobile-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-4 h-4 text-plum-400" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search"
                        id="mobile-search"
                        name="q"
                        value="{{ request('q') }}"
                        class="block w-full py-3 pl-11 pr-24 text-sm text-plum-900 bg-lilac-50 border border-lilac-200 rounded-full focus:ring-2 focus:ring-plum-500/20 focus:border-plum-400 transition-all placeholder:text-plum-400"
                        placeholder="Search products..."
                        autocomplete="off" />
                    <button type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-2 bg-gradient-to-r from-plum-700 to-plum-800 text-white text-sm font-medium rounded-full hover:from-plum-800 hover:to-plum-900 transition-all">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-plum-900/60 backdrop-blur-sm z-40 lg:hidden hidden"></div>

<!-- Mobile Menu Drawer -->
<div id="mobileMenuDrawer" class="fixed inset-y-0 left-0 w-[85vw] max-w-[320px] bg-white shadow-2xl z-50 overflow-y-auto lg:hidden transform -translate-x-full transition-transform duration-300 ease-out">
    <!-- Mobile Menu Header -->
    <div class="sticky top-0 bg-gradient-to-br from-plum-600 to-plum-800 p-5 z-10">
        <div class="flex items-center justify-between mb-5">
            <img src="{{ asset('images/logo/logo-purple.png') }}"
                 alt="ChicChevron Beauty"
                 class="h-8 w-auto object-contain brightness-0 invert">
            <button id="closeMobileMenu"
                    class="w-8 h-8 rounded-lg bg-white/20 text-white hover:bg-white/30 transition-colors flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @auth
        <!-- User Info in Header -->
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-lg backdrop-blur-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-sm text-plum-200 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        @endauth
    </div>

    <!-- Mobile Menu Content -->
    <div class="pb-24">
        @auth
            <!-- User Menu Items -->
            <div class="p-4 space-y-1.5">
                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                    <div class="w-10 h-10 bg-plum-100 group-hover:bg-plum-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Account</span>
                </a>
                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                    <div class="w-10 h-10 bg-plum-100 group-hover:bg-plum-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Orders</span>
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                    <div class="w-10 h-10 bg-plum-100 group-hover:bg-plum-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Wishlist</span>
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                    <div class="w-10 h-10 bg-plum-100 group-hover:bg-plum-200 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Cart</span>
                    <span class="ml-auto bg-plum-600 text-white text-xs rounded-full h-5 min-w-[20px] px-1.5 flex items-center justify-center font-bold" id="mobile-cart-count">0</span>
                </a>
            </div>
        @else
            <!-- Guest Menu Items -->
            <div class="p-4 space-y-3">
                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3.5 bg-plum-700 hover:bg-plum-800 text-white rounded-xl font-semibold transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="flex items-center justify-center gap-2 w-full px-4 py-3.5 border-2 border-plum-200 text-plum-700 rounded-xl font-semibold hover:bg-plum-50 hover:border-plum-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Create Account
                </a>
            </div>
        @endauth

        <!-- Navigation Links -->
        <div class="border-t border-gray-100 px-4 py-4">
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Browse</p>
            <div class="space-y-1.5">
                <a href="{{ route('home') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group {{ request()->routeIs('home') ? 'bg-plum-50 text-plum-700' : '' }}">
                    <div class="w-10 h-10 bg-gray-100 group-hover:bg-plum-100 rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('home') ? 'bg-plum-100' : '' }}">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-plum-600 {{ request()->routeIs('home') ? 'text-plum-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Home</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-all group {{ request()->routeIs('products.*') ? 'bg-plum-50 text-plum-700' : '' }}">
                    <div class="w-10 h-10 bg-gray-100 group-hover:bg-plum-100 rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('products.*') ? 'bg-plum-100' : '' }}">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-plum-600 {{ request()->routeIs('products.*') ? 'text-plum-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">All Products</span>
                </a>
            </div>
        </div>

        @auth
            <!-- Logout -->
            <div class="px-4 py-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2.5 w-full px-4 py-3.5 bg-red-50 text-red-600 rounded-xl font-semibold hover:bg-red-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        @else
            <!-- Guest Cart -->
            <div class="px-4 py-4 border-t border-gray-100">
                <a href="{{ route('cart.index') }}"
                   class="flex items-center justify-center gap-2.5 w-full px-4 py-3.5 bg-plum-50 text-plum-700 rounded-xl font-semibold hover:bg-plum-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    View Cart
                    <span class="bg-plum-600 text-white text-xs rounded-full h-5 min-w-[20px] px-1.5 flex items-center justify-center font-bold">0</span>
                </a>
            </div>
        @endauth
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-lilac-200 z-40 safe-bottom">
    <div class="grid grid-cols-5 h-16">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('home') ? 'text-plum-700' : 'text-plum-400 hover:text-plum-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-[10px] font-medium">Home</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('products.*') ? 'text-plum-700' : 'text-plum-400 hover:text-plum-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('products.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="text-[10px] font-medium">Shop</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center justify-center gap-0.5 relative transition-colors {{ request()->routeIs('cart.*') ? 'text-plum-700' : 'text-plum-400 hover:text-plum-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('cart.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="text-[10px] font-medium">Cart</span>
            <span class="absolute top-0.5 right-3.5 bg-gradient-to-r from-gold-500 to-gold-600 text-white text-[9px] rounded-full h-4 w-4 flex items-center justify-center font-bold">0</span>
        </a>
        <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-plum-700' : 'text-plum-400 hover:text-plum-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('wishlist.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span class="text-[10px] font-medium">Wishlist</span>
        </a>
        <a href="@auth{{ route('user.account.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-0.5 transition-colors {{ request()->routeIs('user.*') ? 'text-plum-700' : 'text-plum-400 hover:text-plum-600' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('user.*') ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-[10px] font-medium">Account</span>
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

    // User menu dropdown with animation
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const userMenuArrow = document.getElementById('userMenuArrow');

    if (userMenuBtn && userDropdown) {
        let isOpen = false;

        function openDropdown() {
            isOpen = true;
            userDropdown.classList.remove('hidden');
            // Trigger animation after display change
            requestAnimationFrame(() => {
                userDropdown.classList.remove('opacity-0', 'translate-y-2');
                userDropdown.classList.add('opacity-100', 'translate-y-0');
            });
            if (userMenuArrow) {
                userMenuArrow.classList.add('rotate-180');
            }
        }

        function closeDropdown() {
            isOpen = false;
            userDropdown.classList.remove('opacity-100', 'translate-y-0');
            userDropdown.classList.add('opacity-0', 'translate-y-2');
            if (userMenuArrow) {
                userMenuArrow.classList.remove('rotate-180');
            }
            // Hide after animation completes
            setTimeout(() => {
                if (!isOpen) {
                    userDropdown.classList.add('hidden');
                }
            }, 200);
        }

        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (isOpen && !userDropdown.contains(e.target)) {
                closeDropdown();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeDropdown();
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

        if (currentScroll > 50) {
            header.classList.add('shadow-xl', 'shadow-plum-900/10');
        } else {
            header.classList.remove('shadow-xl', 'shadow-plum-900/10');
        }

        lastScroll = currentScroll;
    });
});
</script>
