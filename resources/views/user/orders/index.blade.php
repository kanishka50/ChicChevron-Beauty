<!-- ORDERS LIST PAGE -->
@extends('layouts.app')

@section('title', 'My Orders - ChicChevron Beauty')

@php
    $user = Auth::user();
    $pendingOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'payment_completed', 'processing', 'shipping'])
        ->count();
    $addressesCount = $user->addresses()->count();
    $wishlistCount = $user->wishlists()->count();
@endphp

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header with Menu Toggle -->
    <div class="lg:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="flex items-center justify-between px-4 py-3">
            <a href="{{ route('user.account.index') }}" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">My Orders</h1>
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
                    <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-plum-700 bg-plum-50 border-r-4 border-plum-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="font-medium">My Orders</span>
                        @if($pendingOrdersCount > 0)
                            <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $pendingOrdersCount }}</span>
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
                        <span class="ml-auto text-xs text-gray-400">{{ $addressesCount }}</span>
                    </a>

                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-5 py-3.5 text-gray-600 hover:bg-plum-50 hover:text-plum-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="font-medium">Wishlist</span>
                        @if($wishlistCount > 0)
                            <span class="ml-auto text-xs text-gray-400">{{ $wishlistCount }}</span>
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
                        <a href="{{ route('user.account.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('user.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-plum-700 bg-plum-50 rounded-xl font-medium transition-all">
                            <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>My Orders</span>
                            @if($pendingOrdersCount > 0)
                                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $pendingOrdersCount }}</span>
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
                            <span class="ml-auto text-xs text-gray-400">{{ $addressesCount }}</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 hover:bg-plum-50 hover:text-plum-700 rounded-xl transition-all group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span>Wishlist</span>
                            @if($wishlistCount > 0)
                                <span class="ml-auto text-xs text-gray-400">{{ $wishlistCount }}</span>
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
                <!-- Page Header -->
                <div class="hidden lg:flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                        <p class="mt-1 text-sm text-gray-500">Track and manage your orders</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Continue Shopping
                    </a>
                </div>

                <!-- Status Filter Tabs -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-6 overflow-hidden">
                    <div class="border-b border-gray-100 overflow-x-auto scrollbar-hide">
                        <nav class="-mb-px flex space-x-1 px-4 min-w-max">
                            <a href="{{ route('user.orders.index') }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ !request('status') || request('status') === 'all' ? 'border-plum-600 text-plum-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                All
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ !request('status') || request('status') === 'all' ? 'bg-plum-100 text-plum-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['all'] }}
                                </span>
                            </a>

                            <a href="{{ route('user.orders.index', ['status' => 'payment_completed']) }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ request('status') === 'payment_completed' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Confirmed
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'payment_completed' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['payment_completed'] }}
                                </span>
                            </a>

                            <a href="{{ route('user.orders.index', ['status' => 'processing']) }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ request('status') === 'processing' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Processing
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'processing' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['processing'] }}
                                </span>
                            </a>

                            <a href="{{ route('user.orders.index', ['status' => 'shipping']) }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ request('status') === 'shipping' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Shipping
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'shipping' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['shipping'] }}
                                </span>
                            </a>

                            <a href="{{ route('user.orders.index', ['status' => 'completed']) }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ request('status') === 'completed' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Completed
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'completed' ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['completed'] }}
                                </span>
                            </a>

                            <a href="{{ route('user.orders.index', ['status' => 'cancelled']) }}"
                               class="py-3 px-4 border-b-2 font-medium text-sm whitespace-nowrap transition-all {{ request('status') === 'cancelled' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Cancelled
                                <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium rounded-full {{ request('status') === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusCounts['cancelled'] }}
                                </span>
                            </a>
                        </nav>
                    </div>

                    <!-- Search Bar -->
                    <div class="p-4">
                        <form method="GET" action="{{ route('user.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <div class="flex-1 relative">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by order number..."
                                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors">
                                    Search
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('user.orders.index', ['status' => request('status')]) }}"
                                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="space-y-4">
                    @forelse($orders as $index => $order)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all duration-300"
                             style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s backwards;">
                            <!-- Order Header -->
                            <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">
                                                Order #{{ $order->order_number }}
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                            </p>
                                        </div>
                                        <x-order-status-badge :status="$order->status" />
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <div class="text-base font-bold text-gray-900">
                                            LKR {{ number_format($order->total_amount, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="px-5 py-4">
                                <div class="flex items-center gap-3 overflow-x-auto scrollbar-hide pb-2">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="flex items-center gap-3 flex-shrink-0">
                                            <div class="relative">
                                                <img src="{{ Storage::url($item->product->main_image) }}"
                                                     alt="{{ $item->product_name }}"
                                                     class="w-14 h-14 rounded-lg object-cover">
                                                @if($item->quantity > 1)
                                                    <span class="absolute -top-1.5 -right-1.5 bg-gray-800 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-medium">
                                                        {{ $item->quantity }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 max-w-[140px]">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $item->product_name }}
                                                </p>
                                                @if($item->variant_details)
                                                    @php $variantDetails = json_decode($item->variant_details, true); @endphp
                                                    <div class="flex flex-wrap gap-1 mt-1">
                                                        @if(is_array($variantDetails))
                                                            @foreach($variantDetails as $key => $value)
                                                                @if($value)
                                                                    <span class="inline-block bg-gray-100 text-gray-600 text-[10px] px-1.5 py-0.5 rounded">
                                                                        {{ ucfirst($key) }}: {{ $value }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <div class="flex-shrink-0 w-14 h-14 bg-plum-50 rounded-lg flex items-center justify-center">
                                            <span class="text-xs text-plum-600 font-semibold">
                                                +{{ $order->items->count() - 4 }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Order Actions -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 mt-4 border-t border-gray-100 gap-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="{{ route('user.orders.show', $order) }}"
                                           class="text-plum-600 hover:text-plum-700 font-medium text-sm inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>

                                        @if($order->status !== 'cancelled')
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('user.orders.invoice', $order) }}"
                                               class="text-emerald-600 hover:text-emerald-700 font-medium text-sm inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                                </svg>
                                                Invoice
                                            </a>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        @if($order->can_be_cancelled && in_array($order->status, ['payment_completed', 'processing']))
                                            <button onclick="requestCancellation({{ $order->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50 rounded-lg transition-colors">
                                                Cancel Order
                                            </button>
                                        @endif

                                        @if($order->status === 'shipping')
                                            <button onclick="markAsCompleted({{ $order->id }})"
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-plum-700 hover:bg-plum-800 rounded-lg transition-colors">
                                                Mark as Received
                                            </button>
                                        @endif

                                        <button onclick="trackOrder({{ $order->id }})"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-plum-700 hover:bg-plum-800 rounded-lg transition-colors inline-flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                            </svg>
                                            Track
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 sm:p-12 text-center">
                            <div class="mx-auto w-20 h-20 bg-plum-100 rounded-full flex items-center justify-center mb-5">
                                <svg class="w-10 h-10 text-plum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders found</h3>
                            <p class="text-sm text-gray-500 mb-6 max-w-xs mx-auto">
                                @if(request('search'))
                                    No orders match your search criteria.
                                @else
                                    You haven't placed any orders yet. Start shopping to see your orders here!
                                @endif
                            </p>
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center px-6 py-3 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Start Shopping
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<!-- Order Tracking Modal -->
<div id="trackingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-5 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Order Tracking</h3>
                    <button onclick="closeTrackingModal()" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="trackingContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancellationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-5 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Order Cancellation</h3>
                <form id="cancellationForm" onsubmit="submitCancellation(event)">
                    <input type="hidden" id="cancellationOrderId" name="order_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Reason for Cancellation</label>
                        <textarea id="cancellationReason"
                                  name="reason"
                                  rows="4"
                                  placeholder="Please tell us why you want to cancel this order..."
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent"
                                  required></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="closeCancellationModal()"
                                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white p-6 rounded-2xl shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 border-3 border-gray-200 border-t-plum-600 rounded-full animate-spin"></div>
            <span class="text-gray-700 font-medium">Processing...</span>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

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

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .toast-notification {
        animation: slideInRight 0.3s ease-out;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
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

// Toast notification
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');

    const bgColor = type === 'success' ? 'bg-emerald-500' : 'bg-red-500';
    const icon = type === 'success'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';

    toast.className = `toast-notification flex items-center gap-3 ${bgColor} text-white px-5 py-3 rounded-xl shadow-lg max-w-md`;
    toast.innerHTML = `
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icon}
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        toast.style.transition = 'all 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function trackOrder(orderId) {
    showLoading();

    fetch(`/orders/${orderId}/track`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showTrackingModal(data, orderId);
            } else {
                showToast('Error loading tracking information', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Something went wrong. Please try again.', 'error');
        });
}

function showTrackingModal(trackingData, orderId) {
    const content = `
        <div class="mb-6">
            <div class="bg-gradient-to-r from-plum-50 to-plum-100/50 p-4 rounded-xl mb-4">
                <h4 class="font-semibold text-plum-900">Order #${trackingData.order_number}</h4>
                <p class="text-plum-700 text-sm mt-1">Status: ${trackingData.current_status_label}</p>
                <p class="text-plum-600 text-sm">Estimated Delivery: ${trackingData.estimated_delivery}</p>
            </div>
        </div>

        <div class="space-y-4">
            <h5 class="font-medium text-gray-900">Order Timeline</h5>
            <div class="relative">
                ${trackingData.status_history.map((status, index) => `
                    <div class="flex items-start gap-3 ${!status.is_current ? 'opacity-60' : ''}">
                        <div class="relative flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full ${status.is_current ? 'bg-plum-100' : 'bg-gray-100'} flex items-center justify-center">
                                ${status.is_current
                                    ? '<svg class="w-5 h-5 text-plum-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>'
                                    : '<div class="w-3 h-3 rounded-full bg-gray-400"></div>'
                                }
                            </div>
                            ${index < trackingData.status_history.length - 1 ? '<div class="absolute top-10 left-5 w-0.5 h-16 bg-gray-200"></div>' : ''}
                        </div>
                        <div class="flex-1 pb-8">
                            <p class="font-medium ${status.is_current ? 'text-plum-900' : 'text-gray-900'}">${status.status_label}</p>
                            ${status.comment ? `<p class="text-sm text-gray-600 mt-1">${status.comment}</p>` : ''}
                            <p class="text-xs text-gray-500 mt-1">${status.date}</p>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-3">
            ${trackingData.can_complete ? `
                <button onclick="markAsCompleted(${trackingData.order_id || orderId})"
                        class="px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors">
                    Mark as Received
                </button>
            ` : ''}
            ${trackingData.can_cancel ? `
                <button onclick="requestCancellation(${orderId})"
                        class="px-5 py-2.5 border border-red-200 text-red-600 hover:bg-red-50 text-sm font-semibold rounded-xl transition-colors">
                    Request Cancellation
                </button>
            ` : ''}
        </div>
    `;

    document.getElementById('trackingContent').innerHTML = content;
    document.getElementById('trackingModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTrackingModal() {
    document.getElementById('trackingModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function requestCancellation(orderId) {
    document.getElementById('cancellationOrderId').value = orderId;
    document.getElementById('cancellationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    closeTrackingModal();
}

function closeCancellationModal() {
    document.getElementById('cancellationModal').classList.add('hidden');
    document.getElementById('cancellationForm').reset();
    document.body.style.overflow = '';
}

function submitCancellation(event) {
    event.preventDefault();

    const orderId = document.getElementById('cancellationOrderId').value;
    const reason = document.getElementById('cancellationReason').value;

    showLoading();

    fetch(`/orders/${orderId}/request-cancellation`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        closeCancellationModal();

        if (data.success) {
            showToast(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Error processing request', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        closeCancellationModal();
        showToast('Something went wrong. Please try again.', 'error');
    });
}

function markAsCompleted(orderId) {
    if (confirm('Are you sure you want to mark this order as completed? This confirms that you have received all items.')) {
        showLoading();

        fetch(`/orders/${orderId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            closeTrackingModal();

            if (data.success) {
                showToast(data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Error processing request', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            showToast('Something went wrong. Please try again.', 'error');
        });
    }
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
    document.getElementById('loadingOverlay').classList.remove('flex');
}

// Close modals when clicking outside
document.getElementById('trackingModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('bg-gray-900')) {
        closeTrackingModal();
    }
});

document.getElementById('cancellationModal').addEventListener('click', function(e) {
    if (e.target === this || e.target.classList.contains('bg-gray-900')) {
        closeCancellationModal();
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTrackingModal();
        closeCancellationModal();
        closeDrawerFunc();
    }
});
</script>
@endpush
@endsection
