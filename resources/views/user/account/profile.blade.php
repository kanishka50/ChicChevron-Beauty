@extends('layouts.app')

@section('title', 'Profile Settings - ChicChevron Beauty')

@php
    $pendingOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'payment_completed', 'processing', 'shipping'])
        ->count();
    $addressesCount = $user->addresses()->count();
    $wishlistCount = $user->wishlists()->count();
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-plum-50/30 to-gray-50">
    <div class="container-responsive py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden sticky top-0 z-40 -mx-4 px-4 py-3 bg-white/95 backdrop-blur-md border-b border-gray-100 mb-6 flex items-center justify-between">
            <a href="{{ route('user.account.index') }}" class="p-2 -ml-2 text-gray-600 hover:text-plum-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Profile Settings</h1>
            <button type="button" id="mobileMenuBtn" class="p-2 -mr-2 text-gray-600 hover:text-plum-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Drawer Overlay -->
        <div id="drawerOverlay" class="fixed inset-0 bg-black/50 z-50 hidden lg:hidden opacity-0 transition-opacity duration-300"></div>

        <!-- Mobile Drawer -->
        <div id="mobileDrawer" class="fixed top-0 right-0 h-full w-80 bg-white z-50 transform translate-x-full transition-transform duration-300 ease-out lg:hidden shadow-2xl">
            <div class="flex flex-col h-full">
                <!-- Drawer Header -->
                <div class="bg-gradient-to-br from-plum-600 via-plum-700 to-plum-800 px-6 py-8 text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/5 rounded-full"></div>
                    <button type="button" id="closeDrawerBtn" class="absolute top-4 right-4 p-2 text-white/80 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="relative">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm">
                            <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <h2 class="text-lg font-bold">{{ $user->name }}</h2>
                        <p class="text-sm text-plum-200 mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Drawer Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">
                    <a href="{{ route('user.account.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('user.orders.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">My Orders</span>
                        @if($pendingOrdersCount > 0)
                            <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.account.profile') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                        <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Profile Settings</span>
                    </a>
                    <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Addresses</span>
                        @if($addressesCount > 0)
                            <span class="ml-auto text-xs text-gray-400 font-medium">{{ $addressesCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Wishlist</span>
                        @if($wishlistCount > 0)
                            <span class="ml-auto bg-rose-100 text-rose-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Help & Support</span>
                    </a>
                </nav>

                <!-- Drawer Footer -->
                <div class="border-t border-gray-100 p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="font-medium">Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex gap-8">
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
                    <!-- User Header -->
                    <div class="bg-gradient-to-br from-plum-600 via-plum-700 to-plum-800 px-6 py-8 text-white relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/5 rounded-full"></div>
                        <div class="relative">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm">
                                <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <h2 class="text-lg font-bold">{{ $user->name }}</h2>
                            <p class="text-sm text-plum-200 mt-1">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="py-4">
                        <a href="{{ route('user.account.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="{{ route('user.orders.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">My Orders</span>
                            @if($pendingOrdersCount > 0)
                                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $pendingOrdersCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('user.account.profile') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                            <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Profile Settings</span>
                        </a>
                        <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Addresses</span>
                            @if($addressesCount > 0)
                                <span class="ml-auto text-xs text-gray-400 font-medium">{{ $addressesCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            </div>
                            <span class="font-medium">Wishlist</span>
                            @if($wishlistCount > 0)
                                <span class="ml-auto bg-rose-100 text-rose-600 text-xs font-bold px-2.5 py-1 rounded-full">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Help & Support</span>
                        </a>

                        <div class="border-t border-gray-100 mt-4 pt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-4 w-full px-6 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span class="font-medium">Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <!-- Desktop Breadcrumb -->
                <nav class="hidden lg:block mb-6 text-sm">
                    <ol class="flex items-center gap-2 text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-plum-600 transition-colors">Home</a></li>
                        <li>/</li>
                        <li><a href="{{ route('user.account.index') }}" class="hover:text-plum-600 transition-colors">My Account</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">Profile Settings</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="hidden lg:block mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                    <p class="text-gray-500 mt-1">Manage your personal information and security settings</p>
                </div>

                <!-- Profile Form -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <form action="{{ route('user.account.profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="m-6 mb-0 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between animate-fadeIn">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endif

                        <!-- Personal Information Section -->
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-plum-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all @error('email') border-red-300 @enderror"
                                           placeholder="you@example.com"
                                           required>
                                    @error('email')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="lg:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                                    </label>
                                    <input type="tel"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="07X XXX XXXX"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all @error('phone') border-red-300 @enderror">
                                    @error('phone')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1.5 text-xs text-gray-500">For order updates and delivery notifications</p>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="border-t border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Security Settings</h3>
                                        <p class="text-sm text-gray-500">Change your password</p>
                                    </div>
                                </div>
                                <button type="button" id="togglePasswordSection" class="text-sm text-plum-600 hover:text-plum-700 font-medium px-4 py-2 rounded-lg hover:bg-plum-50 transition-colors">
                                    <span id="passwordToggleText">Change Password</span>
                                </button>
                            </div>

                            <div id="passwordSection" class="hidden space-y-5 animate-slideDown">
                                <!-- Current Password -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current Password</label>
                                    <div class="relative">
                                        <input type="password"
                                               id="current_password"
                                               name="current_password"
                                               class="w-full px-4 py-2.5 pr-12 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all @error('current_password') border-red-300 @enderror"
                                               autocomplete="current-password"
                                               placeholder="Enter current password">
                                        <button type="button"
                                                onclick="togglePasswordVisibility('current_password')"
                                                class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="current_password_show">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <svg class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="current_password_hide">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                    <!-- New Password -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password</label>
                                        <div class="relative">
                                            <input type="password"
                                                   id="password"
                                                   name="password"
                                                   class="w-full px-4 py-2.5 pr-12 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all @error('password') border-red-300 @enderror"
                                                   autocomplete="new-password"
                                                   placeholder="Enter new password">
                                            <button type="button"
                                                    onclick="togglePasswordVisibility('password')"
                                                    class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_show">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_hide">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                        @enderror

                                        <!-- Password Strength Indicator -->
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="text-xs text-gray-500">Password Strength</span>
                                                <span class="text-xs font-medium text-gray-500" id="passwordStrengthText">-</span>
                                            </div>
                                            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div id="passwordStrengthBar" class="h-full bg-gray-300 transition-all duration-300 ease-out rounded-full" style="width: 0%"></div>
                                            </div>
                                        </div>
                                        <p class="mt-1.5 text-xs text-gray-500">Must be at least 8 characters</p>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password</label>
                                        <div class="relative">
                                            <input type="password"
                                                   id="password_confirmation"
                                                   name="password_confirmation"
                                                   class="w-full px-4 py-2.5 pr-12 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all"
                                                   autocomplete="new-password"
                                                   placeholder="Confirm new password">
                                            <button type="button"
                                                    onclick="togglePasswordVisibility('password_confirmation')"
                                                    class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_confirmation_show">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <svg class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_confirmation_hide">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div id="passwordMatch" class="hidden mt-1.5 text-sm">
                                            <span class="text-green-600 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Passwords match
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile drawer functionality
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');

    function openDrawer() {
        mobileDrawer.classList.remove('translate-x-full');
        drawerOverlay.classList.remove('hidden');
        setTimeout(() => drawerOverlay.classList.remove('opacity-0'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        mobileDrawer.classList.add('translate-x-full');
        drawerOverlay.classList.add('opacity-0');
        setTimeout(() => drawerOverlay.classList.add('hidden'), 300);
        document.body.style.overflow = '';
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openDrawer);
    }

    if (closeDrawerBtn) {
        closeDrawerBtn.addEventListener('click', closeDrawer);
    }

    if (drawerOverlay) {
        drawerOverlay.addEventListener('click', closeDrawer);
    }

    // Toggle Password Section
    const togglePasswordSection = document.getElementById('togglePasswordSection');
    if (togglePasswordSection) {
        togglePasswordSection.addEventListener('click', function() {
            const section = document.getElementById('passwordSection');
            const toggleText = document.getElementById('passwordToggleText');

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                toggleText.textContent = 'Cancel';
            } else {
                section.classList.add('hidden');
                toggleText.textContent = 'Change Password';
                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('password').value = '';
                document.getElementById('password_confirmation').value = '';
                // Reset strength indicator
                document.getElementById('passwordStrengthBar').style.width = '0%';
                document.getElementById('passwordStrengthText').textContent = '-';
                document.getElementById('passwordMatch').classList.add('hidden');
            }
        });
    }

    // Password strength checker
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
    }

    // Password confirmation checker
    const confirmInput = document.getElementById('password_confirmation');
    if (confirmInput) {
        confirmInput.addEventListener('input', checkPasswordMatch);
    }

    // Form validation
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (newPassword || confirmPassword || currentPassword) {
                if (!currentPassword) {
                    e.preventDefault();
                    showToast('Please enter your current password to change your password.', 'error');
                    return;
                }

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    showToast('New passwords do not match.', 'error');
                    return;
                }

                if (newPassword && newPassword.length < 8) {
                    e.preventDefault();
                    showToast('New password must be at least 8 characters long.', 'error');
                    return;
                }
            }
        });
    }
});

