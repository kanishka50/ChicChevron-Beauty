@extends('layouts.app')

@section('title', 'Checkout - ChicChevron Beauty')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="container-responsive">
        <ol class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm flex-wrap">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Home</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Cart</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">Checkout</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="container-responsive">
        <!-- Enhanced Header -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-1 md:mt-2 text-xs md:text-sm">Complete your order in just a few steps</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column - Checkout Forms -->
                <div class="lg:col-span-2 space-y-4 md:space-y-6">
                    
                    <!-- Customer Information - Enhanced Mobile Design -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-50 to-pink-50 px-4 md:px-6 py-4">
                            <h2 class="text-base md:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 bg-white rounded-full flex items-center justify-center text-primary-600 font-bold text-sm">1</span>
                                Customer Information
                            </h2>
                        </div>
                        
                        <div class="p-4 md:p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 md:col-span-2 md:grid md:grid-cols-2 md:gap-4 space-y-4 md:space-y-0">
                                    <div>
                                        <label for="customer_name" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                            Full Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               id="customer_name" 
                                               name="customer_name" 
                                               value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                               class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('customer_name') border-red-500 @enderror"
                                               placeholder="Enter your full name"
                                               required>
                                        @error('customer_name')
                                            <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="customer_phone" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                            Phone Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" 
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               value="{{ old('customer_phone', Auth::user()->phone ?? '') }}"
                                               class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('customer_phone') border-red-500 @enderror"
                                               placeholder="07X XXX XXXX"
                                               required>
                                        @error('customer_phone')
                                            <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="customer_email" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="customer_email" 
                                           name="customer_email" 
                                           value="{{ old('customer_email', Auth::user()->email ?? '') }}"
                                           class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('customer_email') border-red-500 @enderror"
                                           placeholder="your@email.com"
                                           required>
                                    @error('customer_email')
                                        <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address - Enhanced Mobile Design -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-50 to-pink-50 px-4 md:px-6 py-4">
                            <h2 class="text-base md:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 bg-white rounded-full flex items-center justify-center text-primary-600 font-bold text-sm">2</span>
                                Delivery Address
                            </h2>
                        </div>
                        
                        <div class="p-4 md:p-6">
                            @if($userAddresses->isNotEmpty())
                                <!-- Saved Addresses - Mobile Optimized -->
                                <div class="mb-6">
                                    <label class="block text-xs md:text-sm font-medium text-gray-700 mb-3">Select Delivery Address</label>
                                    <div class="space-y-3">
                                        <label class="flex items-start space-x-3 p-3 md:p-4 border-2 border-gray-200 rounded-xl hover:border-primary-200 hover:bg-primary-50/30 cursor-pointer transition-all duration-200 group">
                                            <input type="radio" 
                                                   name="address_selection" 
                                                   value="new"
                                                   class="mt-1 text-primary-600 focus:ring-primary-500 w-4 h-4"
                                                   checked
                                                   onchange="useNewAddress()">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900 group-hover:text-primary-700 text-sm md:text-base">Enter New Address</div>
                                                <div class="text-xs md:text-sm text-gray-500 mt-1">Fill in the delivery details below</div>
                                            </div>
                                        </label>
                                        
                                        @foreach($userAddresses as $address)
                                            <label class="flex items-start space-x-3 p-3 md:p-4 border-2 border-gray-200 rounded-xl hover:border-primary-200 hover:bg-primary-50/30 cursor-pointer transition-all duration-200 group">
                                                <input type="radio" 
                                                       name="address_selection" 
                                                       value="saved_{{ $address->id }}"
                                                       class="mt-1 text-primary-600 focus:ring-primary-500 w-4 h-4"
                                                       onchange="useSavedAddress({{ $address->toJson() }})">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="font-medium text-gray-900 group-hover:text-primary-700 text-sm md:text-base">{{ $address->name }}</span>
                                                        @if($address->is_default)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Default
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs md:text-sm text-gray-600">{{ $address->phone }}</div>
                                                    <div class="text-xs md:text-sm text-gray-600 mt-1">{{ $address->address_line_1 }}</div>
                                                    @if($address->address_line_2)
                                                        <div class="text-xs md:text-sm text-gray-600">{{ $address->address_line_2 }}</div>
                                                    @endif
                                                    <div class="text-xs md:text-sm text-gray-600">
                                                        {{ $address->city }}, {{ $address->district }} {{ $address->postal_code }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <hr class="mb-6 border-gray-200">
                            @endif
                            
                            <!-- Address Form Fields - Mobile Optimized -->
                            <div id="address-form" class="space-y-4">
                                <input type="hidden" name="saved_address_id" id="saved_address_id" value="">
                                
                                <div>
                                    <label for="address_line_1" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                        Address Line 1 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="address_line_1" 
                                           name="address_line_1" 
                                           value="{{ old('address_line_1') }}"
                                           placeholder="House/Building No, Street Name"
                                           class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('address_line_1') border-red-500 @enderror"
                                           required>
                                    @error('address_line_1')
                                        <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="address_line_2" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                        Address Line 2 <span class="text-gray-400 text-xs">(Optional)</span>
                                    </label>
                                    <input type="text" 
                                           id="address_line_2" 
                                           name="address_line_2" 
                                           value="{{ old('address_line_2') }}"
                                           placeholder="Apartment, Suite, Unit, etc."
                                           class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('address_line_2') border-red-500 @enderror">
                                    @error('address_line_2')
                                        <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="city" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                            City <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            id="city" 
                                            name="city" 
                                            value="{{ old('city') }}"
                                            placeholder="Enter your city"
                                            class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('city') border-red-500 @enderror"
                                            required>
                                        @error('city')
                                            <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="district" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                            District <span class="text-red-500">*</span>
                                        </label>
                                        <select id="district" 
                                                name="district" 
                                                class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('district') border-red-500 @enderror"
                                                required>
                                            <option value="">Select District</option>
                                            @foreach(['Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya', 'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar', 'Mullaitivu', 'Vavuniya', 'Puttalam', 'Kurunegala', 'Anuradhapura', 'Polonnaruwa', 'Badulla', 'Moneragala', 'Ratnapura', 'Kegalle', 'Batticaloa', 'Ampara', 'Trincomalee'] as $district)
                                                <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>
                                                    {{ $district }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('district')
                                            <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="postal_code" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                            Postal Code <span class="text-gray-400 text-xs">(Optional)</span>
                                        </label>
                                        <input type="text" 
                                               id="postal_code" 
                                               name="postal_code" 
                                               value="{{ old('postal_code') }}"
                                               placeholder="00000"
                                               class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 @error('postal_code') border-red-500 @enderror"
                                               >
                                        @error('postal_code')
                                            <p class="mt-1 text-xs md:text-sm text-red-500 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="delivery_notes" class="block text-xs md:text-sm font-medium text-gray-700 mb-2">
                                        Delivery Notes <span class="text-gray-400 text-xs">(Optional)</span>
                                    </label>
                                    <textarea id="delivery_notes" 
                                              name="delivery_notes" 
                                              rows="3"
                                              class="w-full px-3 py-2 md:px-4 md:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
                                              placeholder="Any special delivery instructions">{{ old('delivery_notes') }}</textarea>
                                </div>

                                <!-- Save Address Option - Enhanced Design -->
                                <div class="mt-4">
                                    <label class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                        <input type="checkbox" 
                                               name="save_address" 
                                               value="1"
                                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4"
                                               {{ old('save_address') ? 'checked' : '' }}>
                                        <span class="text-xs md:text-sm text-gray-700">Save this address for future orders</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method - Cash on Delivery Only -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-50 to-pink-50 px-4 md:px-6 py-4">
                            <h2 class="text-base md:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-6 h-6 md:w-8 md:h-8 bg-white rounded-full flex items-center justify-center text-primary-600 font-bold text-sm">3</span>
                                Payment Method
                            </h2>
                        </div>

                        <div class="p-4 md:p-6">
                            <!-- Cash on Delivery - Only Option -->
                            <div class="flex items-start space-x-3 p-3 md:p-4 border-2 border-primary-200 bg-primary-50/30 rounded-xl">
                                <input type="hidden" name="payment_method" value="cod">
                                <div class="mt-1">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 text-sm md:text-base">Cash on Delivery</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Available
                                        </span>
                                    </div>
                                    <div class="text-xs md:text-sm text-gray-600 mt-1">Pay when you receive your order</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary - Sticky on Desktop, Static on Mobile -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-4">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-4 md:px-6 py-4">
                            <h2 class="text-base md:text-lg font-semibold text-white">Order Summary</h2>
                        </div>
                        
                        <div class="p-4 md:p-6">
                            <!-- Order Items - Mobile Optimized -->
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
                                @foreach($cartItems as $item)
                                    <div class="flex items-start gap-3">
                                        <img src="{{ $item->product_image }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-14 h-14 object-cover rounded-lg flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-xs md:text-sm font-medium text-gray-900 line-clamp-1">{{ $item->product->name }}</h3>
                                            @if($item->variant_details_formatted)
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant_details_formatted }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 flex-shrink-0">
                                            {{ $item->total_price_formatted }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Totals - Enhanced Design -->
                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">{{ $cartSummary['subtotal_formatted'] }}</span>
                                </div>
                                
                                @if($cartSummary['discount_amount'] > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Discount
                                        </span>
                                        <span class="font-medium text-green-600">-{{ $cartSummary['discount_formatted'] }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium">{{ $cartSummary['shipping_formatted'] }}</span>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold">Total</span>
                                        <span class="text-lg font-bold text-primary-600">{{ $cartSummary['total_formatted'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions - Enhanced Mobile Touch Target -->
                            <div class="mt-6">
                                <label class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                    <input type="checkbox" 
                                           name="terms_accepted" 
                                           value="1"
                                           class="mt-0.5 text-primary-600 focus:ring-primary-500 rounded w-4 h-4 @error('terms_accepted') border-red-500 @enderror"
                                           required>
                                    <span class="text-xs md:text-sm text-gray-600 flex-1">
                                        I agree to the <a href="#" class="text-primary-600 hover:text-primary-700 underline">Terms and Conditions</a> and <a href="#" class="text-primary-600 hover:text-primary-700 underline">Privacy Policy</a>
                                    </span>
                                </label>
                                @error('terms_accepted')
                                    <p class="mt-2 text-xs md:text-sm text-red-500 px-4 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Place Order Button - Enhanced Mobile Design -->
                            <button type="submit" 
                                    class="w-full mt-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-4 md:py-4 md:px-6 rounded-lg hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-semibold transform hover:scale-[1.02] transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Place Order
                            </button>

                            <!-- Security Badge -->
                            <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-600">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Your information is secure and encrypted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Enhanced Loading Overlay -->
<div id="checkout-loading" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 shadow-2xl">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 rounded-full"></div>
                <div class="w-16 h-16 border-4 border-primary-600 rounded-full border-t-transparent animate-spin absolute top-0 left-0"></div>
            </div>
            <span class="text-gray-700 font-medium">Processing your order...</span>
        </div>
    </div>
</div>

<script>
function useSavedAddress(address) {
    // Set saved address ID
    document.getElementById('saved_address_id').value = address.id;
    
    // DON'T change customer info - keep the logged-in user's details
    // Only fill address fields
    document.getElementById('address_line_1').value = address.address_line_1;
    document.getElementById('address_line_2').value = address.address_line_2 || '';
    document.getElementById('city').value = address.city;
    document.getElementById('district').value = address.district;
    document.getElementById('postal_code').value = address.postal_code || '';
    
    // Disable save address checkbox when using saved address
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.checked = false;
        saveCheckbox.disabled = true;
        saveCheckbox.closest('label').classList.add('opacity-60', 'cursor-not-allowed');
    }
    
    // Make ONLY address fields readonly (not customer fields)
    const fields = ['address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.readOnly = true;
            element.classList.add('bg-gray-50', 'cursor-not-allowed');
        }
    });
}

function useNewAddress() {
    // Clear saved address ID
    document.getElementById('saved_address_id').value = '';
    
    // Clear ONLY address fields (not customer fields)
    const fields = ['address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.value = '';
            element.readOnly = false;
            element.classList.remove('bg-gray-50', 'cursor-not-allowed');
        }
    });
    
    // Enable save address checkbox
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.disabled = false;
        saveCheckbox.closest('label').classList.remove('opacity-60', 'cursor-not-allowed');
    }
}

// Auto-select district based on city (common mapping for Sri Lanka)
document.getElementById('city')?.addEventListener('change', function() {
    const cityDistrictMap = {
        'Colombo': 'Colombo',
        'Gampaha': 'Gampaha',
        'Kalutara': 'Kalutara',
        'Kandy': 'Kandy',
        'Matale': 'Matale',
        'Nuwara Eliya': 'Nuwara Eliya',
        'Galle': 'Galle',
        'Matara': 'Matara',
        'Hambantota': 'Hambantota',
        'Jaffna': 'Jaffna',
        'Kilinochchi': 'Kilinochchi',
        'Mannar': 'Mannar',
        'Mullaitivu': 'Mullaitivu',
        'Vavuniya': 'Vavuniya',
        'Puttalam': 'Puttalam',
        'Kurunegala': 'Kurunegala',
        'Anuradhapura': 'Anuradhapura',
        'Polonnaruwa': 'Polonnaruwa',
        'Badulla': 'Badulla',
        'Moneragala': 'Moneragala',
        'Ratnapura': 'Ratnapura',
        'Kegalle': 'Kegalle',
        'Batticaloa': 'Batticaloa',
        'Ampara': 'Ampara',
        'Trincomalee': 'Trincomalee'
    };
    
    const district = cityDistrictMap[this.value];
    if (district) {
        document.getElementById('district').value = district;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        // Override fetch to block counter requests during checkout
        const originalFetch = window.fetch;
        let isSubmitting = false;
        
        checkoutForm.addEventListener('submit', function(e) {
            // Show loading overlay
            document.getElementById('checkout-loading').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            console.log('Checkout form submitting...');
            
            // Set flag
            isSubmitting = true;
            window._isCheckoutInProgress = true;
            
            // Override fetch to block counter requests
            window.fetch = function(url, options) {
                if (isSubmitting && (url.includes('/cart/count') || url.includes('/wishlist/count'))) {
                    console.log('Blocked counter request during checkout:', url);
                    return Promise.resolve({
                        json: () => Promise.resolve({ count: 0 }),
                        ok: true
                    });
                }
                return originalFetch.apply(this, arguments);
            };
            
            // Disable all event listeners temporarily
            const stopAllEvents = function(e) {
                if (isSubmitting) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                }
            };
            
            window.addEventListener('cart-updated', stopAllEvents, true);
            window.addEventListener('wishlist-updated', stopAllEvents, true);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span> Processing order...';
            }
            
            // Disable all links and buttons
            document.querySelectorAll('a, button').forEach(el => {
                if (!el.closest('#checkout-form')) {
                    el.style.pointerEvents = 'none';
                    el.style.opacity = '0.6';
                }
            });
            
            console.log('All AJAX and events blocked during checkout');
        });
    }
});

// Also override XMLHttpRequest for older code
(function() {
    const originalXHR = window.XMLHttpRequest;
    
    window.XMLHttpRequest = function() {
        const xhr = new originalXHR();
        const originalOpen = xhr.open;
        
        xhr.open = function(method, url) {
            if (window._isCheckoutInProgress && (url.includes('/cart/count') || url.includes('/wishlist/count'))) {
                console.log('Blocked XHR counter request during checkout:', url);
                // Return a dummy response
                this.send = function() {
                    Object.defineProperty(this, 'responseText', {
                        get: function() { return '{"count": 0}'; }
                    });
                    Object.defineProperty(this, 'readyState', {
                        get: function() { return 4; }
                    });
                    Object.defineProperty(this, 'status', {
                        get: function() { return 200; }
                    });
                    if (this.onreadystatechange) {
                        this.onreadystatechange();
                    }
                };
                return;
            }
            return originalOpen.apply(this, arguments);
        };
        
        return xhr;
    };
})();
</script>

<style>
/* Custom scrollbar for order items */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Line clamp utility */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}
</style>
@endsection