<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\VariantCombination;
use App\Models\Inventory;
use App\Models\Promotion;
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
        $query = CartItem::with(['product.brand', 'product.category', 'variantCombination'])
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
                      ->with(['product', 'variantCombination'])
                      ->first();
    }

    /**
     * Add product to cart
     */
    public function addToCart(Product $product, VariantCombination $variantCombination = null, $quantity = 1)
    {
        $identifier = $this->getCartIdentifier();
        
        // Check if item already exists in cart
        $existingItem = CartItem::where($identifier)
                               ->where('product_id', $product->id)
                               ->where('variant_combination_id', $variantCombination?->id)
                               ->first();

        if ($existingItem) {
            // Update quantity of existing item
            $newQuantity = $existingItem->quantity + $quantity;
            return $this->updateQuantity($existingItem->id, $newQuantity);
        }

        // Calculate price
        $unitPrice = $variantCombination 
            ? $variantCombination->effective_price 
            : $product->effective_price;

        // Create new cart item
        $cartItem = CartItem::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::guest() ? Session::getId() : null,
            'product_id' => $product->id,
            'variant_combination_id' => $variantCombination?->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
        ]);

        return $cartItem->load(['product', 'variantCombination']);
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
        
        return $cartItem->load(['product', 'variantCombination']);
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
    public function getAvailableStock($productId, $variantCombinationId = null)
    {
        $inventory = Inventory::where('product_id', $productId)
                             ->where('variant_combination_id', $variantCombinationId)
                             ->first();

        if (!$inventory) {
            return 0;
        }

        return max(0, $inventory->current_stock - $inventory->reserved_stock);
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
        
        // Get applied promotion if any
        $appliedPromotion = $this->getAppliedPromotion();
        $discountAmount = 0;
        
        if ($appliedPromotion) {
            $discountAmount = $this->calculatePromotionDiscount($cartItems, $appliedPromotion);
        }

        // Calculate shipping (you can customize this logic)
        $shippingAmount = $this->calculateShipping($subtotal);
        
        $total = $subtotal - $discountAmount + $shippingAmount;

        return [
            'total_items' => $totalItems,
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rs. ' . number_format($subtotal, 2),
            'discount_amount' => $discountAmount,
            'discount_formatted' => 'Rs. ' . number_format($discountAmount, 2),
            'shipping_amount' => $shippingAmount,
            'shipping_formatted' => 'Rs. ' . number_format($shippingAmount, 2),
            'total' => $total,
            'total_formatted' => 'Rs. ' . number_format($total, 2),
            'applied_promotion' => $appliedPromotion
        ];
    }

    /**
     * Apply promotion code
     */
    public function applyPromotion($promotionCode)
    {
        $promotion = Promotion::where('code', $promotionCode)
                             ->where('is_active', true)
                             ->where('start_date', '<=', now())
                             ->where('end_date', '>=', now())
                             ->first();

        if (!$promotion) {
            return [
                'success' => false,
                'message' => 'Invalid or expired promotion code.'
            ];
        }

        // Check usage limits
        if ($promotion->max_uses && $promotion->used_count >= $promotion->max_uses) {
            return [
                'success' => false,
                'message' => 'This promotion code has reached its usage limit.'
            ];
        }

        // Check user-specific usage limit
        if (Auth::check() && $promotion->max_uses_per_user) {
            $userUsageCount = $promotion->usages()
                                       ->where('user_id', Auth::id())
                                       ->count();
            
            if ($userUsageCount >= $promotion->max_uses_per_user) {
                return [
                    'success' => false,
                    'message' => 'You have already used this promotion code the maximum number of times.'
                ];
            }
        }

        // Check minimum order amount
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        if ($promotion->min_order_amount && $subtotal < $promotion->min_order_amount) {
            return [
                'success' => false,
                'message' => 'Minimum order amount of Rs. ' . number_format($promotion->min_order_amount, 2) . ' required for this promotion.'
            ];
        }

        // Store promotion in session
        Session::put('applied_promotion_code', $promotionCode);

        $discountAmount = $this->calculatePromotionDiscount($cartItems, $promotion);

        return [
            'success' => true,
            'message' => 'Promotion code applied successfully!',
            'discount_amount' => $discountAmount
        ];
    }

    /**
     * Remove applied promotion
     */
    public function removePromotion()
    {
        Session::forget('applied_promotion_code');
    }

    /**
     * Get currently applied promotion
     */
    protected function getAppliedPromotion()
    {
        $promotionCode = Session::get('applied_promotion_code');
        
        if (!$promotionCode) {
            return null;
        }

        return Promotion::where('code', $promotionCode)
                       ->where('is_active', true)
                       ->where('start_date', '<=', now())
                       ->where('end_date', '>=', now())
                       ->first();
    }

    /**
     * Calculate promotion discount
     */
    protected function calculatePromotionDiscount($cartItems, $promotion)
    {
        if ($promotion->type !== 'percentage') {
            return 0; // Only percentage discounts supported for now
        }

        $discountAmount = 0;

        foreach ($cartItems as $item) {
            // Check if product is eligible for this promotion
            $isEligible = $promotion->products->contains('id', $item->product_id);
            
            if ($isEligible) {
                $itemTotal = $item->quantity * $item->unit_price;
                $itemDiscount = ($itemTotal * $promotion->discount_value) / 100;
                
                // Apply maximum discount limit if set
                if ($promotion->max_discount_amount) {
                    $itemDiscount = min($itemDiscount, $promotion->max_discount_amount);
                }
                
                $discountAmount += $itemDiscount;
            }
        }

        return $discountAmount;
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
                                   ->where('variant_combination_id', $guestItem->variant_combination_id)
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

            // Check stock availability
            $availableStock = $this->getAvailableStock(
                $item->product_id, 
                $item->variant_combination_id
            );

            if ($availableStock < $item->quantity) {
                $variantDetails = $item->variant_combination 
                    ? " ({$item->variant_combination->combination_sku})"
                    : '';
                    
                $errors[] = "Only {$availableStock} units available for '{$item->product->name}{$variantDetails}'.";
            }
        }

        return $errors;
    }
}