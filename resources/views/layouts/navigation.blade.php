<!-- Modern Navigation Component -->
<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false, searchOpen: false, userMenuOpen: false }">
    <!-- Top Bar - Desktop Only with Subtle Design -->
    <div class="hidden lg:block bg-gradient-to-r from-primary-50 to-primary-100 text-primary-800 text-sm">
        <div class="container-responsive">
            <div class="flex justify-between items-center py-2">
                <div class="flex items-center gap-6">
                    <a href="tel:+94112345678" class="flex items-center hover:text-primary-900 transition-colors group">
                        <svg class="w-4 h-4 mr-1.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        +94 11 234 5678
                    </a>
                    <a href="mailto:hello@chicchevron.com" class="flex items-center hover:text-primary-900 transition-colors">
                        <svg class="w-4 h-4 mr-1.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        hello@chicchevron.com
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="font-medium">Free delivery over Rs. 5,000</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="bg-white border-b border-gray-100">
        <div class="container-responsive">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Mobile Menu Button - Better Design -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="lg:hidden relative w-10 h-10 -ml-2 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200 flex items-center justify-center">
                    <div class="w-6">
                        <span class="block h-0.5 w-full bg-gray-700 transition-all duration-300 transform" 
                              :class="mobileMenuOpen ? 'rotate-45 translate-y-1.5' : ''"></span>
                        <span class="block h-0.5 w-full bg-gray-700 transition-all duration-300 my-1" 
                              :class="mobileMenuOpen ? 'opacity-0' : ''"></span>
                        <span class="block h-0.5 w-full bg-gray-700 transition-all duration-300 transform" 
                              :class="mobileMenuOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
                    </div>
                </button>

                <!-- Logo - Responsive -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="group flex items-center">
                        <!-- Mobile Logo -->
                        <h1 class="lg:hidden text-xl font-bold bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent">
                            ChicChevron
                        </h1>
                        <!-- Desktop Logo -->
                        <h1 class="hidden lg:block text-2xl xl:text-3xl font-bold bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent group-hover:from-primary-700 group-hover:to-primary-800 transition-all duration-300">
                            ChicChevron Beauty
                        </h1>
                    </a>
                </div>

                <!-- Desktop Search Bar - Enhanced -->
                <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                    <div class="relative w-full">
                        <form action="{{ route('search') }}" method="GET" class="flex">
                            <input 
                                type="search" 
                                name="q" 
                                value="{{ request('q') }}"
                                placeholder="Search products, brands, ingredients..." 
                                class="w-full pl-5 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-l-full focus:bg-white focus:border-primary-400 focus:outline-none transition-all duration-200 text-sm placeholder-gray-400"
                                autocomplete="off"
                            >
                            <button 
                                type="submit" 
                                class="px-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-r-full hover:from-primary-700 hover:to-primary-800 transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User Actions - Enhanced Visibility -->
                <div class="flex items-center gap-1 md:gap-2">
                    <!-- Mobile Search Button -->
                    <button @click="searchOpen = !searchOpen" 
                            class="lg:hidden w-10 h-10 rounded-lg bg-gray-50 hover:bg-gray-100 transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Wishlist - Better Visibility -->
                    <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth" 
                       class="relative w-10 h-10 rounded-lg bg-gray-50 hover:bg-primary-50 transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        @auth
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-sm">0</span>
                        @endauth
                    </a>

                    <!-- Shopping Cart - Better Visibility -->
                    <a href="{{ route('cart.index') }}" 
                       class="relative w-10 h-10 rounded-lg bg-gray-50 hover:bg-primary-50 transition-all duration-200 flex items-center justify-center group">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-sm">0</span>
                    </a>

                    <!-- User Menu - Desktop -->
                    <div class="hidden lg:flex items-center ml-2">
                        @guest
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white text-sm font-medium rounded-full hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-md hover:shadow-lg">
                                Register
                            </a>
                        @else
                            <div class="relative">
                                <button @click="userMenuOpen = !userMenuOpen" 
                                        @click.away="userMenuOpen = false"
                                        class="flex items-center gap-2 px-3 py-2 rounded-full hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-medium text-sm shadow-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden xl:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" 
                                         :class="{'rotate-180': userMenuOpen}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="userMenuOpen"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden"
                                     style="display: none;">
                                    <div class="p-4 bg-gradient-to-br from-gray-50 to-white border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-2">
                                        <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            My Account
                                        </a>
                                        <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                            My Orders
                                        </a>
                                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                            <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>
                                            My Wishlist
                                        </a>
                                    </div>
                                    <div class="p-2 border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Mobile Search Bar - Slide Down -->
            <div x-show="searchOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="lg:hidden pb-4 -mx-4 px-4 border-t border-gray-100 mt-2"
                 style="display: none;">
                <form action="{{ route('search') }}" method="GET" class="relative mt-4">
                    <input 
                        type="search" 
                        name="q" 
                        value="{{ request('q') }}"
                        placeholder="Search products..." 
                        class="w-full pl-5 pr-12 py-3 bg-gray-50 border-2 border-gray-200 rounded-full focus:bg-white focus:border-primary-400 focus:outline-none transition-all duration-200"
                        autocomplete="off"
                    >
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center hover:bg-primary-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Category Navigation - Desktop -->
    <nav class="hidden lg:block bg-white border-b border-gray-100">
        <div class="container-responsive">
            <div class="flex items-center gap-8 overflow-x-auto scrollbar-hide">
                <a href="{{ route('products.index') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group {{ request()->routeIs('products.index') && !request('category') ? 'text-primary-600' : '' }}">
                    All Products
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform {{ request()->routeIs('products.index') && !request('category') ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
                @foreach(\App\Models\Category::active()->ordered()->limit(8)->get() as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group {{ request('category') == $category->id ? 'text-primary-600' : '' }}">
                        {{ $category->name }}
                        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform {{ request('category') == $category->id ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-200"></span>
                    </a>
                @endforeach
                <a href="{{ route('about') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group {{ request()->routeIs('about') ? 'text-primary-600' : '' }}">
                    About
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform {{ request()->routeIs('about') ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
                <a href="{{ route('contact') }}" 
                   class="relative py-3 text-sm font-medium text-gray-700 hover:text-primary-600 whitespace-nowrap transition-all duration-200 group {{ request()->routeIs('contact') ? 'text-primary-600' : '' }}">
                    Contact
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-600 transform {{ request()->routeIs('contact') ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-200"></span>
                </a>
            </div>
        </div>
    </nav>
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

<!-- Mobile Menu Drawer - Modern Design -->
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
    <div class="sticky top-0 bg-gradient-to-r from-primary-600 to-primary-700 p-4 z-10">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Menu</h2>
            <button @click="mobileMenuOpen = false" 
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
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- User Menu Items -->
            <div class="py-2">
                <a href="{{ route('user.account.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors"
                   @click="mobileMenuOpen = false">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Account</span>
                </a>
                <a href="{{ route('user.orders.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors"
                   @click="mobileMenuOpen = false">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Orders</span>
                </a>
                <a href="{{ route('wishlist.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors"
                   @click="mobileMenuOpen = false">
                    <div class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium">My Wishlist</span>
                </a>
            </div>
        @else
            <!-- Guest Menu Items -->
            <div class="p-4 space-y-3">
                <a href="{{ route('login') }}" 
                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary-600 text-white rounded-xl font-medium hover:bg-primary-700 transition-colors">
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
            </div>
        @endauth

        <!-- Categories -->
        <div class="border-t border-gray-100">
            <div class="px-4 py-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Categories</h3>
            </div>
            <div class="pb-2">
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors {{ request()->routeIs('products.index') && !request('category') ? 'bg-primary-50 text-primary-600' : '' }}"
                   @click="mobileMenuOpen = false">
                    <span class="font-medium">All Products</span>
                </a>
                @foreach(\App\Models\Category::active()->ordered()->limit(10)->get() as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="flex items-center justify-between px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors {{ request('category') == $category->id ? 'bg-primary-50 text-primary-600' : '' }}"
                       @click="mobileMenuOpen = false">
                        <span>{{ $category->name }}</span>
                        <span class="text-xs text-gray-500">({{ $category->products_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Other Pages -->
        <div class="border-t border-gray-100">
            <div class="px-4 py-3">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Information</h3>
            </div>
            <div class="pb-2">
                <a href="{{ route('about') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors" @click="mobileMenuOpen = false">About Us</a>
                <a href="{{ route('contact') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors" @click="mobileMenuOpen = false">Contact</a>
                <a href="{{ route('faq') }}" class="flex items-center px-4 py-2.5 text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors" @click="mobileMenuOpen = false">FAQ</a>
            </div>
        </div>

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
            <span class="absolute top-1 right-4 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-bold">0</span>
        </a>
        <a href="@auth{{ route('wishlist.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span class="text-xs">Wishlist</span>
        </a>
        <a href="@auth{{ route('user.account.index') }}@else{{ route('login') }}@endauth" class="flex flex-col items-center justify-center gap-1 text-gray-600 hover:text-primary-600 transition-colors {{ request()->routeIs('user.*') ? 'text-primary-600' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs">Account</span>
        </a>
    </div>
</nav>

<style>
/* Additional styles for smooth transitions */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Safe area for notched devices */
.safe-top {
    padding-top: env(safe-area-inset-top);
}
.safe-bottom {
    padding-bottom: env(safe-area-inset-bottom);
}

/* Active navigation indicator animation */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}
</style>