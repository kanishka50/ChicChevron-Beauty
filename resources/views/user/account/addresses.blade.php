<!-- ADDRESSES LIST PAGE -->
@extends('layouts.app')

@section('title', 'My Addresses - ChicChevron Beauty')

@php
    $user = Auth::user();
    $pendingOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'payment_completed', 'processing', 'shipping'])
        ->count();
    $addressesCount = $addresses->count();
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
            <h1 class="text-lg font-bold text-gray-900">My Addresses</h1>
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
                    <a href="{{ route('user.account.profile') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Profile Settings</span>
                    </a>
                    <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                        <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Addresses</span>
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
                        <a href="{{ route('user.account.profile') }}" class="flex items-center gap-4 px-6 py-3 text-gray-700 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Profile Settings</span>
                        </a>
                        <a href="{{ route('user.account.addresses') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                            <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Addresses</span>
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
                        <li class="text-gray-900 font-medium">Addresses</li>
                    </ol>
                </nav>

                <!-- Page Header with Add Button -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
                        <p class="text-gray-500 mt-1">Manage your delivery addresses</p>
                    </div>
                    <a href="{{ route('user.account.addresses.create') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Address
                    </a>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between animate-fadeIn">
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

                @if($addresses->isEmpty())
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-plum-100 to-plum-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No addresses saved</h3>
                        <p class="text-gray-500 mb-6">Add your first delivery address to make checkout faster.</p>
                        <a href="{{ route('user.account.addresses.create') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Your First Address
                        </a>
                    </div>
                @else
                    <!-- Address Grid -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @foreach($addresses as $index => $address)
                            <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-plum-200 transition-all duration-300 overflow-hidden {{ $address->is_default ? 'ring-2 ring-plum-500' : '' }}"
                                 style="animation: fadeInUp 0.4s ease-out {{ $index * 0.1 }}s backwards;">

                                <!-- Default Badge -->
                                @if($address->is_default)
                                    <div class="absolute top-0 right-0 bg-gradient-to-r from-plum-600 to-plum-700 text-white px-4 py-1.5 rounded-bl-xl">
                                        <span class="text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Default
                                        </span>
                                    </div>
                                @endif

                                <div class="p-5">
                                    <!-- Address Header -->
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-plum-100 to-plum-200 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                            <svg class="w-6 h-6 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 font-medium">{{ $address->address_line_1 }}</p>
                                            @if($address->address_line_2)
                                                <p class="text-sm text-gray-600 mt-0.5">{{ $address->address_line_2 }}</p>
                                            @endif
                                            <p class="text-sm text-gray-600 mt-0.5">{{ $address->city }}, {{ $address->district }}</p>
                                            @if($address->postal_code)
                                                <p class="text-sm text-gray-500 mt-0.5">{{ $address->postal_code }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('user.account.addresses.edit', $address) }}"
                                               class="text-sm text-plum-600 hover:text-plum-700 font-medium flex items-center gap-1.5 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>

                                            @if(!$address->is_default)
                                                <form action="{{ route('user.account.addresses.delete', $address) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this address?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-1.5 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        @if(!$address->is_default)
                                            <form action="{{ route('user.account.addresses.default', $address) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs text-gray-500 hover:text-plum-600 font-medium px-3 py-1.5 rounded-lg hover:bg-plum-50 transition-colors">
                                                    Set as default
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </main>
        </div>
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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
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
});
</script>
@endpush
@endsection
