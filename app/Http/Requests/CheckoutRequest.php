<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
    // Only authenticated users can checkout (no guest checkout)
    return Auth::check();
}

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Customer Information
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\-\.]+$/u' // Letters, spaces, hyphens, dots only
            ],
            
            'customer_phone' => [
                'required',
                'string',
                'regex:/^(?:\+94|0)?[0-9]{9,10}$/' // Sri Lankan phone format
            ],
            
            'customer_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Ensure email matches logged-in user's email
                    if ($value !== Auth::user()->email) {
                        $fail('The email must match your account email.');
                    }
                },
            ],
            
            // Delivery Address
            'delivery_address' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            
            'delivery_city' => [
                'required',
                'string',
                'max:100',
                'in:Colombo,Gampaha,Kalutara,Kandy,Matale,Nuwara Eliya,Galle,Matara,Hambantota,Jaffna,Kilinochchi,Mannar,Mullaitivu,Vavuniya,Puttalam,Kurunegala,Anuradhapura,Polonnaruwa,Badulla,Moneragala,Ratnapura,Kegalle,Batticaloa,Ampara,Trincomalee' // Major Sri Lankan cities
            ],
            
            'delivery_postal_code' => [
                'required',
                'string',
                'regex:/^[0-9]{5}$/' // Sri Lankan postal code format (5 digits)
            ],
            
            'delivery_notes' => [
                'nullable',
                'string',
                'max:500'
            ],
            
            // Payment Method
            'payment_method' => [
                'required',
                'string',
                'in:cod,payhere'
            ],
            
            // Order Notes
            'order_notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
            
            // Terms Acceptance
            'terms_accepted' => [
                'required',
                'accepted'
            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional validation for COD payment method
            if ($this->payment_method === 'cod') {
                $this->validateCODOrder($validator);
            }
            
            // Validate cart is not empty and items are available
            $this->validateCartContents($validator);
        });
    }

    /**
     * Validate COD order constraints
     */
    protected function validateCODOrder($validator)
    {
        $cartService = app(\App\Services\CartService::class);
        $cartSummary = $cartService->getCartSummary();
        
        // Check COD maximum limit (Rs. 10,000)
        if ($cartSummary['total'] > 10000) {
            $validator->errors()->add('payment_method', 
                'Cash on Delivery is only available for orders up to Rs. 10,000. Your order total is ' . 
                $cartSummary['total_formatted'] . '. Please choose online payment.'
            );
        }
    }

    /**
     * Validate cart contents are valid for checkout
     */
    protected function validateCartContents($validator)
    {
        $cartService = app(\App\Services\CartService::class);
        
        // Check if cart is empty
        $cartItems = $cartService->getCartItems();
        if ($cartItems->isEmpty()) {
            $validator->errors()->add('cart', 'Your cart is empty. Please add items before checkout.');
        }
        
        // Validate cart items for checkout
        $validationErrors = $cartService->validateCartForCheckout();
        if (!empty($validationErrors)) {
            foreach ($validationErrors as $error) {
                $validator->errors()->add('cart', $error);
            }
        }
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.regex' => 'Name can only contain letters, spaces, hyphens, and dots.',
            
            'customer_phone.required' => 'Please enter your phone number.',
            'customer_phone.regex' => 'Please enter a valid Sri Lankan phone number (e.g., 0771234567 or +94771234567).',
            
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Please enter a valid email address.',
            
            'delivery_address.required' => 'Please enter your delivery address.',
            'delivery_address.min' => 'Please enter a complete delivery address (at least 10 characters).',
            
            'delivery_city.required' => 'Please select your city.',
            'delivery_city.in' => 'Please select a valid city from the list.',
            
            'delivery_postal_code.required' => 'Please enter your postal code.',
            'delivery_postal_code.regex' => 'Please enter a valid 5-digit postal code.',
            
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Please select a valid payment method.',
            
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions to proceed.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Clean phone number - remove spaces and special characters except +
        if ($this->has('customer_phone')) {
            $phone = preg_replace('/[^0-9+]/', '', $this->customer_phone);
            $this->merge(['customer_phone' => $phone]);
        }
        
        // Trim all string inputs
        $fieldsToTrim = [
            'customer_name', 'customer_email', 'delivery_address', 
            'delivery_city', 'delivery_postal_code', 'delivery_notes', 'order_notes'
        ];
        
        foreach ($fieldsToTrim as $field) {
            if ($this->has($field)) {
                $this->merge([$field => trim($this->$field)]);
            }
        }
    }
}