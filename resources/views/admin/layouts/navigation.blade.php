<header class="bg-white border-b border-gray-200 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page Title -->
                <div class="ml-4 lg:ml-0">
                    <h1 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h1>
                </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Quick Stats (Optional - can be removed if not needed) -->
                <div class="hidden md:flex items-center space-x-6 text-sm">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ now()->format('D, M d') }}</span>
                    </div>
                </div>

                <!-- Divider -->
                <div class="hidden md:block h-6 w-px bg-gray-200"></div>

                <!-- Admin Profile Dropdown -->
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen" 
                            @click.away="dropdownOpen = false"
                            class="flex items-center space-x-3 text-sm rounded-lg hover:bg-gray-50 px-3 py-2 transition-colors">
                        <!-- Avatar -->
                        <div class="h-8 w-8 rounded-full bg-gray-600 flex items-center justify-center">
                            <span class="text-white font-medium text-sm">
                                {{ substr(Auth::guard('admin')->user()->name, 0, 1) }}
                            </span>
                        </div>
                        
                        <!-- Name and Role -->
                        <div class="hidden md:block text-left">
                            <div class="font-medium text-gray-700">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="text-xs text-gray-500">Administrator</div>
                        </div>
                        
                        <!-- Dropdown arrow -->
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                         style="display: none;">
                        
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-200">
                            <div class="text-sm font-medium text-gray-900">{{ Auth::guard('admin')->user()->name }}</div>
                            <div class="text-sm text-gray-500 break-all">{{ Auth::guard('admin')->user()->email }}</div>
                        </div>
                        
                        
                        <!-- Logout -->
                        <div class="border-t border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>