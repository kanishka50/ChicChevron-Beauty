@extends('layouts.app')

@section('title', 'Checkout - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Checkout Forms -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_name') border-red-500 @enderror"
                                       required>
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone', Auth::user()->phone ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_phone') border-red-500 @enderror"
                                       required>
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="customer_email" 
                                       name="customer_email" 
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_email') border-red-500 @enderror"
                                       required>
                                @error('customer_email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Address</h2>
                        
                        @if($userAddresses->isNotEmpty())
                            <!-- Saved Addresses -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Saved Addresses</label>
                                <div class="space-y-2">
                                    @foreach($userAddresses as $address)
                                        <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" 
                                                   name="saved_address" 
                                                   value="{{ $address->id }}"
                                                   class="mt-1 text-pink-600 focus:ring-pink-500"
                                                   onchange="fillAddressForm({{ $address->toJson() }})">
                                            <div class="flex-1">
                                                <div class="font-medium">{{ $address->address_line_1 }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->city }}, {{ $address->postal_code }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <hr class="my-4">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="saved_address" value="new" checked class="text-pink-600 focus:ring-pink-500">
                                    <span class="text-sm font-medium">Use new address</span>
                                </label>
                            </div>
                        @endif
                        
                        <div class="space-y-4">
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <textarea id="delivery_address" 
                                          name="delivery_address" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('delivery_address') border-red-500 @enderror"
                                          placeholder="Enter your full address"
                                          required>{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="delivery_city" class="block text-sm font-medium text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="delivery_city" 
                                           name="delivery_city" 
                                           value="{{ old('delivery_city') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('delivery_city') border-red-500 @enderror"
                                           required>
                                    @error('delivery_city')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="delivery_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Postal Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="delivery_postal_code" 
                                           name="delivery_postal_code" 
                                           value="{{ old('delivery_postal_code') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('delivery_postal_code') border-red-500 @enderror"
                                           required>
                                    @error('delivery_postal_code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Notes (Optional)
                                </label>
                                <textarea id="delivery_notes" 
                                          name="delivery_notes" 
                                          rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                                          placeholder="Any special delivery instructions">{{ old('delivery_notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="space-y-3">
                            <!-- Cash on Delivery -->
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="cod" 
                                       class="mt-1 text-pink-600 focus:ring-pink-500"
                                       checked>
                                <div class="flex-1">
                                    <div class="font-medium">Cash on Delivery</div>
                                    <div class="text-sm text-gray-600">Pay when you receive your order (Max: Rs. 10,000)</div>
                                </div>
                            </label>

                            <!-- PayHere -->
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="payhere" 
                                       class="mt-1 text-pink-600 focus:ring-pink-500">
                                <div class="flex-1">
                                    <div class="font-medium">Online Payment (PayHere)</div>
                                    <div class="text-sm text-gray-600">Pay securely with credit/debit cards</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Notes (Optional)</h2>
                        <textarea name="order_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                                  placeholder="Any special requests or notes for your order">{{ old('order_notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <!-- Order Items -->
                        <div class="space-y-3 mb-4">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->product_image }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-cover rounded">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h3>
                                        @if($item->variant_details_formatted)
                                            <p class="text-xs text-gray-500">{{ $item->variant_details_formatted }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->total_price_formatted }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Totals -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span>{{ $cartSummary['subtotal_formatted'] }}</span>
                            </div>
                            
                            @if($cartSummary['discount_amount'] > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Discount</span>
                                    <span>-{{ $cartSummary['discount_formatted'] }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span>{{ $cartSummary['shipping_formatted'] }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between text-lg font-medium">
                                    <span>Total</span>
                                    <span>{{ $cartSummary['total_formatted'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-6">
                            <label class="flex items-start space-x-2">
                                <input type="checkbox" 
                                       name="terms_accepted" 
                                       value="1"
                                       class="mt-1 text-pink-600 focus:ring-pink-500 @error('terms_accepted') border-red-500 @enderror"
                                       required>
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-pink-600 hover:text-pink-700">Terms and Conditions</a> and <a href="#" class="text-pink-600 hover:text-pink-700">Privacy Policy</a>
                                </span>
                            </label>
                            @error('terms_accepted')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" 
                                class="w-full mt-6 bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 font-medium">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function fillAddressForm(address) {
    document.getElementById('delivery_address').value = address.address_line_1;
    document.getElementById('delivery_city').value = address.city;
    document.getElementById('delivery_postal_code').value = address.postal_code;
}
</script>
@endsection