@extends('layouts.app')

@section('title', 'My Account - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
            <p class="mt-2 text-gray-600">Welcome back, {{ $user->name }}!</p>
        </div>

        <!-- Account Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Orders</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Active Orders</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Completed</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-600">LKR {{ number_format($stats['total_spent'], 0) }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Spent</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-pink-600">{{ $stats['wishlist_count'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Wishlist</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $stats['addresses_count'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">Addresses</p>
                </div>
            </div>
        </div>

        <!-- Quick Links Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Orders -->
            <a href="{{ route('user.orders.index') }}" class="group">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-full p-3 group-hover:bg-blue-200 transition-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">My Orders</h3>
                            <p class="text-sm text-gray-600">Track & manage your orders</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Profile -->
            <a href="{{ route('user.account.profile') }}" class="group">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-3 group-hover:bg-green-200 transition-colors">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Profile</h3>
                            <p class="text-sm text-gray-600">Update personal information</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Addresses -->
            <a href="{{ route('user.account.addresses') }}" class="group">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-purple-100 rounded-full p-3 group-hover:bg-purple-200 transition-colors">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Addresses</h3>
                            <p class="text-sm text-gray-600">Manage delivery locations</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Wishlist -->
            <a href="{{ route('wishlist.index') }}" class="group">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-pink-100 rounded-full p-3 group-hover:bg-pink-200 transition-colors">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Wishlist</h3>
                            <p class="text-sm text-gray-600">Your saved items</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Reviews -->
            <a href="{{ route('user.reviews.index') }}" class="group">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 rounded-full p-3 group-hover:bg-yellow-200 transition-colors">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Reviews</h3>
                            <p class="text-sm text-gray-600">{{ $stats['reviews_count'] }} product reviews</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Complaints -->
            <a href="{{ route('user.complaints.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Complaints</h3>
                            <p class="text-sm text-gray-600">Manage your complaints & feedback</p>
                        </div>
                    </div>
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Recent Orders -->
        @if($recentOrders->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('user.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recentOrders as $order)
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center space-x-4">
                                <h3 class="text-sm font-medium text-gray-900">
                                    Order #{{ $order->order_number }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'shipping') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                Placed on {{ $order->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-900 font-medium mt-1">
                                Total: LKR {{ number_format($order->total_amount, 2) }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Order Items Preview -->
                            <div class="flex -space-x-2">
                                @foreach($order->items->take(3) as $item)
                                    @if($item->product && $item->product->main_image)
                                    <img src="{{ Storage::url($item->product->main_image) }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-10 h-10 rounded-full border-2 border-white object-cover">
                                    @else
                                    <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-xs"></i>
                                    </div>
                                    @endif
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center">
                                    <span class="text-xs text-gray-600">+{{ $order->items->count() - 3 }}</span>
                                </div>
                                @endif
                            </div>
                            <a href="{{ route('user.orders.show', $order) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
            <p class="text-gray-600 mb-6">Start shopping to see your orders here!</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-shopping-cart mr-2"></i>
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</div>
@endsection