<!-- Cart Dropdown Component -->
<div class="relative" x-data="cartDropdown()" @click.away="closeDropdown()">
    <!-- Cart Button -->
    <button @click="toggleDropdown()" 
            class="relative p-2.5 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
        <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        
        <!-- Cart Count Badge -->
        <span x-show="cartCount > 0" 
              x-text="cartCount"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="opacity-0 scale-0"
              x-transition:enter-end="opacity-100 scale-100"
              class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium animate-bounce">
        </span>
    </button>

    <!-- Dropdown Content -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 mt-3 w-96 max-w-[calc(100vw-2rem)] bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 md:p-5 bg-gradient-to-r from-primary-50 to-primary-100 border-b border-primary-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Shopping Cart</h3>
                    <p class="text-sm text-gray-600 mt-0.5" x-show="cartCount > 0">
                        <span x-text="cartCount"></span> item<span x-show="cartCount !== 1">s</span> in your cart
                    </p>
                </div>
                <button @click="closeDropdown()" class="p-1 rounded-lg hover:bg-primary-200/50 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
            <!-- Loading State -->
            <div x-show="isLoading" class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-100 rounded-full mb-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
                </div>
                <p class="text-sm text-gray-500">Loading your cart...</p>
            </div>

            <!-- Empty Cart -->
            <div x-show="!isLoading && cartItems.length === 0" class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-900 font-medium mb-1">Your cart is empty</p>
                <p class="text-sm text-gray-500 mb-4">Add some products to get started!</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium group">
                    <span>Start Shopping</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Cart Items List -->
            <div x-show="!isLoading && cartItems.length > 0" class="divide-y divide-gray-100">
                <template x-for="item in cartItems" :key="item.id">
                    <div class="p-4 hover:bg-gray-50 transition-colors group">
                        <div class="flex items-start gap-3">
                            <!-- Product Image -->
                            <div class="relative flex-shrink-0">
                                <img :src="item.product_image" 
                                     :alt="item.product_name"
                                     class="w-16 h-16 object-cover rounded-xl">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 rounded-xl transition-colors"></div>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-600 transition-colors" 
                                    x-text="item.product_name"></h4>
                                <p x-show="item.variant_details" 
                                   x-text="item.variant_details" 
                                   class="text-xs text-gray-500 mt-0.5"></p>
                                
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center border border-gray-200 rounded-lg">
                                            <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                                    :disabled="item.quantity <= 1"
                                                    class="p-1 hover:bg-gray-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="px-3 text-sm font-medium" x-text="item.quantity"></span>
                                            <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                                    class="p-1 hover:bg-gray-100 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900" x-text="item.total_price"></span>
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <button @click="removeItem(item.id)" 
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <div x-show="!isLoading && cartItems.length > 0" class="p-4 md:p-5 border-t border-gray-100 bg-gray-50">
            <!-- Summary -->
            <div class="space-y-2 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Subtotal</span>
                    <span class="text-sm font-medium text-gray-900" x-text="cartSummary.subtotal_formatted"></span>
                </div>
                <div class="flex justify-between items-center" x-show="cartSummary.discount_amount > 0">
                    <span class="text-sm text-gray-600">Discount</span>
                    <span class="text-sm font-medium text-green-600">-<span x-text="cartSummary.discount_formatted"></span></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                    <span class="text-base font-medium text-gray-900">Total</span>
                    <span class="text-lg font-bold text-primary-600" x-text="cartSummary.total_formatted"></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <a href="{{ route('cart.index') }}" 
                   class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-50 transition-all font-medium group">
                    <span>View Cart</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </a>
                <a href="{{ route('checkout.index') }}" 
                   class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-3 px-4 rounded-xl hover:from-primary-700 hover:to-primary-800 transition-all font-medium group shadow-lg hover:shadow-xl">
                    <span>Checkout</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Free Shipping Notice -->
            <div class="mt-3 text-center">
                <p class="text-xs text-gray-500">
                    @if($cartSummary.total < 5000)
                        Add Rs. {{ number_format(5000 - $cartSummary.total, 2) }} more for free shipping!
                    @else
                        ✓ You qualify for free shipping!
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for cart items */
.scrollbar-thin {
    scrollbar-width: thin;
}

.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Animation for badge */
@keyframes bounce {
    0%, 100% {
        transform: translateY(-25%);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}
</style>

<script>
function cartDropdown() {
    return {
        isOpen: false,
        isLoading: false,
        cartItems: [],
        cartCount: 0,
        cartSummary: {},

        init() {
            this.loadCartData();
            
            // Listen for cart updates
            window.addEventListener('cart-updated', () => {
                this.loadCartData();
            });
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.loadCartData();
            }
        },

        closeDropdown() {
            this.isOpen = false;
        },

        async loadCartData() {
            this.isLoading = true;
            
            try {
                const response = await fetch('/cart/summary');
                const data = await response.json();
                
                if (data.success) {
                    this.cartItems = data.items;
                    this.cartCount = data.summary.total_items;
                    this.cartSummary = data.summary;
                }
            } catch (error) {
                console.error('Error loading cart data:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;
            
            try {
                const response = await fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        cart_item_id: itemId,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    this.loadCartData();
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    window.showToast(data.message);
                } else {
                    window.showToast(data.message, 'error');
                }
            } catch (error) {
                window.showToast('Error updating quantity. Please try again.', 'error');
            }
        },

        async removeItem(itemId) {
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
                    this.loadCartData();
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    window.showToast(data.message);
                } else {
                    window.showToast(data.message, 'error');
                }
            } catch (error) {
                window.showToast('Error removing item. Please try again.', 'error');
            }
        }
    }
}
</script>