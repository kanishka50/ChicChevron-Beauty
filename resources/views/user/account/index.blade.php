@extends('layouts.app')

@section('title', 'My Account - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header with Menu Toggle -->
    <div class="lg:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="flex items-center justify-between px-4 py-3">
            <h1 class="text-xl font-bold text-gray-900">My Account</h1>
            <button id="mobileMenuToggle" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="bg-gradient-to-r from-plum-700 to-plum-800 px-6 py-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Account Menu</h2>
                        <button id="closeDrawer" class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-lg font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $user->name }}</p>
                            <p class="text-sm text-plum-200">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 overflow-y-auto py-2">
                    <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-plum-700 bg-plum-50 border-r-4 border-plum-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="font-medium">My Orders</span>
                        @if($stats['pending_orders'] > 0)
                            <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $stats['pending_orders'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('user.account.profile') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Profile</span>
                    </a>

                    <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">Addresses</span>
                        <span class="ml-auto text-xs text-gray-400">{{ $stats['addresses_count'] }}</span>
                    </a>

                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="font-medium">Wishlist</span>
                        @if($stats['wishlist_count'] > 0)
                            <span class="ml-auto text-xs text-gray-400">{{ $stats['wishlist_count'] }}</span>
                        @endif
                    </a>

                    <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <span class="font-medium">Complaints</span>
                    </a>
                </nav>

                <!-- Drawer Footer -->
                <div class="border-t border-gray-100 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout: Sidebar + Content -->
    <div class="container-responsive py-6 lg:py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Desktop Sidebar -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden sticky top-6 shadow-sm">
                    <!-- User Profile Header -->
                    <div class="bg-gradient-to-br from-plum-600 via-plum-700 to-plum-800 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-xl font-bold ring-2 ring-white/30">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-lg truncate">{{ $user->name }}</p>
                                <p class="text-sm text-plum-200 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-plum-300 mt-4">Member since {{ $user->created_at->format('F Y') }}</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="p-3">
                        <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 text-plum-700 bg-plum-50 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>My Orders</span>
                            @if($stats['pending_orders'] > 0)
                                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $stats['pending_orders'] }}</span>
                            @endif
                        </a>

                        <a href="{{ route('user.account.profile') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Profile</span>
                        </a>

                        <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Addresses</span>
                            <span class="ml-auto text-xs text-gray-400">{{ $stats['addresses_count'] }}</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Wishlist</span>
                            @if($stats['wishlist_count'] > 0)
                                <span class="ml-auto text-xs text-gray-400">{{ $stats['wishlist_count'] }}</span>
                            @endif
                        </a>

                        <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <span>Complaints</span>
                        </a>

                        <div class="border-t border-gray-100 mt-3 pt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <!-- Mobile: Single Card Dashboard Design -->
                <div class="lg:hidden mb-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- User Header with Gradient -->
                        <div class="bg-gradient-to-br from-plum-600 via-plum-700 to-plum-800 px-5 py-6 text-white relative overflow-hidden">
                            <!-- Decorative circles -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full"></div>
                            <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/5 rounded-full"></div>

                            <div class="relative">
                                <p class="text-sm text-plum-200 font-medium">Welcome back!</p>
                                <h2 class="text-xl font-bold mt-1">{{ $user->name }}</h2>

                                <!-- Total Spent - Featured -->
                                <div class="mt-5 pt-5 border-t border-white/20">
                                    <p class="text-xs text-plum-300 uppercase tracking-wide">Total Spent</p>
                                    <p class="text-2xl font-bold mt-1">
                                        <span class="text-lg text-plum-200">LKR</span>
                                        <span data-counter="{{ $stats['total_spent'] }}">0</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Row - Horizontal Scrollable -->
                        <div class="px-4 py-4 border-b border-gray-100">
                            <div class="flex gap-3">
                                <!-- Total Orders -->
                                <div class="flex-1 bg-gray-50 rounded-xl p-3 text-center min-w-0">
                                    <div class="w-8 h-8 mx-auto rounded-lg bg-plum-100 flex items-center justify-center mb-2">
                                        <svg class="w-4 h-4 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900" data-counter="{{ $stats['total_orders'] }}">0</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Orders</p>
                                </div>

                                <!-- Active Orders -->
                                <div class="flex-1 bg-amber-50 rounded-xl p-3 text-center min-w-0">
                                    <div class="w-8 h-8 mx-auto rounded-lg bg-amber-100 flex items-center justify-center mb-2">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900" data-counter="{{ $stats['pending_orders'] }}">0</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Active</p>
                                </div>

                                <!-- Completed -->
                                <div class="flex-1 bg-emerald-50 rounded-xl p-3 text-center min-w-0">
                                    <div class="w-8 h-8 mx-auto rounded-lg bg-emerald-100 flex items-center justify-center mb-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900" data-counter="{{ $stats['completed_orders'] }}">0</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Done</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions - Clean List Style -->
                        <div class="divide-y divide-gray-50">
                            <a href="{{ route('user.orders.index') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-plum-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center group-hover:bg-plum-200 transition-colors">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">My Orders</p>
                                    <p class="text-xs text-gray-500">Track & manage orders</p>
                                </div>
                                @if($stats['pending_orders'] > 0)
                                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $stats['pending_orders'] }}</span>
                                @endif
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <a href="{{ route('user.account.profile') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-plum-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center group-hover:bg-plum-200 transition-colors">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Profile Settings</p>
                                    <p class="text-xs text-gray-500">Update your information</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-plum-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center group-hover:bg-plum-200 transition-colors">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Saved Addresses</p>
                                    <p class="text-xs text-gray-500">Manage delivery locations</p>
                                </div>
                                @if($stats['addresses_count'] > 0)
                                    <span class="text-xs text-gray-400 font-medium">{{ $stats['addresses_count'] }}</span>
                                @endif
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-plum-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center group-hover:bg-plum-200 transition-colors">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Wishlist</p>
                                    <p class="text-xs text-gray-500">Items you've saved</p>
                                </div>
                                @if($stats['wishlist_count'] > 0)
                                    <span class="bg-rose-100 text-rose-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $stats['wishlist_count'] }}</span>
                                @endif
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-plum-50/50 transition-colors group">
                                <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center group-hover:bg-plum-200 transition-colors">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">Help & Support</p>
                                    <p class="text-xs text-gray-500">Contact us or raise a complaint</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Desktop Stats Grid -->
                <div class="hidden lg:grid grid-cols-4 gap-4 mb-8">
                    <!-- Total Orders -->
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-plum-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900" data-counter="{{ $stats['total_orders'] }}">0</p>
                        <p class="text-xs text-gray-500 mt-1">Total Orders</p>
                    </div>

                    <!-- Active Orders -->
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900" data-counter="{{ $stats['pending_orders'] }}">0</p>
                        <p class="text-xs text-gray-500 mt-1">Active Orders</p>
                    </div>

                    <!-- Completed Orders -->
                    <div class="bg-white rounded-xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-2xl font-bold text-gray-900" data-counter="{{ $stats['completed_orders'] }}">0</p>
                        <p class="text-xs text-gray-500 mt-1">Completed</p>
                    </div>

                    <!-- Total Spent -->
                    <div class="bg-gradient-to-br from-plum-600 to-plum-700 rounded-xl p-5 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xl font-bold">
                            <span class="text-sm text-plum-200">LKR</span> <span data-counter="{{ $stats['total_spent'] }}">0</span>
                        </p>
                        <p class="text-xs text-plum-200 mt-1">Total Spent</p>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                @if($recentOrders->count() > 0)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                        <a href="{{ route('user.orders.index') }}" class="text-sm text-plum-600 hover:text-plum-700 font-medium flex items-center gap-1">
                            <span>View All</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($recentOrders as $index => $order)
                        <div class="p-4 sm:p-5 hover:bg-gray-50/50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Order Info -->
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <h3 class="text-sm font-semibold text-gray-900">
                                            Order #{{ $order->order_number }}
                                        </h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium
                                            @if($order->status === 'completed') bg-emerald-50 text-emerald-700
                                            @elseif($order->status === 'shipping') bg-blue-50 text-blue-700
                                            @elseif($order->status === 'processing') bg-amber-50 text-amber-700
                                            @elseif($order->status === 'cancelled') bg-red-50 text-red-600
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="font-medium text-gray-900">LKR {{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Order Items Preview & Actions -->
                                <div class="flex items-center gap-4">
                                    <!-- Product Images -->
                                    <div class="flex -space-x-2">
                                        @foreach($order->items->take(3) as $item)
                                            @if($item->product && $item->product->main_image)
                                            <img src="{{ Storage::url($item->product->main_image) }}"
                                                 alt="{{ $item->product_name }}"
                                                 class="w-9 h-9 rounded-lg border-2 border-white object-cover shadow-sm">
                                            @else
                                            <div class="w-9 h-9 rounded-lg border-2 border-white bg-gray-100 flex items-center justify-center shadow-sm">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            @endif
                                        @endforeach
                                        @if($order->items->count() > 3)
                                        <div class="w-9 h-9 rounded-lg border-2 border-white bg-plum-50 flex items-center justify-center shadow-sm">
                                            <span class="text-[10px] text-plum-600 font-semibold">+{{ $order->items->count() - 3 }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- View Details Button -->
                                    <a href="{{ route('user.orders.show', $order) }}"
                                       class="px-4 py-2 text-xs font-medium text-plum-700 bg-plum-50 hover:bg-plum-100 rounded-lg transition-colors whitespace-nowrap">
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
                <div class="bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center">
                    <div class="mx-auto w-20 h-20 bg-plum-100 rounded-full flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-plum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders yet</h3>
                    <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">Start shopping to see your orders here!</p>
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Start Shopping
                    </a>
                </div>
                @endif
            </main>
        </div>
    </div>
</div>

@push('styles')
<style>
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
</script>
@endpush
@endsection
