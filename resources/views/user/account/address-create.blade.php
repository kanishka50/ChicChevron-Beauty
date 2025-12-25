@extends('layouts.app')

@section('title', 'Add New Address - ChicChevron Beauty')

@php
    $user = Auth::user();
    $pendingOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'payment_completed', 'processing', 'shipping'])
        ->count();
    $addressesCount = $user->addresses()->count();
    $wishlistCount = $user->wishlists()->count();
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-plum-50/30 to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6">
            <div class="bg-white rounded-2xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('user.account.addresses') }}" class="p-2 -ml-2 text-gray-600 hover:text-plum-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Add Address</h1>
                    <button id="mobileMenuBtn" class="p-2 -mr-2 text-gray-600 hover:text-plum-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <!-- User Profile Header -->
                    <div class="bg-gradient-to-br from-plum-600 to-plum-700 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold backdrop-blur-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-lg truncate">{{ $user->name }}</h3>
                                <p class="text-plum-200 text-sm truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="p-4">
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="font-medium">My Orders</span>
                                    @if($pendingOrdersCount > 0)
                                        <span class="ml-auto bg-plum-100 text-plum-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $pendingOrdersCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="font-medium">Wishlist</span>
                                    @if($wishlistCount > 0)
                                        <span class="ml-auto bg-plum-100 text-plum-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-plum-50 text-plum-700 font-medium">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Addresses</span>
                                    @if($addressesCount > 0)
                                        <span class="ml-auto bg-plum-200 text-plum-800 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $addressesCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-medium">Help & Support</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.account.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium">Profile Settings</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Sign Out -->
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-medium">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Mobile Sidebar Drawer -->
            <div id="mobileSidebar" class="fixed inset-0 z-50 lg:hidden hidden">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="sidebarOverlay"></div>
                <div class="absolute right-0 top-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl transform transition-transform duration-300 translate-x-full" id="sidebarPanel">
                    <div class="bg-gradient-to-br from-plum-600 to-plum-700 p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold">Menu</h3>
                            <button id="closeSidebar" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-lg font-bold backdrop-blur-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium truncate">{{ $user->name }}</h4>
                                <p class="text-plum-200 text-sm truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <nav class="p-4 overflow-y-auto max-h-[calc(100vh-180px)]">
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    <span class="font-medium">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="font-medium">My Orders</span>
                                    @if($pendingOrdersCount > 0)
                                        <span class="ml-auto bg-plum-100 text-plum-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $pendingOrdersCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="font-medium">Wishlist</span>
                                    @if($wishlistCount > 0)
                                        <span class="ml-auto bg-plum-100 text-plum-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $wishlistCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-plum-50 text-plum-700 font-medium">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Addresses</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-medium">Help & Support</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.account.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium">Profile Settings</span>
                                </a>
                            </li>
                        </ul>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span class="font-medium">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <!-- Desktop Breadcrumb -->
                <nav class="hidden lg:flex items-center gap-2 text-sm text-gray-500 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-plum-600 transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('user.account.index') }}" class="hover:text-plum-600 transition-colors">My Account</a>
                    <span>/</span>
                    <a href="{{ route('user.account.addresses') }}" class="hover:text-plum-600 transition-colors">Addresses</a>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Add New</span>
                </nav>

                <!-- Page Header -->
                <div class="hidden lg:block mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Add New Address</h1>
                    <p class="text-gray-500 mt-1">Add a new delivery address to your account</p>
                </div>

                <!-- Address Form Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 sm:p-6 bg-gradient-to-r from-plum-50 to-white border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-plum-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Delivery Address Details</h2>
                                <p class="text-sm text-gray-500">Enter your delivery address information</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('user.account.addresses.store') }}" method="POST" class="p-5 sm:p-6 space-y-5">
                        @csrf

                        <!-- Address Lines -->
                        <div class="space-y-4">
                            <div class="group">
                                <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Address Line 1 <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           id="address_line_1"
                                           name="address_line_1"
                                           value="{{ old('address_line_1') }}"
                                           placeholder="House/Building No, Street Name"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-plum-500 focus:border-plum-500 transition-all @error('address_line_1') border-red-300 ring-1 ring-red-300 @enderror"
                                           required>
                                </div>
                                @error('address_line_1')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Address Line 2 <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           id="address_line_2"
                                           name="address_line_2"
                                           value="{{ old('address_line_2') }}"
                                           placeholder="Apartment, Suite, Unit, etc."
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-plum-500 focus:border-plum-500 transition-all @error('address_line_2') border-red-300 ring-1 ring-red-300 @enderror">
                                </div>
                                @error('address_line_2')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- City -->
                            <div class="group">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           id="city"
                                           name="city"
                                           value="{{ old('city') }}"
                                           placeholder="Colombo"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-plum-500 focus:border-plum-500 transition-all @error('city') border-red-300 ring-1 ring-red-300 @enderror"
                                           required>
                                </div>
                                @error('city')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- District -->
                            <div class="group">
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    District <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                    </div>
                                    <select id="district"
                                            name="district"
                                            class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-plum-500 focus:border-plum-500 transition-all appearance-none bg-white @error('district') border-red-300 ring-1 ring-red-300 @enderror"
                                            required>
                                        <option value="">Select District</option>
                                        <optgroup label="Western Province">
                                            <option value="Colombo" {{ old('district') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                                            <option value="Gampaha" {{ old('district') == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                                            <option value="Kalutara" {{ old('district') == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                                        </optgroup>
                                        <optgroup label="Central Province">
                                            <option value="Kandy" {{ old('district') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                                            <option value="Matale" {{ old('district') == 'Matale' ? 'selected' : '' }}>Matale</option>
                                            <option value="Nuwara Eliya" {{ old('district') == 'Nuwara Eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                                        </optgroup>
                                        <optgroup label="Southern Province">
                                            <option value="Galle" {{ old('district') == 'Galle' ? 'selected' : '' }}>Galle</option>
                                            <option value="Matara" {{ old('district') == 'Matara' ? 'selected' : '' }}>Matara</option>
                                            <option value="Hambantota" {{ old('district') == 'Hambantota' ? 'selected' : '' }}>Hambantota</option>
                                        </optgroup>
                                        <optgroup label="Other Districts">
                                            <option value="Jaffna" {{ old('district') == 'Jaffna' ? 'selected' : '' }}>Jaffna</option>
                                            <option value="Batticaloa" {{ old('district') == 'Batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                                            <option value="Ampara" {{ old('district') == 'Ampara' ? 'selected' : '' }}>Ampara</option>
                                            <option value="Trincomalee" {{ old('district') == 'Trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                                            <option value="Kurunegala" {{ old('district') == 'Kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                                            <option value="Anuradhapura" {{ old('district') == 'Anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                                            <option value="Ratnapura" {{ old('district') == 'Ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                                        </optgroup>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('district')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Postal Code -->
                            <div class="group sm:col-span-2 lg:col-span-1">
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Postal Code <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-plum-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <input type="text"
                                           id="postal_code"
                                           name="postal_code"
                                           value="{{ old('postal_code') }}"
                                           placeholder="10100"
                                           maxlength="5"
                                           pattern="[0-9]{5}"
                                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-plum-500 focus:border-plum-500 transition-all @error('postal_code') border-red-300 ring-1 ring-red-300 @enderror">
                                </div>
                                @error('postal_code')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Set as Default -->
                        <div class="bg-plum-50/50 rounded-xl p-4 border border-plum-100">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox"
                                       id="is_default"
                                       name="is_default"
                                       value="1"
                                       {{ old('is_default') ? 'checked' : '' }}
                                       class="w-5 h-5 mt-0.5 text-plum-600 border-gray-300 rounded focus:ring-2 focus:ring-plum-500 focus:ring-offset-2 transition-all">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">Set as default delivery address</span>
                                    <p class="text-xs text-gray-600 mt-0.5">This address will be pre-selected during checkout</p>
                                </div>
                            </label>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-5 border-t border-gray-100 gap-3">
                            <a href="{{ route('user.account.addresses') }}"
                               class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow group">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Add Address
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mobile sidebar toggle
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileSidebar = document.getElementById('mobileSidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarPanel = document.getElementById('sidebarPanel');
const closeSidebar = document.getElementById('closeSidebar');

function openSidebar() {
    mobileSidebar.classList.remove('hidden');
    setTimeout(() => {
        sidebarPanel.classList.remove('translate-x-full');
    }, 10);
}

function closeSidebarMenu() {
    sidebarPanel.classList.add('translate-x-full');
    setTimeout(() => {
        mobileSidebar.classList.add('hidden');
    }, 300);
}

mobileMenuBtn?.addEventListener('click', openSidebar);
closeSidebar?.addEventListener('click', closeSidebarMenu);
sidebarOverlay?.addEventListener('click', closeSidebarMenu);

// Postal code validation - only allow numbers
document.getElementById('postal_code')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
});
</script>
@endpush
@endsection
