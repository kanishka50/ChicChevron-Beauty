<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100'
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

            // All products now have variants, so we always need a variant selection
            $productVariant = null;
            if (!$request->product_variant_id) {
                // If no variant specified, check if product has only one variant (default)
                if ($product->variants()->count() === 1) {
                    $productVariant = $product->defaultVariant();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select product options.'
                    ], 400);
                }
            } else {
                $productVariant = ProductVariant::findOrFail($request->product_variant_id);
                
                // Validate variant belongs to product
                if ($productVariant->product_id !== $product->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid product variant.'
                    ], 400);
                }
                
                // Check if variant is active
                if (!$productVariant->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected variant is not available.'
                    ], 400);
                }
            }

            // Check stock availability
            $availableStock = $this->cartService->getAvailableStock($product->id, $productVariant->id);
            
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
                $productVariant,
                $request->quantity
            );

            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully!',
                'cart_count' => $cartSummary['total_items'],
                'cart_total' => $cartSummary['total_formatted'],
                'item_total' => $cartItem->total_price_formatted
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding item to cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Update cart item quantity - UNIFIED ENDPOINT
     * This handles both /cart/update and /cart/update-quantity routes
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:100'
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
                $cartItem->product_variant_id
            );

            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => $availableStock > 0 
                        ? "Only {$availableStock} items available in stock."
                        : 'This item is out of stock.',
                    'max_quantity' => $availableStock,
                    'previous_quantity' => $cartItem->quantity
                ], 400);
            }

            // Update quantity
            $updatedItem = $this->cartService->updateQuantity($cartItem->id, $request->quantity);
            $cartSummary = $this->cartService->getCartSummary();

            // Return consistent response structure for all frontends
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => $updatedItem->total_price_formatted,
                'cart_count' => $cartSummary['total_items'],
                'cart_subtotal' => $cartSummary['subtotal_formatted'],
                'cart_total' => $cartSummary['total_formatted'],
                'cart_shipping' => $cartSummary['shipping_formatted'],
                'cart_discount' => $cartSummary['discount_formatted'],
                // Include summary object for dropdown
                'summary' => $cartSummary
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
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
                'cart_subtotal' => $cartSummary['subtotal_formatted'],
                'cart_total' => $cartSummary['total_formatted'],
                'cart_shipping' => $cartSummary['shipping_formatted'],
                'cart_discount' => $cartSummary['discount_formatted'],
                // Include summary object for dropdown
                'summary' => $cartSummary
            ]);

        } catch (\Exception $e) {
            Log::error('Error removing item: ' . $e->getMessage());
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
            Log::error('Error clearing cart: ' . $e->getMessage());
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
        try {
            $cartSummary = $this->cartService->getCartSummary();
            
            return response()->json([
                'count' => $cartSummary['total_items']
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart count: ' . $e->getMessage());
            return response()->json([
                'count' => 0
            ]);
        }
    }

    /**
     * Get cart summary for dropdown
     */
    public function getCartSummary()
    {
        try {
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
                        'product_image' => $item->product_image,
                        'product_url' => route('products.show', $item->product->slug),
                        'is_available' => $item->is_available
                    ];
                }),
                'summary' => $cartSummary
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting cart summary: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading cart data.'
            ], 500);
        }
    }

    /**
     * Quick add to cart (for single variant products or with variant ID)
     */
    public function quickAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is no longer available.'
                ], 400);
            }

            // For quick add, if no variant specified and product has single variant
            $productVariant = null;
            if (!$request->product_variant_id) {
                if ($product->variants()->count() === 1) {
                    $productVariant = $product->defaultVariant();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select product options from the product page.'
                    ], 400);
                }
            } else {
                $productVariant = ProductVariant::findOrFail($request->product_variant_id);
            }

            if (!$productVariant || !$productVariant->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected variant is not available.'
                ], 400);
            }

            // Check stock
            $availableStock = $this->cartService->getAvailableStock($product->id, $productVariant->id);
            
            if ($availableStock < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'This item is out of stock.'
                ], 400);
            }

            // Add single quantity
            $cartItem = $this->cartService->addToCart($product, $productVariant, 1);
            $cartSummary = $this->cartService->getCartSummary();

            return response()->json([
                'success' => true,
                'message' => 'Added to cart!',
                'cart_count' => $cartSummary['total_items'],
                'cart_total' => $cartSummary['total_formatted']
            ]);

        } catch (\Exception $e) {
            Log::error('Error in quick add: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart.'
            ], 500);
        }
    }
}