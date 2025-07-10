@extends('layouts.app')

@section('title', 'Shopping Cart - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-gray-600 mt-2">Review your items before checkout</p>
        </div>

        @if($cartItems->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" id="cart-item-{{ $item->id }}">
                            <div class="flex items-start space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    <img src="{{ $item->product_image }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="{{ route('products.show', $item->product->slug) }}" 
                                           class="hover:text-pink-600">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    
                                    @if($item->product->brand)
                                        <p class="text-sm text-gray-500">{{ $item->product->brand->name }}</p>
                                    @endif

                                    @if($item->variant_details_formatted)
                                        <p class="text-sm text-gray-600 mt-1">{{ $item->variant_details_formatted }}</p>
                                    @endif

                                    <p class="text-sm text-gray-500 mt-1">SKU: {{ $item->variantCombination?->combination_sku ?? $item->product->sku }}</p>

                                    <!-- Availability Status -->
                                    @if($item->is_available)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                            In Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>

                                <!-- Price and Quantity -->
                                <div class="text-right">
                                    <p class="text-lg font-medium text-gray-900">{{ $item->unit_price_formatted }}</p>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center mt-3 space-x-2">
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
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
                                               class="w-16 text-center border border-gray-300 rounded-md py-1"
                                               onchange="updateQuantity({{ $item->id }}, this.value)">
                                        
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                                {{ $item->quantity >= $item->available_stock ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <p class="text-sm text-gray-500 mt-1">Available: {{ $item->available_stock }}</p>
                                    
                                    <!-- Item Total -->
                                    <p class="text-lg font-semibold text-gray-900 mt-2" id="item-total-{{ $item->id }}">
                                        {{ $item->total_price_formatted }}
                                    </p>

                                    <!-- Remove Button -->
                                    <button type="button" 
                                            onclick="removeItem({{ $item->id }})"
                                            class="text-red-600 hover:text-red-700 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Clear Cart Button -->
                    <div class="text-center pt-4">
                        <button type="button" 
                                onclick="clearCart()"
                                class="text-red-600 hover:text-red-700 text-sm">
                            Clear All Items
                        </button>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        
                        <!-- Promotion Code -->
                        <div class="mb-4">
                            <label for="promotion-code" class="block text-sm font-medium text-gray-700 mb-2">
                                Promotion Code
                            </label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       id="promotion-code" 
                                       placeholder="Enter code"
                                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm">
                                <button type="button" 
                                        onclick="applyPromotion()"
                                        class="px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 text-sm">
                                    Apply
                                </button>
                            </div>
                            
                            @if($cartSummary['applied_promotion'])
                                <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-green-700">{{ $cartSummary['applied_promotion']['code'] }} applied</span>
                                        <button type="button" 
                                                onclick="removePromotion()"
                                                class="text-red-600 hover:text-red-700 text-xs">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Summary Details -->
                        <div class="space-y-3 border-t border-gray-200 pt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $cartSummary['total_items'] }} items)</span>
                                <span id="cart-subtotal">{{ $cartSummary['subtotal_formatted'] }}</span>
                            </div>

                            @if($cartSummary['discount_amount'] > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-green-600">Discount</span>
                                    <span class="text-green-600" id="cart-discount">-{{ $cartSummary['discount_formatted'] }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span id="cart-shipping">{{ $cartSummary['shipping_formatted'] }}</span>
                            </div>

                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-medium">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-gray-900" id="cart-total">{{ $cartSummary['total_formatted'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-6">
                            <a href="{{ route('checkout.index') }}" 
                            class="w-full bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 text-center font-medium block">
                                Proceed to Checkout
                            </a>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="mt-4">
                            <a href="{{ route('products.index') }}" 
                               class="w-full text-pink-600 hover:text-pink-700 text-center py-2 block">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-2 text-gray-500">Start shopping to add items to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                        Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6">
        <div class="flex items-center space-x-3">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-pink-600"></div>
            <span>Processing...</span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Cart JavaScript Functions
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

function showMessage(message, type = 'success') {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

async function updateQuantity(itemId, quantity) {
    if (quantity < 1) return;
    
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
                quantity: parseInt(quantity)
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Update item total
            document.getElementById(`item-total-${itemId}`).textContent = data.item_total;
            
            // Update quantity input
            document.getElementById(`quantity-${itemId}`).value = quantity;
            
            // Update cart summary
            document.getElementById('cart-subtotal').textContent = data.cart_subtotal;
            
            showMessage(data.message);
            
            // Refresh page to update all totals properly
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showMessage(data.message, 'error');
            // Reset quantity input to previous value
            document.getElementById(`quantity-${itemId}`).value = quantity > 1 ? quantity - 1 : 1;
        }
    } catch (error) {
        showMessage('Error updating cart. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
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
            // Remove item from DOM
            document.getElementById(`cart-item-${itemId}`).remove();
            
            showMessage(data.message);
            
            // Refresh page if cart is empty
            if (data.cart_count === 0) {
                setTimeout(() => window.location.reload(), 1000);
            }
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Error removing item. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function clearCart() {
    if (!confirm('Are you sure you want to clear your entire cart?')) {
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
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showMessage('Error clearing cart. Please try again.', 'error');
        }
    } catch (error) {
        showMessage('Error clearing cart. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function applyPromotion() {
    const promotionCode = document.getElementById('promotion-code').value.trim();
    
    if (!promotionCode) {
        showMessage('Please enter a promotion code.', 'error');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('/cart/apply-promotion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                promotion_code: promotionCode
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showMessage(data.message, 'error');
        }
    } catch (error) {
        showMessage('Error applying promotion. Please try again.', 'error');
    } finally {
        hideLoading();
    }
}

async function removePromotion() {
    showLoading();
    
    try {
        const response = await fetch('/cart/remove-promotion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showMessage(data.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showMessage('Error removing promotion.', 'error');
        }
    } catch (error) {
        showMessage('Error removing promotion.', 'error');
    } finally {
        hideLoading();
    }
}
</script>
@endpush