// Toggle Password Visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const showIcon = document.getElementById(fieldId + '_show');
    const hideIcon = document.getElementById(fieldId + '_hide');

    if (field.type === 'password') {
        field.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        field.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}

// Password Strength Checker
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');

    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;

    const strengthLevels = [
        { width: '0%', color: 'bg-gray-300', text: '-' },
        { width: '20%', color: 'bg-red-500', text: 'Very Weak' },
        { width: '40%', color: 'bg-orange-500', text: 'Weak' },
        { width: '60%', color: 'bg-amber-500', text: 'Fair' },
        { width: '80%', color: 'bg-plum-500', text: 'Good' },
        { width: '100%', color: 'bg-green-500', text: 'Strong' }
    ];

    const level = strengthLevels[strength];
    strengthBar.style.width = level.width;
    strengthBar.className = `h-full transition-all duration-300 ease-out rounded-full ${level.color}`;
    strengthText.textContent = level.text;
}

// Password Match Checker
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const matchIndicator = document.getElementById('passwordMatch');

    if (confirmation && password === confirmation) {
        matchIndicator.classList.remove('hidden');
    } else {
        matchIndicator.classList.add('hidden');
    }
}

// Toast notification
function showToast(message, type = 'error') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg text-white font-medium transform transition-all duration-300 translate-y-full opacity-0 ${type === 'error' ? 'bg-red-600' : 'bg-green-600'}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-full', 'opacity-0');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endpush
@endsection
