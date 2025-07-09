<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\VariantCombination;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cartItems = $this->cartService->getCartItems();
        $cartSummary = $this->cartService->getCartSummary();
        
        return view('cart.index', compact('cartItems', 'cartSummary'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_combination_id' => 'nullable|exists:variant_combinations,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            // Check if product is active
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is no longer available.'
                ], 400);
            }

            // Validate variant if product has variants
            $variantCombination = null;
            if ($product->has_variants) {
                if (!$request->variant_combination_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select product options.'
                    ], 400);
                }
                
                $variantCombination = VariantCombination::findOrFail($request->variant_combination_id);
                
                if ($variantCombination->product_id !== $product->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid product variant.'
                    ], 400);
                }
            }

            // Check stock availability
            $availableStock = $this->cartService->getAvailableStock($product->id, $request->variant_combination_id);
            
            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => $availableStock > 0 
                        ? "Only {$availableStock} items available in stock."
                        : 'This item is out of stock.'
                ], 400);
            }

            // Add to cart
            $cartItem = $this->cartService->addToCart(
                $product,
                $variantCombination,
                $request->quantity
            );

            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully!',
                'cart_count' => $cartSummary['total_items'],
                'cart_total' => $cartSummary['subtotal_formatted'],
                'item_total' => $cartItem->total_price_formatted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding item to cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $cartItem = $this->cartService->getCartItem($request->cart_item_id);
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found.'
                ], 404);
            }

            // Check stock availability for new quantity
            $availableStock = $this->cartService->getAvailableStock(
                $cartItem->product_id, 
                $cartItem->variant_combination_id
            );

            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => $availableStock > 0 
                        ? "Only {$availableStock} items available in stock."
                        : 'This item is out of stock.',
                    'max_quantity' => $availableStock
                ], 400);
            }

            // Update quantity
            $updatedItem = $this->cartService->updateQuantity($cartItem->id, $request->quantity);
            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => $updatedItem->total_price_formatted,
                'cart_count' => $cartSummary['total_items'],
                'cart_subtotal' => $cartSummary['subtotal_formatted']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer'
        ]);

        try {
            $cartItem = $this->cartService->getCartItem($request->cart_item_id);
            
            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found.'
                ], 404);
            }

            $this->cartService->removeItem($cartItem->id);
            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart.',
                'cart_count' => $cartSummary['total_items'],
                'cart_subtotal' => $cartSummary['subtotal_formatted']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing item. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            $this->cartService->clearCart();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Get cart count for header display
     */
    public function getCartCount()
    {
        $cartSummary = $this->cartService->getCartSummary();
        
        return response()->json([
            'count' => $cartSummary['total_items']
        ]);
    }

    /**
     * Get cart summary for dropdown
     */
    public function getCartSummary()
    {
        $cartItems = $this->cartService->getCartItems();
        $cartSummary = $this->cartService->getCartSummary();

        return response()->json([
            'success' => true,
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'variant_details' => $item->variant_details_formatted,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price_formatted,
                    'total_price' => $item->total_price_formatted,
                    'product_image' => $item->product->main_image 
                        ? asset('storage/' . $item->product->main_image) 
                        : '/placeholder.jpg'
                ];
            }),
            'summary' => $cartSummary
        ]);
    }

    /**
     * Apply promotion code
     */
    public function applyPromotion(Request $request)
    {
        $request->validate([
            'promotion_code' => 'required|string'
        ]);

        try {
            $result = $this->cartService->applyPromotion($request->promotion_code);

            if ($result['success']) {
                $cartSummary = $this->cartService->getCartSummary();
                
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'discount_amount' => $result['discount_amount'],
                    'cart_summary' => $cartSummary
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error applying promotion. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove applied promotion
     */
    public function removePromotion()
    {
        try {
            $this->cartService->removePromotion();
            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Promotion code removed.',
                'cart_summary' => $cartSummary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing promotion.'
            ], 500);
        }
    }
}