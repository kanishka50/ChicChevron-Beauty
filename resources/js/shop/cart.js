/**
 * Cart functionality for ChicChevron Beauty
 * Handle all cart-related operations and UI updates
 */

class CartManager {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        this.init();
    }

    init() {
        // Bind event listeners
        this.bindEvents();
        
        // Load initial cart count
        this.updateCartCounter();
        
        // Listen for cart updates from other components
        window.addEventListener('cart-updated', () => {
            this.updateCartCounter();
        });
    }

    bindEvents() {
        // Add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart-btn') || e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                const button = e.target.classList.contains('add-to-cart-btn') ? e.target : e.target.closest('.add-to-cart-btn');
                const productId = button.dataset.productId;
                const variantId = button.dataset.variantId || null;
                const quantity = parseInt(button.dataset.quantity) || 1;
                
                this.addToCart(productId, variantId, quantity, button);
            }
        });

        // Quick add to cart from product listings
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quick-add-btn')) {
                e.preventDefault();
                const productId = e.target.dataset.productId;
                this.addToCart(productId, null, 1, e.target);
            }
        });
    }

    /**
     * Add product to cart
     */
    async addToCart(productId, variantId = null, quantity = 1, buttonElement = null) {
        // Validate inputs
        if (!productId) {
            this.showToast('Product ID is required', 'error');
            return;
        }

        // Show loading state on button
        const originalButtonState = this.setButtonLoading(buttonElement);

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    product_id: parseInt(productId),
                    variant_combination_id: variantId ? parseInt(variantId) : null,
                    quantity: parseInt(quantity)
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update cart counter
                this.updateCartCounter(data.cart_count);
                
                // Show success state on button
                this.setButtonSuccess(buttonElement, originalButtonState);
                
                // Dispatch cart updated event
                window.dispatchEvent(new CustomEvent('cart-updated', {
                    detail: { 
                        action: 'add',
                        productId: productId,
                        cartCount: data.cart_count 
                    }
                }));
                
                // Show success message
                this.showToast(data.message, 'success');
                
            } else {
                // Show error state on button
                this.setButtonError(buttonElement, originalButtonState);
                
                // Show error message
                this.showToast(data.message, 'error');
            }

        } catch (error) {
            console.error('Error adding to cart:', error);
            
            // Show error state on button
            this.setButtonError(buttonElement, originalButtonState);
            
            // Show error message
            this.showToast('Error adding item to cart. Please try again.', 'error');
        }
    }

    /**
     * Update cart item quantity
     */
    async updateQuantity(cartItemId, quantity) {
        if (quantity < 1) {
            this.showToast('Quantity must be at least 1', 'error');
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    cart_item_id: parseInt(cartItemId),
                    quantity: parseInt(quantity)
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update UI elements
                this.updateCartItemTotal(cartItemId, data.item_total);
                this.updateCartSummary(data.cart_count, data.cart_subtotal);
                
                // Dispatch event
                window.dispatchEvent(new CustomEvent('cart-updated', {
                    detail: { 
                        action: 'update',
                        cartItemId: cartItemId,
                        quantity: quantity 
                    }
                }));
                
                this.showToast(data.message, 'success');
            } else {
                this.showToast(data.message, 'error');
                
                // Reset quantity input to previous value
                const quantityInput = document.getElementById(`quantity-${cartItemId}`);
                if (quantityInput) {
                    quantityInput.value = quantityInput.dataset.previousValue || 1;
                }
            }

        } catch (error) {
            console.error('Error updating quantity:', error);
            this.showToast('Error updating cart. Please try again.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Remove item from cart
     */
    async removeItem(cartItemId) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    cart_item_id: parseInt(cartItemId)
                })
            });

            const data = await response.json();

            if (data.success) {
                // Remove item from DOM
                const itemElement = document.getElementById(`cart-item-${cartItemId}`);
                if (itemElement) {
                    itemElement.style.transition = 'opacity 0.3s ease-out';
                    itemElement.style.opacity = '0';
                    setTimeout(() => {
                        itemElement.remove();
                        
                        // Check if cart is now empty
                        const remainingItems = document.querySelectorAll('[id^="cart-item-"]');
                        if (remainingItems.length === 0) {
                            this.showEmptyCartMessage();
                        }
                    }, 300);
                }
                
                // Update cart counter
                this.updateCartCounter(data.cart_count);
                
                // Dispatch event
                window.dispatchEvent(new CustomEvent('cart-updated', {
                    detail: { 
                        action: 'remove',
                        cartItemId: cartItemId 
                    }
                }));
                
                this.showToast(data.message, 'success');
            } else {
                this.showToast(data.message, 'error');
            }

        } catch (error) {
            console.error('Error removing item:', error);
            this.showToast('Error removing item. Please try again.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart() {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                // Redirect to show empty cart
                window.location.reload();
            } else {
                this.showToast('Error clearing cart. Please try again.', 'error');
            }

        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showToast('Error clearing cart. Please try again.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Apply promotion code
     */
    async applyPromotion(promotionCode) {
        if (!promotionCode?.trim()) {
            this.showToast('Please enter a promotion code.', 'error');
            return;
        }

        this.showLoading();

        try {
            const response = await fetch('/cart/apply-promotion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    promotion_code: promotionCode.trim()
                })
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                // Reload page to show updated cart with promotion
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showToast(data.message, 'error');
            }

        } catch (error) {
            console.error('Error applying promotion:', error);
            this.showToast('Error applying promotion. Please try again.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Remove applied promotion
     */
    async removePromotion() {
        this.showLoading();

        try {
            const response = await fetch('/cart/remove-promotion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showToast(data.message, 'success');
                // Reload page to show updated cart without promotion
                setTimeout(() => window.location.reload(), 1000);
            } else {
                this.showToast('Error removing promotion.', 'error');
            }

        } catch (error) {
            console.error('Error removing promotion:', error);
            this.showToast('Error removing promotion.', 'error');
        } finally {
            this.hideLoading();
        }
    }

    /**
     * Update cart counter in header
     */
    async updateCartCounter(count = null) {
        if (count === null) {
            try {
                const response = await fetch('/cart/count');
                const data = await response.json();
                count = data.count;
            } catch (error) {
                console.error('Error getting cart count:', error);
                return;
            }
        }

        // Update all cart counter elements
        const cartCounters = document.querySelectorAll('.cart-count, [data-cart-count]');
        cartCounters.forEach(counter => {
            counter.textContent = count;
            counter.style.display = count > 0 ? 'flex' : 'none';
        });

        // Update cart badge in Alpine.js components
        if (window.Alpine) {
            window.Alpine.store('cart', { count: count });
        }
    }

    /**
     * UI Helper Methods
     */
    setButtonLoading(button) {
        if (!button) return null;

        const originalState = {
            text: button.textContent,
            disabled: button.disabled,
            innerHTML: button.innerHTML,
            classList: [...button.classList]
        };

        button.disabled = true;
        button.innerHTML = `
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Adding...
            </div>
        `;

        return originalState;
    }

    setButtonSuccess(button, originalState) {
        if (!button || !originalState) return;

        button.textContent = 'Added!';
        button.classList.remove('bg-pink-600', 'hover:bg-pink-700');
        button.classList.add('bg-green-600');

        setTimeout(() => {
            button.textContent = originalState.text;
            button.disabled = originalState.disabled;
            button.className = originalState.classList.join(' ');
        }, 2000);
    }

    setButtonError(button, originalState) {
        if (!button || !originalState) return;

        button.textContent = 'Error';
        button.classList.remove('bg-pink-600');
        button.classList.add('bg-red-600');

        setTimeout(() => {
            button.textContent = originalState.text;
            button.disabled = originalState.disabled;
            button.className = originalState.classList.join(' ');
        }, 2000);
    }

    updateCartItemTotal(cartItemId, newTotal) {
        const totalElement = document.getElementById(`item-total-${cartItemId}`);
        if (totalElement) {
            totalElement.textContent = newTotal;
        }
    }

    updateCartSummary(itemCount, subtotal) {
        const subtotalElement = document.getElementById('cart-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = subtotal;
        }

        this.updateCartCounter(itemCount);
    }

    showEmptyCartMessage() {
        const cartContainer = document.querySelector('.cart-items-container');
        if (cartContainer) {
            cartContainer.innerHTML = `
                <div class="text-center py-16">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                    <p class="mt-2 text-gray-500">Start shopping to add items to your cart.</p>
                    <div class="mt-6">
                        <a href="/products" class="inline-flex items-center px-6 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                            Start Shopping
                        </a>
                    </div>
                </div>
            `;
        }
    }

    showLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.remove('hidden');
        }
    }

    hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }

    showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 transform translate-y-full transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        } text-white max-w-sm`;
        
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Trigger slide-in animation
        setTimeout(() => {
            toast.classList.remove('translate-y-full');
        }, 100);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('translate-y-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
}

// Global cart functions for backward compatibility
window.cartManager = new CartManager();

// Export functions globally
window.addToCart = (productId, variantId, quantity) => {
    window.cartManager.addToCart(productId, variantId, quantity);
};

window.updateQuantity = (cartItemId, quantity) => {
    window.cartManager.updateQuantity(cartItemId, quantity);
};

window.removeItem = (cartItemId) => {
    window.cartManager.removeItem(cartItemId);
};

window.clearCart = () => {
    window.cartManager.clearCart();
};

window.applyPromotion = () => {
    const promotionCode = document.getElementById('promotion-code')?.value;
    window.cartManager.applyPromotion(promotionCode);
};

window.removePromotion = () => {
    window.cartManager.removePromotion();
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    if (!window.cartManager) {
        window.cartManager = new CartManager();
    }
});