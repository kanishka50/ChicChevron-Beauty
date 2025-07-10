<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Customer Information
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            
            // Delivery Address
            'delivery_address' => 'required|string|max:500',
            'delivery_city' => 'required|string|max:100',
            'delivery_postal_code' => 'required|string|max:10',
            'delivery_notes' => 'nullable|string|max:500',
            
            // Payment Method
            'payment_method' => 'required|in:cod,payhere',
            
            // Optional
            'order_notes' => 'nullable|string|max:500',
            'terms_accepted' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'customer_email.required' => 'Please enter your email address.',
            'customer_phone.required' => 'Please enter your phone number.',
            'delivery_address.required' => 'Please enter your delivery address.',
            'delivery_city.required' => 'Please enter your city.',
            'delivery_postal_code.required' => 'Please enter your postal code.',
            'payment_method.required' => 'Please select a payment method.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
        ];
    }
}