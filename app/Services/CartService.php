<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Get cart items for current user/session
     */
    public function getCartItems()
    {
        $query = CartItem::with(['product.brand', 'product.category', 'productVariant'])
                          ->where($this->getCartIdentifier())
                          ->orderBy('created_at', 'desc');

        return $query->get();
    }

    /**
     * Get specific cart item
     */
    public function getCartItem($cartItemId)
    {
        return CartItem::where('id', $cartItemId)
                      ->where($this->getCartIdentifier())
                      ->with(['product', 'productVariant'])
                      ->first();
    }

    /**
     * Add product to cart
     */
    public function addToCart(Product $product, ProductVariant $productVariant = null, $quantity = 1)
    {
        $identifier = $this->getCartIdentifier();

        // For products with variants, variant is required
        if ($product->has_variants && !$productVariant) {
            throw new \Exception('Please select product options.');
        }

        // For products without variants
        if (!$product->has_variants && !$productVariant) {
            // Check if product has any variants at all
            if ($product->variants()->count() === 0) {
                // Handle products with no variants (shouldn't happen in normal flow)
                throw new \Exception('Product configuration error. Please contact support.');
            }

            // Get the default (standard) variant
            $productVariant = $product->defaultVariant();
            if (!$productVariant) {
                throw new \Exception('Product variant not found.');
            }
        }

        // Check if item already exists in cart
        $existingItem = CartItem::where($identifier)
                               ->where('product_id', $product->id)
                               ->where('product_variant_id', $productVariant->id)
                               ->first();

        if ($existingItem) {
            // Update quantity of existing item
            $newQuantity = $existingItem->quantity + $quantity;
            return $this->updateQuantity($existingItem->id, $newQuantity);
        }

        // Calculate price from variant
        $unitPrice = $productVariant->effective_price;

        // Create new cart item
        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::guest() ? Session::getId() : null,
            'product_id' => $product->id,
            'product_variant_id' => $productVariant->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);

        return $cartItem->load(['product', 'productVariant']);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::where('id', $cartItemId)
                           ->where($this->getCartIdentifier())
                           ->firstOrFail();

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->load(['product', 'productVariant']);
    }

    /**
     * Remove item from cart
     */
    public function removeItem($cartItemId)
    {
        return CartItem::where('id', $cartItemId)
                      ->where($this->getCartIdentifier())
                      ->delete();
    }

    /**
     * Clear entire cart
     * @param bool $silent - If true, suppress any events or redirects
     */
    public function clearCart($silent = false)
    {
        // Store the silent flag in session temporarily
        if ($silent) {
            Session::put('cart_clearing_silent', true);
        }

        $result = CartItem::where($this->getCartIdentifier())->delete();

        // Remove the silent flag after clearing
        if ($silent) {
            Session::forget('cart_clearing_silent');
        }

        return $result;
    }

    /**
     * Get available stock for product/variant
     */
    public function getAvailableStock($productId, $productVariantId = null)
    {
        if (!$productVariantId) {
            return 0;
        }

        // Inventory table only has product_variant_id, not product_id
        $inventory = Inventory::where('product_variant_id', $productVariantId)->first();

        if (!$inventory) {
            return 0;
        }

        // Use correct column names: stock_quantity and reserved_quantity
        return max(0, $inventory->stock_quantity - $inventory->reserved_quantity);
    }

    /**
     * Calculate cart summary with totals
     */
    public function getCartSummary()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $totalItems = $cartItems->sum('quantity');

        // Calculate shipping
        $shippingAmount = $this->calculateShipping($subtotal);

        $total = $subtotal + $shippingAmount;

        return [
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rs. ' . number_format($subtotal, 2),
            'discount_amount' => 0,
            'discount_formatted' => 'Rs. 0.00',
            'shipping_amount' => $shippingAmount,
            'shipping_formatted' => 'Rs. ' . number_format($shippingAmount, 2),
            'total' => $total,
            'total_formatted' => 'Rs. ' . number_format($total, 2),
        ];
    }

    /**
     * Calculate shipping cost
     */
    protected function calculateShipping($subtotal)
    {
        // Customize this logic based on your shipping rules
        if ($subtotal >= 5000) {
            return 0; // Free shipping over Rs. 5000
        }

        return 300; // Flat shipping rate
    }

    /**
     * Get cart identifier (user_id or session_id)
     */
    protected function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        return ['session_id' => Session::getId()];
    }

    /**
     * Merge guest cart with user cart after login
     */
    public function mergeGuestCart($sessionId, $userId)
    {
        $guestCartItems = CartItem::where('session_id', $sessionId)->get();

        foreach ($guestCartItems as $guestItem) {
            // Check if user already has this item in cart
            $existingItem = CartItem::where('user_id', $userId)
                                   ->where('product_id', $guestItem->product_id)
                                   ->where('product_variant_id', $guestItem->product_variant_id)
                                   ->first();

            if ($existingItem) {
                // Merge quantities
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity
                ]);
                $guestItem->delete();
            } else {
                // Transfer to user
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null
                ]);
            }
        }
    }

    /**
     * Validate cart items before checkout
     */
    public function validateCartForCheckout()
    {
        $cartItems = $this->getCartItems();
        $errors = [];

        if ($cartItems->isEmpty()) {
            $errors[] = 'Your cart is empty.';
            return $errors;
        }

        foreach ($cartItems as $item) {
            // Check if product is still active
            if (!$item->product->is_active) {
                $errors[] = "Product '{$item->product->name}' is no longer available.";
                continue;
            }

            // Check if variant is still active
            if (!$item->productVariant || !$item->productVariant->is_active) {
                $errors[] = "Selected variant for '{$item->product->name}' is no longer available.";
                continue;
            }

            // Check stock availability
            $availableStock = $this->getAvailableStock(
                $item->product_id,
                $item->product_variant_id
            );

            if ($availableStock < $item->quantity) {
                $variantDetails = $item->productVariant
                    ? " ({$item->productVariant->name})"
                    : '';

                $errors[] = "Only {$availableStock} units available for '{$item->product->name}{$variantDetails}'.";
            }
        }

        return $errors;
    }
}
