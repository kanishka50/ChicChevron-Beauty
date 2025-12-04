<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderStatusUpdate;
use Illuminate\Support\Facades\Mail;

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
    public function createFromCart($user, $cartItems, $shippingData, $promotionCode = null)
    {
        DB::beginTransaction();
        
        try {
            // Calculate order totals
            $totals = $this->calculateOrderTotals($cartItems, $promotionCode);
            
            // Generate unique order number
            $orderNumber = Order::generateOrderNumber();
            
            // Create the order (COD only - starts as processing)
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status' => 'processing',
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'shipping_amount' => $totals['shipping_amount'],
                'total_amount' => $totals['total_amount'],
                'payment_method' => 'cod',
                'payment_status' => 'pending',
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
            $order->addStatusHistory('processing', 'Order created - Cash on Delivery');

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
     * Create order for CheckoutController (simpler format)
     */
    public function createOrder(array $orderData, $cartItems)
    {
        DB::beginTransaction();
        
        try {
            // Generate unique order number if not provided
            if (!isset($orderData['order_number'])) {
                $orderData['order_number'] = $this->generateOrderNumber();
            }

            // Calculate totals from cart items
            $totals = $this->calculateSimpleOrderTotals($cartItems);
            
            // Merge totals with order data
            $orderData = array_merge($orderData, [
                'subtotal' => $totals['subtotal'],
                'total_amount' => $totals['total_amount'],
                'shipping_amount' => $totals['shipping_amount'],
                'discount_amount' => $totals['discount_amount'],
            ]);

            // Map checkout form fields to your database structure
            $mappedOrderData = $this->mapCheckoutDataToOrderFormat($orderData);

            // Create the order
            $order = Order::create($mappedOrderData);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $this->createOrderItemFromCart($order, $cartItem);
            }

            // Reserve stock for the order
            $this->reserveStockForOrder($order);

            // Add initial status history
            if (method_exists($order, 'addStatusHistory')) {
                $order->addStatusHistory($order->status, 'Order created via checkout');
            }

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout order creation failed: ' . $e->getMessage(), [
                'order_data' => $orderData,
                'cart_items' => $cartItems->count(),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Map checkout form data to your order database structure
     */
    protected function mapCheckoutDataToOrderFormat(array $checkoutData)
    {
        return [
            'order_number' => $checkoutData['order_number'],
            'user_id' => $checkoutData['user_id'],
            'customer_email' => $checkoutData['customer_email'],
            'status' => $checkoutData['status'],
            'payment_method' => $checkoutData['payment_method'],
            'payment_status' => $checkoutData['payment_status'],
            
            // Map to shipping fields in database
            'shipping_name' => $checkoutData['customer_name'],
            'shipping_phone' => $checkoutData['customer_phone'],
            'shipping_address_line_1' => $checkoutData['address_line_1'] ?? $checkoutData['delivery_address'],
            'shipping_address_line_2' => $checkoutData['address_line_2'] ?? null,
            'shipping_city' => $checkoutData['city'] ?? $checkoutData['delivery_city'],
            'shipping_district' => $checkoutData['district'] ?? $checkoutData['delivery_city'],
            'shipping_postal_code' => $checkoutData['postal_code'] ?? $checkoutData['delivery_postal_code'],
            
            // Totals
            'subtotal' => $checkoutData['subtotal'],
            'discount_amount' => $checkoutData['discount_amount'],
            'shipping_amount' => $checkoutData['shipping_amount'],
            'total_amount' => $checkoutData['total_amount'],
            
            // Notes for delivery
            'notes' => $checkoutData['delivery_notes'] ?? null,
        ];
    }

    /**
     * Simple totals calculation for checkout (without complex promotions)
     */
    protected function calculateSimpleOrderTotals($cartItems)
    {
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->quantity * $cartItem->unit_price;
        }

        // Simple shipping calculation
        $shippingAmount = $subtotal >= 5000 ? 0 : 300; // Free shipping over Rs. 5000

        return [
            'subtotal' => $subtotal,
            'discount_amount' => 0, // No promotions in simple checkout
            'shipping_amount' => $shippingAmount,
            'total_amount' => $subtotal + $shippingAmount,
        ];
    }

    /**
     * Simple order item creation for checkout
     */
    protected function createOrderItemFromCart($order, $cartItem)
    {
        $product = $cartItem->product;
        $productVariant = $cartItem->productVariant;
        
        // Get cost price using your existing method
        $costPrice = $this->getAverageCostPrice($product->id, $productVariant?->id, $cartItem->quantity);

        // Prepare variant details for storage
        $variantDetails = null;
        if ($productVariant) {
            $variantDetails = json_encode([
                'size' => $productVariant->size,
                'color' => $productVariant->color,
                'scent' => $productVariant->scent,
                'name' => $productVariant->name,
                'sku' => $productVariant->sku,
            ]);
        }

        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $productVariant?->id,
            'product_name' => $product->name,
            'product_sku' => $productVariant ? $productVariant->sku : $product->sku,
            'variant_details' => $variantDetails,
            'quantity' => $cartItem->quantity,
            'unit_price' => $cartItem->unit_price,
            'cost_price' => $costPrice,
            'discount_amount' => 0, // No item-level discounts in simple checkout
            'total_price' => $cartItem->quantity * $cartItem->unit_price,
        ]);
    }

    /**
     * Generate simple order number
     */
    public function generateOrderNumber()
    {
        return 'CHB-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create individual order item with inventory cost tracking
     */
    protected function createOrderItem($order, $cartItem, $discountPerItem = 0)
    {
        $product = $cartItem->product;
        $productVariant = $cartItem->productVariant;
        
        if (!$productVariant) {
            throw new \Exception('Invalid order item - missing variant information.');
        }
        
        $unitPrice = $productVariant->effective_price;

        // Get FIFO cost price from inventory
        $costPrice = $this->getAverageCostPrice($product->id, $productVariant->id, $cartItem->quantity);

        // Prepare variant details for storage
        $variantDetails = json_encode([
            'size' => $productVariant->size,
            'color' => $productVariant->color,
            'scent' => $productVariant->scent,
            'name' => $productVariant->name,
            'sku' => $productVariant->sku,
        ]);

        return OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $productVariant->id,
            'product_name' => $product->name,
            'product_sku' => $productVariant->sku,
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
            $productVariant = $cartItem->productVariant;
            
            if (!$productVariant) {
                throw new \Exception('Invalid cart item - missing variant information.');
            }
            
            $unitPrice = $productVariant->effective_price;
            
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

        // Calculate shipping
        $shippingAmount = $subtotal >= 5000 ? 0 : 300;

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
                $item->product_variant_id,
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
                $item->product_variant_id,
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
                $item->product_variant_id,
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
        // COD-only status transitions
        $validTransitions = [
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
            // Handle COD payment completion when order is marked as completed
            if ($newStatus === 'completed' && $order->payment_status === 'pending') {
                $order->payment_status = 'completed';
                $order->payment_reference = 'COD-' . now()->timestamp;
                $order->save();
            }

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

            // Send email notification
            if ($order->user && $order->user->email) {
                try {
                    // Get admin name if available
                    $adminName = null;
                    if ($adminId) {
                        $admin = \App\Models\Admin::find($adminId);
                        $adminName = $admin ? $admin->name : 'Support Team';
                    }

                    // Send the email
                    Mail::to($order->user->email)
                        ->send(new OrderStatusUpdate($order, $newStatus, $comment, $adminName));
                    
                    Log::info('Order status email sent successfully', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $newStatus,
                        'email' => $order->user->email
                    ]);
                } catch (\Exception $emailException) {
                    // Log email error but don't fail the whole transaction
                    Log::error('Failed to send order status email', [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'error' => $emailException->getMessage()
                    ]);
                    
                    // Add a note about email failure to order history
                    $order->addStatusHistory(
                        $newStatus,
                        'Note: Email notification failed to send. Error: ' . $emailException->getMessage(),
                        $adminId
                    );
                }
            } else {
                Log::warning('No email sent - user email not available', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]);
            }

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
    protected function getAverageCostPrice($productId, $productVariantId, $quantity)
    {
        try {
            // Use inventory service to get FIFO cost
            $stockDetails = $this->inventoryService->getStockDetails($productId, $productVariantId);
            
            if (empty($stockDetails['batches'])) {
                return 0;
            }

            // Calculate weighted average cost for the quantity needed
            $totalCost = 0;
            $remainingQuantity = $quantity;
            
            foreach ($stockDetails['batches'] as $batch) {
                if ($remainingQuantity <= 0) break;
                
                $takeFromBatch = min($remainingQuantity, $batch->available_quantity);
                $totalCost += $takeFromBatch * $batch->cost_per_unit;
                $remainingQuantity -= $takeFromBatch;
            }
            
            return $quantity > 0 ? $totalCost / $quantity : 0;
            
        } catch (\Exception $e) {
            Log::warning('Could not calculate cost price for order item', [
                'product_id' => $productId,
                'product_variant_id' => $productVariantId,
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
            'pending_orders' => Order::where('status', 'processing')->count(),
            'shipping_orders' => Order::where('status', 'shipping')->count(),
        ];
    }

    /**
     * Get orders with filters for admin panel
     */
    public function getOrdersWithFilters($filters = [])
    {
        $query = Order::with(['user', 'items.product', 'items.productVariant'])
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