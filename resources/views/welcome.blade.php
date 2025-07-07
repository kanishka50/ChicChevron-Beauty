@extends('layouts.app')

@section('content')
<div class="relative">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Welcome to ChicChevron Beauty
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-primary-100">
                    Discover Premium Beauty Products for Every Need
                </p>
                <div class="space-x-4">
                    <a href="{{ route('products.index') }}" class="inline-block bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Shop Now
                    </a>
                    <a href="{{ route('categories.index') }}" class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition">
                        Browse Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-primary-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">100% Authentic</h3>
                    <p class="text-gray-600">All products are genuine and sourced directly from authorized distributors</p>
                </div>

                <div class="text-center">
                    <div class="bg-primary-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Secure Payments</h3>
                    <p class="text-gray-600">Multiple payment options including Cash on Delivery and online payments</p>
                </div>

                <div class="text-center">
                    <div class="bg-primary-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-gray-600">Quick and reliable delivery across Sri Lanka</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Preview -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Shop by Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-pink-400 to-pink-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Skin Care</h3>
                        <p class="text-gray-600 mb-4">Cleansers, moisturizers, serums, and more</p>
                        <a href="{{ route('categories.show', 'skin-care') }}" class="text-primary-600 font-semibold hover:text-primary-700">
                            Shop Now →
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-purple-400 to-purple-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Hair Care</h3>
                        <p class="text-gray-600 mb-4">Shampoos, conditioners, treatments, and styling</p>
                        <a href="{{ route('categories.show', 'hair-care') }}" class="text-primary-600 font-semibold hover:text-primary-700">
                            Shop Now →
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">Baby Care</h3>
                        <p class="text-gray-600 mb-4">Gentle care products for your little ones</p>
                        <a href="{{ route('categories.show', 'baby-care') }}" class="text-primary-600 font-semibold hover:text-primary-700">
                            Shop Now →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection