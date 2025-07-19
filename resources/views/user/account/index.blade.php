@extends('layouts.app')

@section('title', 'My Account - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <!-- Mobile Header with Menu Toggle -->
    <div class="lg:hidden sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="flex items-center justify-between px-4 py-3">
            <h1 class="text-xl font-bold text-gray-900">My Account</h1>
            <button id="mobileMenuToggle" class="touch-target rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileDrawer" class="fixed inset-0 z-50 lg:hidden hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="mobileBackdrop"></div>
        
        <!-- Drawer Content -->
        <div class="fixed right-0 top-0 h-full w-80 max-w-full bg-white shadow-2xl transform translate-x-full transition-transform duration-300" id="drawerContent">
            <div class="flex flex-col h-full">
                <!-- Drawer Header -->
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Account Menu</h2>
                        <button id="closeDrawer" class="touch-target">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <p class="text-lg font-medium">{{ $user->name }}</p>
                        <p class="text-sm opacity-90">{{ $user->email }}</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <a href="{{ route('user.orders.index') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span>My Orders</span>
                    </a>
                    
                    <a href="{{ route('user.account.profile') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span>Profile</span>
                    </a>
                    
                    <a href="{{ route('user.account.addresses') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>Addresses</span>
                    </a>
                    
                    <a href="{{ route('wishlist.index') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span>Wishlist</span>
                    </a>
                    
                    <a href="{{ route('user.reviews.index') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <span>Reviews</span>
                    </a>
                    
                    <a href="{{ route('user.complaints.index') }}" class="mobile-menu-item flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <span>Complaints</span>
                    </a>
                </nav>
                
                <!-- Drawer Footer -->
                <div class="border-t border-gray-200 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left mobile-menu-item text-red-600 hover:bg-red-50">
                            <svg class="w-5 h-5 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-responsive py-6 lg:py-8">
        <!-- Desktop Page Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
            <p class="mt-2 text-gray-600">Welcome back, {{ $user->name }}!</p>
        </div>

        <!-- Welcome Card for Mobile -->
        <div class="lg:hidden mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-lg">
                <h2 class="text-lg font-semibold">Welcome back!</h2>
                <p class="text-2xl font-bold mt-1">{{ $user->name }}</p>
                <p class="text-sm opacity-90 mt-2">Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <!-- Account Stats with Modern Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-8">
            <!-- Total Orders -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-full bg-blue-100 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 transition-colors duration-300">Total</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300" data-counter="{{ $stats['total_orders'] }}">0</p>
                    <p class="text-xs sm:text-sm text-gray-600 group-hover:text-white/90 mt-1 transition-colors duration-300">Orders</p>
                </div>
            </div>
            
            <!-- Active Orders -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5 text-yellow-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 transition-colors duration-300">Active</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300" data-counter="{{ $stats['pending_orders'] }}">0</p>
                    <p class="text-xs sm:text-sm text-gray-600 group-hover:text-white/90 mt-1 transition-colors duration-300">Processing</p>
                </div>
            </div>
            
            <!-- Completed Orders -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-full bg-green-100 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5 text-green-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 transition-colors duration-300">Done</span>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300" data-counter="{{ $stats['completed_orders'] }}">0</p>
                    <p class="text-xs sm:text-sm text-gray-600 group-hover:text-white/90 mt-1 transition-colors duration-300">Completed</p>
                </div>
            </div>
            
            <!-- Total Spent -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 rounded-full bg-purple-100 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-300">
                            <svg class="w-5 h-5 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-500 group-hover:text-white/80 transition-colors duration-300">Total</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300">
                        <span class="text-sm">LKR</span> <span data-counter="{{ $stats['total_spent'] }}">0</span>
                    </p>
                    <p class="text-xs sm:text-sm text-gray-600 group-hover:text-white/90 mt-1 transition-colors duration-300">Spent</p>
                </div>
            </div>
        </div>

        <!-- Additional Stats Row for Mobile -->
        <div class="grid grid-cols-2 gap-3 mb-8 lg:hidden">
            <!-- Wishlist -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['wishlist_count'] }}</p>
                        <p class="text-xs text-gray-600">Wishlist Items</p>
                    </div>
                </div>
            </div>
            
            <!-- Addresses -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $stats['addresses_count'] }}</p>
                        <p class="text-xs text-gray-600">Saved Addresses</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links Grid - Desktop Only -->
        <div class="hidden lg:grid lg:grid-cols-3 gap-6 mb-8">
            <!-- Orders -->
            <a href="{{ route('user.orders.index') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">My Orders</h3>
                            <p class="text-sm text-gray-600 mt-1">Track & manage your orders</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Profile -->
            <a href="{{ route('user.account.profile') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Profile</h3>
                            <p class="text-sm text-gray-600 mt-1">Update personal information</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Addresses -->
            <a href="{{ route('user.account.addresses') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Addresses</h3>
                            <p class="text-sm text-gray-600 mt-1">Manage delivery locations</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Wishlist -->
            <a href="{{ route('wishlist.index') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Wishlist</h3>
                            <p class="text-sm text-gray-600 mt-1">Your saved items</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Reviews -->
            <a href="{{ route('user.reviews.index') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors">Reviews</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $stats['reviews_count'] }} product reviews</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Complaints -->
            <a href="{{ route('user.complaints.index') }}" class="group">
                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500/10 to-transparent rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative flex items-center">
                        <div class="bg-gradient-to-br from-orange-100 to-orange-200 rounded-2xl p-4 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Complaints</h3>
                            <p class="text-sm text-gray-600 mt-1">Manage your feedback</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mobile Quick Actions -->
        <div class="lg:hidden grid grid-cols-2 gap-3 mb-8">
            <a href="{{ route('user.orders.index') }}" class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-all duration-300 flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-900">My Orders</span>
            </a>
            
            <a href="{{ route('user.account.profile') }}" class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-all duration-300 flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-900">Profile</span>
            </a>
            
            <a href="{{ route('user.account.addresses') }}" class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-all duration-300 flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-900">Addresses</span>
            </a>
            
            <a href="{{ route('wishlist.index') }}" class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition-all duration-300 flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="font-medium text-gray-900">Wishlist</span>
            </a>
        </div>

        <!-- Recent Orders Section -->
        @if($recentOrders->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('user.orders.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center space-x-1">
                        <span>View All</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($recentOrders as $index => $order)
                <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200" style="animation: fadeInUp 0.5s ease-out {{ $index * 0.1 }}s backwards;">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between space-y-4 sm:space-y-0">
                        <!-- Order Info -->
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 mb-2">
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Order #{{ $order->order_number }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'shipping') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                Placed on {{ $order->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 mt-1">
                                Total: LKR {{ number_format($order->total_amount, 2) }}
                            </p>
                        </div>
                        
                        <!-- Order Items Preview & Actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Product Images -->
                            <div class="flex -space-x-2">
                                @foreach($order->items->take(3) as $item)
                                    @if($item->product && $item->product->main_image)
                                    <img src="{{ Storage::url($item->product->main_image) }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white object-cover shadow-sm">
                                    @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center shadow-sm">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 border-white bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center shadow-sm">
                                    <span class="text-xs text-gray-600 font-medium">+{{ $order->items->count() - 3 }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <!-- View Details Button -->
                            <a href="{{ route('user.orders.show', $order) }}" 
                               class="btn btn-sm btn-primary whitespace-nowrap">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-8 sm:p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}"
               class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Counter animation */
    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    [data-counter] {
        animation: countUp 0.5s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const mobileBackdrop = document.getElementById('mobileBackdrop');
    const drawerContent = document.getElementById('drawerContent');
    const closeDrawer = document.getElementById('closeDrawer');
    
    function openDrawer() {
        mobileDrawer.classList.remove('hidden');
        setTimeout(() => {
            drawerContent.classList.remove('translate-x-full');
        }, 10);
    }
    
    function closeDrawerFunc() {
        drawerContent.classList.add('translate-x-full');
        setTimeout(() => {
            mobileDrawer.classList.add('hidden');
        }, 300);
    }
    
    mobileMenuToggle?.addEventListener('click', openDrawer);
    closeDrawer?.addEventListener('click', closeDrawerFunc);
    mobileBackdrop?.addEventListener('click', closeDrawerFunc);
    
    // Counter Animation
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-counter'));
        const duration = 1000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString();
        }, 16);
    }
    
    // Observe counters
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.animated) {
                entry.target.animated = true;
                animateCounter(entry.target);
            }
        });
    });
    
    document.querySelectorAll('[data-counter]').forEach(el => {
        observer.observe(el);
    });
    
    // Add touch feedback for mobile
    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('touchstart', function() {
            this.style.opacity = '0.7';
        });
        el.addEventListener('touchend', function() {
            this.style.opacity = '';
        });
    });
</script>
@endpush
@endsection