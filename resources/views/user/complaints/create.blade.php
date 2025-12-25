<!-- CREATE COMPLAINT PAGE -->
@extends('layouts.app')

@section('title', 'File a Complaint - ChicChevron Beauty')

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
    <div class="container-responsive py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden sticky top-0 z-40 -mx-4 px-4 py-3 bg-white/95 backdrop-blur-md border-b border-gray-100 mb-6 flex items-center justify-between">
            <a href="{{ route('user.complaints.index') }}" class="p-2 -ml-2 text-gray-600 hover:text-plum-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">File Complaint</h1>
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
                    <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                        <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="{{ route('user.complaints.index') }}" class="flex items-center gap-4 px-6 py-3 bg-plum-50 text-plum-700 border-r-4 border-plum-600">
                            <div class="w-10 h-10 rounded-xl bg-plum-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <li><a href="{{ route('user.complaints.index') }}" class="hover:text-plum-600 transition-colors">Help & Support</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">File Complaint</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="hidden lg:block mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">File a Complaint</h1>
                    <p class="text-gray-500 mt-1">We're here to help resolve any issues you may have</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('user.complaints.store') }}">
                    @csrf

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Form Header -->
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-plum-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-plum-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Complaint Details</h2>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Order Selection -->
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Related Order <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                                </label>
                                <select id="order_id"
                                        name="order_id"
                                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all">
                                    <option value="">-- No specific order --</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Order #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }} - Rs {{ number_format($order->total_amount, 0) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1.5 text-xs text-gray-500">Select an order if your complaint is related to a specific purchase</p>
                                @error('order_id')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Complaint Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Complaint Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="complaint-type-option relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 hover:border-plum-300 transition-all">
                                        <input type="radio"
                                               name="complaint_type"
                                               value="product_not_received"
                                               class="sr-only peer"
                                               {{ old('complaint_type') == 'product_not_received' ? 'checked' : '' }}>
                                        <div class="flex flex-1 items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block text-sm font-semibold text-gray-900">Product Not Received</span>
                                                <span class="mt-0.5 block text-xs text-gray-500">Order was placed but not delivered</span>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-plum-600 peer-checked:bg-plum-600 flex items-center justify-center transition-all">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <label class="complaint-type-option relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 hover:border-plum-300 transition-all">
                                        <input type="radio"
                                               name="complaint_type"
                                               value="wrong_product"
                                               class="sr-only peer"
                                               {{ old('complaint_type') == 'wrong_product' ? 'checked' : '' }}>
                                        <div class="flex flex-1 items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block text-sm font-semibold text-gray-900">Wrong Product</span>
                                                <span class="mt-0.5 block text-xs text-gray-500">Received different item than ordered</span>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-plum-600 peer-checked:bg-plum-600 flex items-center justify-center transition-all">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <label class="complaint-type-option relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 hover:border-plum-300 transition-all">
                                        <input type="radio"
                                               name="complaint_type"
                                               value="damaged_product"
                                               class="sr-only peer"
                                               {{ old('complaint_type') == 'damaged_product' ? 'checked' : '' }}>
                                        <div class="flex flex-1 items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block text-sm font-semibold text-gray-900">Damaged Product</span>
                                                <span class="mt-0.5 block text-xs text-gray-500">Product arrived in damaged condition</span>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-plum-600 peer-checked:bg-plum-600 flex items-center justify-center transition-all">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>

                                    <label class="complaint-type-option relative flex cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 hover:border-plum-300 transition-all">
                                        <input type="radio"
                                               name="complaint_type"
                                               value="other"
                                               class="sr-only peer"
                                               {{ old('complaint_type') == 'other' ? 'checked' : '' }}>
                                        <div class="flex flex-1 items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block text-sm font-semibold text-gray-900">Other Issue</span>
                                                <span class="mt-0.5 block text-xs text-gray-500">Different type of complaint</span>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-plum-600 peer-checked:bg-plum-600 flex items-center justify-center transition-all">
                                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                                @error('complaint_type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="subject"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       required
                                       maxlength="255"
                                       placeholder="Brief description of your issue"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all">
                                @error('subject')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description"
                                          name="description"
                                          rows="5"
                                          required
                                          maxlength="2000"
                                          placeholder="Please provide detailed information about your complaint. Include order details, dates, and any other relevant information..."
                                          class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent transition-all resize-none">{{ old('description') }}</textarea>
                                <div class="mt-1.5 flex justify-between text-xs text-gray-500">
                                    <span>Be as detailed as possible to help us resolve your issue quickly</span>
                                    <span><span id="charCount">0</span>/2000</span>
                                </div>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                            <a href="{{ route('user.complaints.index') }}"
                               class="w-full sm:w-auto px-5 py-2.5 text-center border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Submit Complaint
                            </button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
</div>

@push('styles')
<style>
    .complaint-type-option:has(input:checked) {
        border-color: rgb(var(--color-plum-500, 139 92 135));
        background-color: rgb(var(--color-plum-50, 250 245 250));
    }

    .complaint-type-option:has(input:checked) .absolute > svg {
        opacity: 1;
    }

    .complaint-type-option:has(input:checked) .absolute {
        border-color: rgb(var(--color-plum-600, 124 79 121));
        background-color: rgb(var(--color-plum-600, 124 79 121));
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

    // Character counter
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');

    if (description && charCount) {
        description.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        charCount.textContent = description.value.length;
    }

    // Radio button selection visual feedback
    document.querySelectorAll('.complaint-type-option input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.complaint-type-option').forEach(label => {
                if (label.querySelector('input').checked) {
                    label.classList.add('border-plum-500', 'bg-plum-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-plum-500', 'bg-plum-50');
                    label.classList.add('border-gray-200');
                }
            });
        });

        // Initialize on page load
        if (radio.checked) {
            radio.closest('.complaint-type-option').classList.add('border-plum-500', 'bg-plum-50');
            radio.closest('.complaint-type-option').classList.remove('border-gray-200');
        }
    });
});
</script>
@endpush
@endsection
