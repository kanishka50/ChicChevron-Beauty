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
    public function viewAny(User $user)
    {
        return true; // Users can view their own orders
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order)
    {
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
    public function update(User $user, Order $order)
    {
        // Users can only update their own orders and only in certain states
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
     * Determine whether the user can reorder this order.
     */
    public function reorder(User $user, Order $order)
    {
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