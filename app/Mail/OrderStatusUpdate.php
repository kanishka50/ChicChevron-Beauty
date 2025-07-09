<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;
    public $comment;
    public $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $newStatus, $comment = null, $adminName = null)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
        $this->comment = $comment;
        $this->adminName = $adminName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusLabels = [
            'payment_completed' => 'Payment Confirmed',
            'processing' => 'Order Processing',
            'shipping' => 'Order Shipped',
            'completed' => 'Order Completed',
            'cancelled' => 'Order Cancelled',
        ];

        $subject = $statusLabels[$this->newStatus] ?? 'Order Status Update';
        $subject .= ' - ' . $this->order->order_number;

        return new Envelope(
            subject: $subject,
            from: config('mail.from.address', 'noreply@chicchevronbeauty.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status',
            with: [
                'order' => $this->order,
                'newStatus' => $this->newStatus,
                'comment' => $this->comment,
                'adminName' => $this->adminName,
                'statusInfo' => $this->getStatusInfo(),
                'customer' => [
                    'name' => $this->order->shipping_name,
                    'email' => $this->order->user->email ?? 'N/A'
                ],
                'tracking' => [
                    'order_url' => route('user.orders.show', $this->order),
                    'support_email' => config('mail.support.address', 'support@chicchevronbeauty.com'),
                    'support_phone' => config('company.support_phone', '+94 XX XXX XXXX')
                ],
                'nextSteps' => $this->getNextSteps()
            ]
        );
    }

    /**
     * Get status-specific information
     */
    protected function getStatusInfo()
    {
        $statusInfo = [
            'payment_completed' => [
                'title' => 'Payment Confirmed',
                'message' => 'Thank you! Your payment has been successfully processed.',
                'icon' => '💳',
                'color' => '#3B82F6',
                'description' => 'We have received your payment and your order is now confirmed. Our team will begin processing your order shortly.'
            ],
            'processing' => [
                'title' => 'Order Being Processed',
                'message' => 'Great news! We\'re now preparing your order.',
                'icon' => '📦',
                'color' => '#F59E0B',
                'description' => 'Our team is carefully preparing your items. We\'ll notify you once your order is ready for shipping.'
            ],
            'shipping' => [
                'title' => 'Order Shipped',
                'message' => 'Your order is on its way to you!',
                'icon' => '🚚',
                'color' => '#6366F1',
                'description' => 'Your order has been dispatched and is currently in transit. You should receive it within the estimated delivery time.'
            ],
            'completed' => [
                'title' => 'Order Completed',
                'message' => 'Thank you for your purchase!',
                'icon' => '✅',
                'color' => '#10B981',
                'description' => 'We hope you love your new beauty products! Please don\'t hesitate to leave a review and let us know about your experience.'
            ],
            'cancelled' => [
                'title' => 'Order Cancelled',
                'message' => 'Your order has been cancelled.',
                'icon' => '❌',
                'color' => '#EF4444',
                'description' => 'Your order has been cancelled as requested. If this was unexpected, please contact our support team immediately.'
            ]
        ];

        return $statusInfo[$this->newStatus] ?? [
            'title' => 'Order Status Updated',
            'message' => 'Your order status has been updated.',
            'icon' => '📋',
            'color' => '#6B7280',
            'description' => 'Your order status has been updated. Please check your account for more details.'
        ];
    }

    /**
     * Get next steps based on status
     */
    protected function getNextSteps()
    {
        $nextSteps = [
            'payment_completed' => [
                'We will begin processing your order within 24 hours.',
                'You\'ll receive another email when your order ships.',
                'Track your order anytime in your account.'
            ],
            'processing' => [
                'Your order is being carefully prepared by our team.',
                'We\'ll send you tracking information once it ships.',
                'Estimated processing time: 1-2 business days.'
            ],
            'shipping' => [
                'Your order is now in transit.',
                'Estimated delivery: 3-5 business days.',
                'You can track your package using the provided information.',
                'Please ensure someone is available to receive the delivery.'
            ],
            'completed' => [
                'We hope you enjoy your purchase!',
                'You can now leave a review for the products you bought.',
                'Keep your receipt for warranty purposes.',
                'Consider joining our loyalty program for exclusive offers.'
            ],
            'cancelled' => [
                'If you paid online, your refund will be processed within 5-7 business days.',
                'For Cash on Delivery orders, no payment was collected.',
                'You can place a new order anytime.',
                'Contact support if you have questions about the cancellation.'
            ]
        ];

        return $nextSteps[$this->newStatus] ?? [
            'Check your account for the latest order information.',
            'Contact support if you have any questions.'
        ];
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Build the message (Laravel 9 compatibility)
     */
    public function build()
    {
        $content = $this->content();
        
        return $this->subject($this->envelope()->subject)
                    ->from($this->envelope()->from[0]->address ?? config('mail.from.address'))
                    ->view($content->view, $content->with);
    }
}