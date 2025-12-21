<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        // Admins can view all orders, customers can view their own
        return ($user->is_admin ?? false) || true;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // Admins can view any order, customers can only view their own
        if ($user->is_admin ?? false) {
            return true;
        }
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create orders
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        // Admins can update any order
        if ($user->is_admin ?? false) {
            return true;
        }
        // Customers can only update their own orders in certain states
        return $user->id === $order->user_id &&
               in_array($order->status, ['payment_completed', 'processing', 'shipping']);
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order)
    {
        return false; // Users cannot delete orders, only cancel them
    }

    /**
     * Determine whether the user can cancel the order.
     */
    public function cancel(User $user, Order $order)
    {
        return $user->id === $order->user_id && 
               $order->can_be_cancelled;
    }

    /**
     * Determine whether the user can mark the order as completed.
     */
    public function complete(User $user, Order $order)
    {
        return $user->id === $order->user_id && 
               $order->status === 'shipping';
    }

    /**
     * Determine whether the user can download the order invoice.
     */
    public function downloadInvoice(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can reorder rows in the table.
     * Note: This is Filament's table reordering permission, not "re-order" an order.
     * When called without a model, it's a table-level check.
     */
    public function reorder(User $user, ?Order $order = null): bool
    {
        // If no order provided, this is a table-level reorder check (Filament)
        // Only admins can reorder the table
        if ($order === null) {
            return $user->is_admin ?? false;
        }

        // If order provided, this is checking if user can "re-order" (place same order again)
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can track the order.
     */
    public function track(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can leave reviews for this order.
     */
    public function review(User $user, Order $order)
    {
        return $user->id === $order->user_id && 
               $order->status === 'completed';
    }
}