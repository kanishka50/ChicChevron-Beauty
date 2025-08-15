@extends('layouts.app')

@section('title', 'Shopping Cart - ChicChevron Beauty')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="container-responsive">
        <ol class="flex items-center space-x-1 md:space-x-2 text-xs md:text-sm flex-wrap">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">Home</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-medium">Shopping Cart</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="container-responsive">
        <!-- Enhanced Header -->
        <div class="mb-6 md:mb-8">
            <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-gray-600 mt-1 md:mt-2 text-xs md:text-sm">
                @if($cartItems->isNotEmpty())
                    You have {{ $cartSummary['total_items'] }} {{ Str::plural('item', $cartSummary['total_items']) }} in your cart
                @else
                    Your cart is waiting to be filled
                @endif
            </p>
        </div>

        @if($cartItems->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Cart Items - Mobile Optimized -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Free Shipping Progress Bar -->
                    @php
                        $freeShippingThreshold = 5000;
                        $remainingForFreeShipping = max(0, $freeShippingThreshold - $cartSummary['subtotal']);
                        $progressPercentage = min(100, ($cartSummary['subtotal'] / $freeShippingThreshold) * 100);
                    @endphp
                    
                    @if($remainingForFreeShipping > 0)
                        <div class="bg-gradient-to-r from-primary-50 to-pink-50 rounded-xl p-4 mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-gray-900">
                                    Add Rs. {{ number_format($remainingForFreeShipping, 2) }} more for FREE shipping!
                                </p>
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-full rounded-full transition-all duration-500"
                                     style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-sm font-medium text-green-800">You've qualified for FREE shipping!</p>
                            </div>
                        </div>
                    @endif

                    <!-- Cart Items List -->
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md" 
                             id="cart-item-{{ $item->id }}">
                            <!-- Mobile Layout -->
                            <div class="md:hidden p-4">
                                <div class="flex gap-3">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('products.show', $item->product->slug) }}" 
                                           class="block relative group">
                                            <img src="{{ $item->product_image }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-24 h-24 object-cover rounded-lg">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </a>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm md:text-base font-semibold text-gray-900 mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" 
                                               class="hover:text-primary-600 transition-colors line-clamp-2">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        
                                        @if($item->product->brand)
                                            <p class="text-xs text-gray-500">{{ $item->product->brand->name }}</p>
                                        @endif

                                        @if($item->variant_details_formatted)
                                            <p class="text-xs text-gray-600 mt-1 font-medium">{{ $item->variant_details_formatted }}</p>
                                        @endif

                                        <!-- Price -->
                                        <div class="mt-2">
                                            @if($item->product->is_on_sale && !$item->productVariant)
                                                <span class="text-xs text-gray-500 line-through">
                                                    Rs. {{ number_format($item->product->selling_price, 2) }}
                                                </span>
                                                <span class="text-sm md:text-base font-bold text-primary-600 ml-1">{{ $item->unit_price_formatted }}</span>
                                            @else
                                                <span class="text-sm md:text-base font-bold text-gray-900">{{ $item->unit_price_formatted }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity & Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center bg-gray-100 rounded-lg">
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="p-1.5 md:p-2 hover:bg-gray-200 transition-colors rounded-l-lg"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        
                                        <input type="number" 
                                               id="quantity-{{ $item->id }}"
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->available_stock }}"
                                               class="w-10 md:w-12 text-center bg-transparent border-0 font-medium text-sm"
                                               onchange="updateQuantity({{ $item->id }}, this.value)">
                                        
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="p-2 hover:bg-gray-200 transition-colors rounded-r-lg"
                                                {{ $item->quantity >= $item->available_stock ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Total</p>
                                        <p class="text-base md:text-lg font-bold text-gray-900" id="item-total-{{ $item->id }}">
                                            {{ $item->total_price_formatted }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Stock Status & Remove -->
                                <div class="mt-3 flex items-center justify-between">
                                    @if($item->is_available)
                                        <span class="inline-flex items-center gap-1 text-xs text-green-700">
                                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                                            In Stock ({{ $item->available_stock }} available)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-red-700">
                                            <div class="w-1.5 h-1.5 bg-red-500 rounded-full"></div>
                                            Out of Stock
                                        </span>
                                    @endif

                                    <button type="button" 
                                            onclick="removeItem({{ $item->id }})"
                                            class="text-red-600 hover:text-red-700 text-xs font-medium">
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <!-- Desktop Layout -->
                            <div class="hidden md:block p-6">
                                <div class="flex items-start gap-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('products.show', $item->product->slug) }}" 
                                           class="block relative group">
                                            <img src="{{ $item->product_image }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-28 h-28 object-cover rounded-xl">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-200"></div>
                                        </a>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" 
                                               class="hover:text-primary-600 transition-colors">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        
                                        <div class="flex items-center gap-3 text-sm text-gray-600">
                                            @if($item->product->brand)
                                                <span>{{ $item->product->brand->name }}</span>
                                                <span class="text-gray-300">|</span>
                                            @endif
                                            <span>SKU: {{ $item->productVariant?->sku ?? $item->product->sku }}</span>
                                        </div>

                                        @if($item->variant_details_formatted)
                                            <p class="text-sm font-medium text-gray-700 mt-2 bg-gray-50 inline-block px-3 py-1 rounded-full">
                                                {{ $item->variant_details_formatted }}
                                            </p>
                                        @endif

                                        <!-- Availability Status -->
                                        <div class="mt-3">
                                            @if($item->is_available)
                                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                    In Stock ({{ $item->available_stock }} available)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Price, Quantity and Actions -->
                                    <div class="text-right space-y-4">
                                        <!-- Price -->
                                        <div>
                                            @if($item->product->is_on_sale && !$item->productVariant)
                                                <p class="text-sm text-gray-500 line-through">
                                                    Rs. {{ number_format($item->product->selling_price, 2) }}
                                                </p>
                                                <p class="text-lg md:text-xl font-bold text-primary-600">{{ $item->unit_price_formatted }}</p>
                                            @else
                                                <p class="text-lg md:text-xl font-bold text-gray-900">{{ $item->unit_price_formatted }}</p>
                                            @endif
                                        </div>

                                        <!-- Quantity Controls -->
                                        <div class="flex items-center justify-end gap-3">
                                            <span class="text-sm text-gray-600">Qty:</span>
                                            <div class="flex items-center bg-gray-100 rounded-lg">
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        class="px-2 py-1.5 md:px-3 md:py-2  hover:bg-gray-200 transition-colors rounded-l-lg"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                    </svg>
                                                </button>
                                                
                                                <input type="number" 
                                                       id="quantity-desktop-{{ $item->id }}"
                                                       value="{{ $item->quantity }}" 
                                                       min="1" 
                                                       max="{{ $item->available_stock }}"
                                                       class="w-12 md:w-16 text-center bg-transparent border-0 font-medium"
                                                       onchange="updateQuantity({{ $item->id }}, this.value)">
                                                
                                                <button type="button" 
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        class="px-3 py-2 hover:bg-gray-200 transition-colors rounded-r-lg"
                                                        {{ $item->quantity >= $item->available_stock ? 'disabled' : '' }}>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="border-t pt-3">
                                            <p class="text-sm text-gray-600">Subtotal</p>
                                            <p class="text-xl md:text-2xl font-bold text-gray-900" id="item-total-desktop-{{ $item->id }}">
                                                {{ $item->total_price_formatted }}
                                            </p>
                                        </div>

                                        <!-- Remove Button -->
                                        <button type="button" 
                                                onclick="removeItem({{ $item->id }})"
                                                class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1 ml-auto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Clear Cart & Continue Shopping -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
                        <a href="{{ route('products.index') }}" 
                           class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                            </svg>
                            Continue Shopping
                        </a>
                        
                        <button type="button" 
                                onclick="clearCart()"
                                class="text-red-600 hover:text-red-700 text-sm font-medium">
                            Clear All Items
                        </button>
                    </div>
                </div>

                <!-- Order Summary - Enhanced Mobile View -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-base md:text-lg font-semibold text-gray-900">Order Summary</h3>
                        </div>

                        <!-- Summary Details -->
                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal ({{ $cartSummary['total_items'] }} {{ Str::plural('item', $cartSummary['total_items']) }})</span>
                                    <span class="font-medium text-gray-900" id="cart-subtotal">{{ $cartSummary['subtotal_formatted'] }}</span>
                                </div>

                                {{-- @if($cartSummary['discount_amount'] > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-green-600 font-medium">Discount</span>
                                        <span class="text-green-600 font-medium" id="cart-discount">-{{ $cartSummary['discount_formatted'] }}</span>
                                    </div>
                                @endif --}}

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium text-gray-900" id="cart-shipping">{{ $cartSummary['shipping_formatted'] }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-xl md:text-2xl font-bold text-gray-900" id="cart-total">{{ $cartSummary['total_formatted'] }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Including all taxes</p>
                            </div>

                            <!-- Savings Message -->
                            {{-- @if($cartSummary['discount_amount'] > 0)
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-sm text-green-700 font-medium">
                                        🎉 You're saving {{ $cartSummary['discount_formatted'] }}!
                                    </p>
                                </div>
                            @endif --}}
                        </div>

                        <!-- Checkout Button -->
                        <div class="p-6 bg-gray-50 space-y-3">
                            <a href="{{ route('checkout.index') }}" 
                               class="w-full bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-4 rounded-lg hover:from-primary-700 hover:to-primary-800 text-center font-semibold block transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                                Proceed to Checkout
                            </a>

                            <!-- Security Badge -->
                            <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Secure Checkout</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart - Enhanced Design -->
            <div class="max-w-lg mx-auto text-center py-12 md:py-20">
                <div class="bg-gray-100 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-8">Looks like you haven't added any items to your cart yet.</p>
                
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 font-medium transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Start Shopping
                </a>

                <!-- Suggestions -->
                <div class="mt-12">
                    <p class="text-sm text-gray-600 mb-4">Popular categories to explore:</p>
                    <div class="flex flex-wrap gap-2 justify-center">
                        @foreach(\App\Models\Category::active()->limit(4)->get() as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-sm text-gray-700 transition-colors">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Enhanced Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-8 shadow-2xl">
        <div class="flex flex-col items-center space-y-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 rounded-full"></div>
                <div class="w-16 h-16 border-4 border-primary-600 rounded-full border-t-transparent animate-spin absolute top-0 left-0"></div>
            </div>
            <span class="text-gray-700 font-medium">Updating your cart...</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Enhanced Cart Functions
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Use the global showToast function from app.blade.php
function showMessage(message, type = 'success') {
    if (typeof showToast === 'function') {
        showToast(message, type);
    } else {
        // Fallback if showToast is not available
        alert(message);
    }
}

async function updateQuantity(itemId, quantity) {
    quantity = parseInt(quantity);
    if (quantity < 1 || isNaN(quantity)) return;
    
    // Update both mobile and desktop quantity inputs
    const mobileInput = document.getElementById(`quantity-${itemId}`);
    const desktopInput = document.getElementById(`quantity-desktop-${itemId}`);
    
    showLoading();
    
    try {
        const response = await fetch('/cart/update-quantity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                cart_item_id: itemId,
                quantity: quantity
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Update item totals
            const mobileTotal = document.getElementById(`item-total-${itemId}`);
            const desktopTotal = document.getElementById(`item-total-desktop-${itemId}`);
            
            if (mobileTotal) mobileTotal.textContent = data.item_total;
            if (desktopTotal) desktopTotal.textContent = data.item_total;
            
            // Update quantity inputs
            if (mobileInput) mobileInput.value = quantity;
            if (desktopInput) desktopInput.value = quantity;
            
            // Update cart summary with animation
            updateCartSummary(data);
            
            showMessage(data.message);
            
            // Update cart counter
            window.dispatchEvent(new Event('cart-updated'));
        } else {
            showMessage(data.message || 'Unable to update quantity', 'error');
            // Reset quantity inputs to previous value
            if (mobileInput) mobileInput.value = data.previous_quantity || quantity;
            if (desktopInput) desktopInput.value = data.previous_quantity || quantity;
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error updating cart. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function removeItem(itemId) {
    if (!confirm('Remove this item from your cart?')) {
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                cart_item_id: itemId
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Animate item removal
            const itemElement = document.getElementById(`cart-item-${itemId}`);
            if (itemElement) {
                itemElement.style.transform = 'translateX(100%)';
                itemElement.style.opacity = '0';
                setTimeout(() => itemElement.remove(), 300);
            }
            
            showMessage(data.message);
            
            // Update cart counter
            window.dispatchEvent(new Event('cart-updated'));
            
            // Refresh page if cart is empty
            if (data.cart_count === 0) {
                setTimeout(() => window.location.reload(), 500);
            } else {
                // Update cart summary
                updateCartSummary(data);
            }
        } else {
            showMessage(data.message || 'Unable to remove item', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error removing item. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function clearCart() {
    if (!confirm('Are you sure you want to remove all items from your cart?')) {
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message);
            
            // Animate all items removal
            const items = document.querySelectorAll('[id^="cart-item-"]');
            items.forEach((item, index) => {
                setTimeout(() => {
                    item.style.transform = 'translateX(100%)';
                    item.style.opacity = '0';
                }, index * 100);
            });
            
            // Update cart counter
            window.dispatchEvent(new Event('cart-updated'));
            
            setTimeout(() => window.location.reload(), 500);
        } else {
            showMessage('Unable to clear cart. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error clearing cart. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}


// Helper function to update cart summary with animation
function updateCartSummary(data) {
    // Update subtotal
    const subtotalEl = document.getElementById('cart-subtotal');
    if (subtotalEl && data.cart_subtotal) {
        animateValue(subtotalEl, data.cart_subtotal);
    }
    
    // Update total
    const totalEl = document.getElementById('cart-total');
    if (totalEl && data.cart_total) {
        animateValue(totalEl, data.cart_total);
    }
    
    // Update discount if present
    // const discountEl = document.getElementById('cart-discount');
    // if (discountEl && data.cart_discount) {
    //     animateValue(discountEl, data.cart_discount);
    // }
}

// Animate value change
function animateValue(element, newValue) {
    element.style.transform = 'scale(1.1)';
    element.style.color = '#ec4899';
    setTimeout(() => {
        element.textContent = newValue;
        element.style.transform = 'scale(1)';
        element.style.color = '';
    }, 200);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
</script>

<style>
/* Smooth transitions */
#cart-item-* {
    transition: all 0.3s ease;
}

/* Loading animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Number input styling */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
@endpush