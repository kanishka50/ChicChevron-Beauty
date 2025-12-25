<!-- COMPLAINTS LIST PAGE -->
@extends('layouts.app')

@section('title', 'Help & Support - ChicChevron Beauty')

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
            <a href="{{ route('user.account.index') }}" class="p-2 -ml-2 text-gray-600 hover:text-plum-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Help & Support</h1>
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
                        <li class="text-gray-900 font-medium">Help & Support</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Help & Support</h1>
                        <p class="text-gray-500 mt-1">Track and manage your support tickets</p>
                    </div>
                    <a href="{{ route('user.complaints.create') }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        File New Complaint
                    </a>
                </div>

                @if($complaints->count() > 0)
                    <!-- Complaints List -->
                    <div class="space-y-4">
                        @foreach($complaints as $index => $complaint)
                            <a href="{{ route('user.complaints.show', $complaint) }}"
                               class="block bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-plum-200 transition-all duration-300"
                               style="animation: fadeInUp 0.4s ease-out {{ $index * 0.1 }}s backwards;">
                                <div class="p-5">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                        <!-- Complaint Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                                <p class="text-sm font-semibold text-plum-600">
                                                    #{{ $complaint->complaint_number }}
                                                </p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $complaint->status_color }}">
                                                    {{ $complaint->status_label }}
                                                </span>
                                                <span class="text-sm text-gray-400">
                                                    {{ $complaint->created_at->format('M d, Y') }}
                                                </span>
                                            </div>

                                            <h3 class="text-base font-medium text-gray-900 mb-2">
                                                {{ $complaint->subject }}
                                            </h3>

                                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    {{ $complaint->complaint_type_label }}
                                                </span>
                                                @if($complaint->order)
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                        Order #{{ $complaint->order->order_number }}
                                                    </span>
                                                @endif
                                                @if($complaint->responses->count() > 0)
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                        </svg>
                                                        {{ $complaint->responses->count() }} {{ Str::plural('response', $complaint->responses->count()) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Arrow Icon -->
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($complaints->hasPages())
                        <div class="mt-6">
                            {{ $complaints->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-plum-100 to-plum-200 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-plum-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No complaints yet</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">We hope you're having a great experience! If you need help, we're here for you.</p>
                        <a href="{{ route('user.complaints.create') }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            File Your First Complaint
                        </a>
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
