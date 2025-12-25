@extends('layouts.app')

@section('title', 'My Wishlist - ChicChevron Beauty')

@php
    $user = Auth::user();
    $pendingOrdersCount = \App\Models\Order::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'payment_completed', 'processing', 'shipping'])
        ->count();
    $addressesCount = $user->addresses()->count();
    $wishlistCount = $wishlistItems->count();
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
            <h1 class="text-lg font-bold text-gray-900">My Wishlist</h1>
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
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                        <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">Wishlist</span>
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
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                            <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Wishlist</span>
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
                        <li class="text-gray-900 font-medium">Wishlist</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
                        <p class="text-gray-500 mt-1">{{ $wishlistItems->count() }} {{ Str::plural('item', $wishlistItems->count()) }} saved</p>
                    </div>
                    @if($wishlistItems->isNotEmpty())
                        <button onclick="clearWishlist()"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 text-gray-600 font-medium rounded-xl hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition-colors group">
                            <svg class="w-5 h-5 mr-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Clear All
                        </button>
                    @endif
                </div>

                @if($wishlistItems->isNotEmpty())
                    <!-- Wishlist Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($wishlistItems as $index => $item)
                            <div class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-plum-200 transition-all duration-300 overflow-hidden"
                                 style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s backwards;">

                                <!-- Product Image -->
                                <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="block h-full">
                                        <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : '/placeholder.jpg' }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                             loading="lazy">
                                    </a>

                                    <!-- Discount Badge -->
                                    @if($item->product->discount_price && $item->product->discount_price < $item->product->selling_price)
                                        @php
                                            $discountPercentage = round((($item->product->selling_price - $item->product->discount_price) / $item->product->selling_price) * 100);
                                        @endphp
                                        <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                                            -{{ $discountPercentage }}%
                                        </div>
                                    @endif

                                    <!-- Quick Actions -->
                                    <div class="absolute top-2 right-2 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <!-- Remove Button -->
                                        <button onclick="removeFromWishlist({{ $item->product->id }})"
                                                class="w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-red-50 hover:text-red-600 transition-all duration-200 flex items-center justify-center"
                                                title="Remove from wishlist">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <!-- Quick View -->
                                        <a href="{{ route('products.show', $item->product->slug) }}"
                                           class="w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-plum-50 hover:text-plum-600 transition-all duration-200 flex items-center justify-center"
                                           title="View product">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    </div>

                                    <!-- Out of Stock Overlay -->
                                    @if(!$item->product->hasStock())
                                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center">
                                            <span class="bg-white/90 text-gray-900 px-3 py-1 rounded-full text-sm font-medium">
                                                Out of Stock
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <!-- Brand -->
                                    @if($item->product->brand)
                                        <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">{{ $item->product->brand->name }}</p>
                                    @endif

                                    <!-- Product Name -->
                                    <h3 class="mt-1 text-sm font-medium text-gray-900 line-clamp-2 min-h-[2.5rem]">
                                        <a href="{{ route('products.show', $item->product->slug) }}"
                                           class="hover:text-plum-600 transition-colors">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>

                                    <!-- Price -->
                                    <div class="mt-2 flex items-center gap-2">
                                        @if($item->product->discount_price && $item->product->discount_price < $item->product->selling_price)
                                            <span class="text-base font-bold text-plum-600">Rs {{ number_format($item->product->discount_price, 0) }}</span>
                                            <span class="text-xs text-gray-400 line-through">Rs {{ number_format($item->product->selling_price, 0) }}</span>
                                        @else
                                            <span class="text-base font-bold text-gray-900">Rs {{ number_format($item->product->selling_price, 0) }}</span>
                                        @endif
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <div class="mt-3">
                                        @if($item->product->hasStock())
                                            <button onclick="addToCart({{ $item->product->id }})"
                                                    class="w-full py-2 px-3 bg-plum-700 hover:bg-plum-800 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Add to Cart
                                            </button>
                                        @else
                                            <button disabled
                                                    class="w-full py-2 px-3 bg-gray-100 text-gray-400 text-sm font-medium rounded-xl cursor-not-allowed">
                                                Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty Wishlist State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-plum-100 to-plum-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-plum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Your wishlist is empty</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">Save your favorite items here to keep track of them and get notified about price drops!</p>

                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center justify-center px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Browse Products
                            </a>
                        </div>
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>

<!-- Toast Notification Container -->
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

    // Lazy load images
    const images = document.querySelectorAll('img[loading="lazy"]');

    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
});

// Toast notification function
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');

    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success'
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';

    toast.className = `toast-notification flex items-center gap-3 ${bgColor} text-white px-6 py-4 rounded-xl shadow-lg max-w-md`;
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

// Remove from wishlist
async function removeFromWishlist(productId) {
    try {
        const response = await fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            showToast(data.message, 'success');
            if (window.updateWishlistCounter) {
                window.updateWishlistCounter();
            }
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error removing item', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}

// Clear wishlist
async function clearWishlist() {
    if (!confirm('Are you sure you want to clear your entire wishlist? This action cannot be undone.')) {
        return;
    }

    try {
        const response = await fetch('/wishlist/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            showToast('Wishlist cleared successfully');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error clearing wishlist', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}

// Add to cart
async function addToCart(productId) {
    try {
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        });

        const data = await response.json();

        if (data.success) {
            showToast('Product added to cart!');
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        } else {
            showToast(data.message || 'Error adding to cart', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Something went wrong. Please try again.', 'error');
    }
}
</script>
@endpush
@endsection
