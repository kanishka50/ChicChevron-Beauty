<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $order = $this->route('order');
        $currentStatus = $order ? $order->status : null;

        // Define valid status transitions
        $validTransitions = [
            'payment_completed' => ['processing', 'cancelled'],
            'processing' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [], // Final state
            'cancelled' => [], // Final state
        ];

        $allowedStatuses = $validTransitions[$currentStatus] ?? [];

        return [
            'status' => [
                'required',
                Rule::in($allowedStatuses)
            ],
            'comment' => 'nullable|string|max:500',
            'notify_customer' => 'sometimes|boolean',
            'tracking_number' => 'nullable|string|max:100|required_if:status,shipping',
            'shipping_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Order status is required.',
            'status.in' => 'Invalid status transition. Please check the current order status.',
            'comment.max' => 'Comment cannot exceed 500 characters.',
            'tracking_number.required_if' => 'Tracking number is required when marking order as shipping.',
            'tracking_number.max' => 'Tracking number cannot exceed 100 characters.',
            'shipping_notes.max' => 'Shipping notes cannot exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'status' => 'order status',
            'comment' => 'status comment',
            'notify_customer' => 'customer notification',
            'tracking_number' => 'tracking number',
            'shipping_notes' => 'shipping notes',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            
            if ($order && $order->status === 'cancelled') {
                $validator->errors()->add('status', 'Cannot update status of a cancelled order.');
            }
            
            if ($order && $order->status === 'completed') {
                $validator->errors()->add('status', 'Cannot update status of a completed order.');
            }

            // Additional business logic validations
            if ($this->status === 'shipping' && $order) {
                // Check if all items have sufficient stock
                foreach ($order->items as $item) {
                    $inventory = $item->product->inventory()
                        ->where('variant_combination_id', $item->variant_combination_id)
                        ->first();
                    
                    if ($inventory && $inventory->current_stock < $item->quantity) {
                        $validator->errors()->add('status', 
                            "Insufficient stock for {$item->product_name}. Cannot ship order."
                        );
                        break;
                    }
                }
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            $response = response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
            
            throw new \Illuminate\Validation\ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and prepare data
        if ($this->has('comment')) {
            $this->merge([
                'comment' => trim($this->comment)
            ]);
        }

        if ($this->has('tracking_number')) {
            $this->merge([
                'tracking_number' => strtoupper(trim($this->tracking_number))
            ]);
        }

        // Set default notification preference
        if (!$this->has('notify_customer')) {
            $this->merge(['notify_customer' => true]);
        }
    }

    /**
     * Get validated data with additional processing
     */
    public function getProcessedData(): array
    {
        $data = $this->validated();
        
        // Add timestamp for status change
        $data['status_changed_at'] = now();
        
        // Add admin user ID
        $data['changed_by'] = auth()->guard('admin')->id();
        
        return $data;
    }

    /**
     * Check if customer should be notified
     */
    public function shouldNotifyCustomer(): bool
    {
        return $this->boolean('notify_customer', true);
    }

    /**
     * Get status transition message for customer notification
     */
    public function getCustomerNotificationMessage(): string
    {
        $messages = [
            'processing' => 'Your order is now being processed and will be prepared for shipping soon.',
            'shipping' => 'Great news! Your order has been shipped and is on its way to you.',
            'completed' => 'Your order has been completed. Thank you for shopping with us!',
            'cancelled' => 'Your order has been cancelled. If you have any questions, please contact our support team.',
        ];

        return $messages[$this->status] ?? 'Your order status has been updated.';
    }

    /**
     * Get admin notification message for internal logs
     */
    public function getAdminLogMessage(): string
    {
        $admin = auth()->guard('admin')->user();
        $adminName = $admin ? $admin->name : 'System';
        
        $message = "Status changed to '" . str_replace('_', ' ', $this->status) . "' by {$adminName}";
        
        if ($this->comment) {
            $message .= ". Comment: " . $this->comment;
        }
        
        return $message;
    }
}