<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - ' . $this->order->order_number,
            from: config('mail.from.address', 'noreply@chicchevronbeauty.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'items' => $this->order->items->load(['product.brand', 'productVariant']), // UPDATED
                'customer' => [
                    'name' => $this->order->shipping_name,
                    'email' => $this->order->user->email ?? 'N/A',
                    'phone' => $this->order->shipping_phone,
                    'address' => $this->order->full_shipping_address
                ],
                'payment' => [
                    'method' => strtoupper($this->order->payment_method),
                    'status' => ucfirst($this->order->payment_status),
                    'reference' => $this->order->payment_reference,
                    'amount' => $this->order->total_amount
                ],
                'tracking' => [
                    'order_url' => route('user.orders.show', $this->order),
                    'support_email' => config('mail.support.address', 'support@chicchevronbeauty.com'),
                    'support_phone' => config('company.support_phone', '+94 XX XXX XXXX')
                ]
            ]
        );
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
                    ->from(config('mail.from.address'))
                    ->view($content->view, $content->with);
    }
}
