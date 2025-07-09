<!-- Cart Dropdown Component -->
<div class="relative" x-data="cartDropdown()" @click.away="closeDropdown()">
    <!-- Cart Button -->
    <button @click="toggleDropdown()" 
            class="relative p-2 text-gray-600 hover:text-pink-600 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        
        <!-- Cart Count Badge -->
        <span x-show="cartCount > 0" 
              x-text="cartCount"
              class="absolute -top-1 -right-1 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
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
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Shopping Cart</h3>
            <p class="text-sm text-gray-500" x-show="cartCount > 0">
                <span x-text="cartCount"></span> item<span x-show="cartCount !== 1">s</span> in your cart
            </p>
        </div>

        <!-- Cart Items -->
        <div class="max-h-64 overflow-y-auto">
            <!-- Loading State -->
            <div x-show="isLoading" class="p-4 text-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-pink-600 mx-auto"></div>
                <p class="text-sm text-gray-500 mt-2">Loading...</p>
            </div>

            <!-- Empty Cart -->
            <div x-show="!isLoading && cartItems.length === 0" class="p-4 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-sm text-gray-500 mt-2">Your cart is empty</p>
                <a href="{{ route('products.index') }}" 
                   class="text-pink-600 hover:text-pink-700 text-sm mt-1 inline-block">
                    Start Shopping
                </a>
            </div>

            <!-- Cart Items List -->
            <div x-show="!isLoading && cartItems.length > 0" class="divide-y divide-gray-200">
                <template x-for="item in cartItems" :key="item.id">
                    <div class="p-4 flex items-start space-x-3">
                        <!-- Product Image -->
                        <img :src="item.product_image" 
                             :alt="item.product_name"
                             class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate" x-text="item.product_name"></h4>
                            <p x-show="item.variant_details" 
                               x-text="item.variant_details" 
                               class="text-xs text-gray-500 mt-1"></p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-sm text-gray-600">
                                    Qty: <span x-text="item.quantity"></span>
                                </span>
                                <span class="text-sm font-medium text-gray-900" x-text="item.total_price"></span>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button @click="removeItem(item.id)" 
                                class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <div x-show="!isLoading && cartItems.length > 0" class="p-4 border-t border-gray-200">
            <!-- Subtotal -->
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-900">Subtotal:</span>
                <span class="text-lg font-semibold text-gray-900" x-text="cartSummary.subtotal_formatted"></span>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2">
                <a href="{{ route('cart.index') }}" 
                   class="w-full bg-gray-100 text-gray-900 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors text-center text-sm font-medium block">
                    View Cart
                </a>
                <a href="{{ route('checkout.index') }}" 
                   class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition-colors text-center text-sm font-medium block">
                    Checkout
                </a>
            </div>
        </div>
    </div>
</div>

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
                    
                    // Dispatch event for other components
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    
                    // Show success message
                    this.showMessage(data.message);
                } else {
                    this.showMessage(data.message, 'error');
                }
            } catch (error) {
                this.showMessage('Error removing item. Please try again.', 'error');
            }
        },

        showMessage(message, type = 'success') {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }
}
</script>