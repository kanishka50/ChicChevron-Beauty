<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\VariantCombination;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create order from cart items
     */
    public function createFromCart($user, $cartItems, $shippingData, $paymentMethod, $promotionCode = null)
    {
        DB::beginTransaction();
        
        try {
            // Calculate order totals
            $totals = $this->calculateOrderTotals($cartItems, $promotionCode);
            
            // Generate unique order number
            $orderNumber = Order::generateOrderNumber();
            
            // Create the order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status' => 'payment_completed',
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'shipping_amount' => $totals['shipping_amount'],
                'total_amount' => $totals['total_amount'],
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'completed',
                'shipping_name' => $shippingData['name'],
                'shipping_phone' => $shippingData['phone'],
                'shipping_address_line_1' => $shippingData['address_line_1'],
                'shipping_address_line_2' => $shippingData['address_line_2'] ?? null,
                'shipping_city' => $shippingData['city'],
                'shipping_district' => $shippingData['district'],
                'shipping_postal_code' => $shippingData['postal_code'] ?? null,
                'notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items and process inventory
            foreach ($cartItems as $cartItem) {
                $this->createOrderItem($order, $cartItem, $totals['promotion_discount_per_item'][$cartItem->id] ?? 0);
            }

            // Reserve stock for the order
            $this->reserveStockForOrder($order);

            // Add initial status history
            $order->addStatusHistory('payment_completed', 'Order created and payment completed');

            // Handle promotion usage if applicable
            if ($promotionCode && $totals['promotion']) {
                $this->recordPromotionUsage($order, $totals['promotion'], $user);
            }

            DB::commit();

            return [
                'success' => true,
                'order' => $order,
                'order_number' => $orderNumber
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'cart_items' => $cartItems->count(),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Create individual order item with inventory cost tracking
     */
    protected function createOrderItem($order, $cartItem, $discountPerItem = 0)
    {
        $product = $cartItem->product;
        $variantCombination = $cartItem->variantCombination;
        
        // Get the selling price (variant price or product price)
        $unitPrice = $variantCombination 
            ? $variantCombination->combination_price 
            : $product->selling_price;

        // Get FIFO cost price from inventory
        $costPrice = $this->getAverageCostPrice($product->id, $variantCombination?->id, $cartItem->quantity);

        // Prepare variant details for storage
        $variantDetails = null;
        if ($variantCombination) {
            $variantDetails = json_encode([
                'size' => $variantCombination->sizeVariant?->variant_value,
                'color' => $variantCombination->colorVariant?->variant_value,
                'scent' => $variantCombination->scentVariant?->variant_value,
                'combination_sku' => $variantCombination->combination_sku,
            ]);
        }

        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_combination_id' => $variantCombination?->id,
            'product_name' => $product->name,
            'product_sku' => $variantCombination ? $variantCombination->combination_sku : $product->sku,
            'variant_details' => $variantDetails,
            'quantity' => $cartItem->quantity,
            'unit_price' => $unitPrice,
            'cost_price' => $costPrice,
            'discount_amount' => $discountPerItem,
            'total_price' => ($unitPrice * $cartItem->quantity) - $discountPerItem,
        ]);
    }

    /**
     * Calculate order totals including promotions
     */
    public function calculateOrderTotals($cartItems, $promotionCode = null)
    {
        $subtotal = 0;
        $itemPrices = [];
        $promotion = null;
        $promotionDiscountPerItem = [];

        // Calculate subtotal
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            $variantCombination = $cartItem->variantCombination;
            
            $unitPrice = $variantCombination 
                ? $variantCombination->combination_price 
                : $product->selling_price;
                
            $itemTotal = $unitPrice * $cartItem->quantity;
            $subtotal += $itemTotal;
            $itemPrices[$cartItem->id] = $itemTotal;
        }

        // Apply promotion if provided
        $discountAmount = 0;
        if ($promotionCode) {
            $promotion = $this->validateAndGetPromotion($promotionCode);
            if ($promotion) {
                $discountAmount = ($subtotal * $promotion->discount_percentage) / 100;
                
                // Calculate discount per item (proportional)
                foreach ($cartItems as $cartItem) {
                    $itemDiscount = ($itemPrices[$cartItem->id] / $subtotal) * $discountAmount;
                    $promotionDiscountPerItem[$cartItem->id] = $itemDiscount;
                }
            }
        }

        // Calculate shipping (free for now, can be enhanced later)
        $shippingAmount = 0;

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_amount' => $shippingAmount,
            'total_amount' => $subtotal - $discountAmount + $shippingAmount,
            'promotion' => $promotion,
            'promotion_discount_per_item' => $promotionDiscountPerItem
        ];
    }

    /**
     * Reserve stock for all items in the order
     */
    public function reserveStockForOrder($order)
    {
        foreach ($order->items as $item) {
            $this->inventoryService->reserveStock(
                $item->product_id,
                $item->variant_combination_id,
                $item->quantity,
                'order',
                $order->id
            );
        }
    }

    /**
     * Confirm reserved stock (after successful payment)
     */
    public function confirmStockForOrder($order)
    {
        foreach ($order->items as $item) {
            $this->inventoryService->confirmReservedStock(
                $item->product_id,
                $item->variant_combination_id,
                $item->quantity,
                'order',
                $order->id
            );
        }
    }

    /**
     * Release reserved stock (on order cancellation)
     */
    public function releaseStockForOrder($order)
    {
        foreach ($order->items as $item) {
            $this->inventoryService->releaseReservedStock(
                $item->product_id,
                $item->variant_combination_id,
                $item->quantity,
                'order',
                $order->id
            );
        }
    }

    /**
     * Update order status with proper workflow validation
     */
    public function updateOrderStatus($order, $newStatus, $comment = null, $adminId = null)
    {
        $validTransitions = [
            'payment_completed' => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [], // Final state
            'cancelled' => [], // Final state
        ];

        $currentStatus = $order->status;

        // Validate status transition
        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            throw new \Exception("Invalid status transition from {$currentStatus} to {$newStatus}");
        }

        DB::beginTransaction();
        
        try {
            // Handle inventory changes based on status
            if ($newStatus === 'cancelled') {
                // Release reserved stock
                $this->releaseStockForOrder($order);
            } elseif ($newStatus === 'shipping' && $currentStatus === 'processing') {
                // Confirm stock deduction if not already done
                $this->confirmStockForOrder($order);
            }

            // Update order status
            $order->updateStatus($newStatus, $comment, $adminId);

            DB::commit();

            return [
                'success' => true,
                'order' => $order->fresh()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get average cost price using FIFO from inventory
     */
    protected function getAverageCostPrice($productId, $variantCombinationId, $quantity)
    {
        try {
            // Use your existing inventory service to get FIFO cost
            $stockDetails = $this->inventoryService->getStockDetails($productId, $variantCombinationId);
            
            if (empty($stockDetails)) {
                return 0;
            }

            // Calculate weighted average cost for the quantity needed
            $totalCost = 0;
            $remainingQuantity = $quantity;
            
            foreach ($stockDetails as $batch) {
                if ($remainingQuantity <= 0) break;
                
                $takeFromBatch = min($remainingQuantity, $batch['available_quantity']);
                $totalCost += $takeFromBatch * $batch['cost_per_unit'];
                $remainingQuantity -= $takeFromBatch;
            }
            
            return $quantity > 0 ? $totalCost / $quantity : 0;
            
        } catch (\Exception $e) {
            Log::warning('Could not calculate cost price for order item', [
                'product_id' => $productId,
                'variant_combination_id' => $variantCombinationId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Validate and get promotion details
     */
    protected function validateAndGetPromotion($promotionCode)
    {
        // This will be implemented when promotion system is ready
        // For now, return null
        return null;
    }

    /**
     * Record promotion usage
     */
    protected function recordPromotionUsage($order, $promotion, $user)
    {
        // This will be implemented when promotion system is ready
        // For now, do nothing
    }

    /**
     * Get order statistics for dashboard
     */
    public function getOrderStatistics($date = null)
    {
        $date = $date ?: now()->toDateString();
        
        return [
            'today_orders' => Order::whereDate('created_at', $date)->count(),
            'today_sales' => Order::whereDate('created_at', $date)
                                 ->where('status', '!=', 'cancelled')
                                 ->sum('total_amount'),
            'pending_orders' => Order::whereIn('status', ['payment_completed', 'processing'])->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
        ];
    }

    /**
     * Get orders with filters for admin panel
     */
    public function getOrdersWithFilters($filters = [])
    {
        $query = Order::with(['user', 'items.product', 'items.variantCombination'])
                     ->orderBy('created_at', 'desc');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    /**
     * Clear user's cart after successful order
     */
    public function clearUserCart($user)
    {
        CartItem::where('user_id', $user->id)->delete();
    }
}