<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddress;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $rules = [
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
            
            // Saved address option
            'saved_address_id' => [
                'nullable',
                'exists:user_addresses,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $address = UserAddress::where('id', $value)
                            ->where('user_id', Auth::id())
                            ->where('is_active', true)
                            ->first();
                        
                        if (!$address) {
                            $fail('The selected address is invalid.');
                        }
                    }
                },
            ],
            
            'save_address' => [
                'nullable',
                'boolean'
            ],
        ];

        // If NOT using saved address, require all address fields
        if (!$this->saved_address_id) {
            $rules = array_merge($rules, [
                // Customer Information
                'customer_name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\pL\s\-\.]+$/u'
                ],
                
                'customer_phone' => [
                    'required',
                    'string',
                    'regex:/^(?:\+94|0)?[0-9]{9,10}$/'
                ],
                
                'customer_email' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if ($value !== Auth::user()->email) {
                            $fail('The email must match your account email.');
                        }
                    },
                ],
                
                // Address fields - matching your profile address form
                'address_line_1' => [
                    'required',
                    'string',
                    'max:255'
                ],
                
                'address_line_2' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                
                'city' => [
                    'required',
                    'string',
                    'max:100',
                    'in:Colombo,Gampaha,Kalutara,Kandy,Matale,Nuwara Eliya,Galle,Matara,Hambantota,Jaffna,Kilinochchi,Mannar,Mullaitivu,Vavuniya,Puttalam,Kurunegala,Anuradhapura,Polonnaruwa,Badulla,Moneragala,Ratnapura,Kegalle,Batticaloa,Ampara,Trincomalee'
                ],
                
                'district' => [
                    'required',
                    'string',
                    'max:100',
                    'in:Colombo,Gampaha,Kalutara,Kandy,Matale,Nuwara Eliya,Galle,Matara,Hambantota,Jaffna,Kilinochchi,Mannar,Mullaitivu,Vavuniya,Puttalam,Kurunegala,Anuradhapura,Polonnaruwa,Badulla,Moneragala,Ratnapura,Kegalle,Batticaloa,Ampara,Trincomalee'
                ],
                
                'postal_code' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{5}$/'
                ],
                
                'delivery_notes' => [
                    'nullable',
                    'string',
                    'max:500'
                ],
            ]);
        }

        return $rules;
    }

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

    // ... keep your existing validateCODOrder and validateCartContents methods ...

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your full name.',
            'customer_name.regex' => 'Name can only contain letters, spaces, hyphens, and dots.',
            
            'customer_phone.required' => 'Please enter your phone number.',
            'customer_phone.regex' => 'Please enter a valid Sri Lankan phone number.',
            
            'customer_email.required' => 'Please enter your email address.',
            'customer_email.email' => 'Please enter a valid email address.',
            
            'address_line_1.required' => 'Please enter your address.',
            'city.required' => 'Please select your city.',
            'district.required' => 'Please select your district.',
            'postal_code.required' => 'Please enter your postal code.',
            'postal_code.regex' => 'Please enter a valid 5-digit postal code.',
            
            'payment_method.required' => 'Please select a payment method.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions to proceed.',
            
            'saved_address_id.exists' => 'The selected address is invalid.',
        ];
    }

    protected function prepareForValidation()
    {
        // Clean phone number
        if ($this->has('customer_phone')) {
            $phone = preg_replace('/[^0-9+]/', '', $this->customer_phone);
            $this->merge(['customer_phone' => $phone]);
        }
        
        // Convert save_address checkbox to boolean
        if ($this->has('save_address')) {
            $this->merge([
                'save_address' => $this->boolean('save_address')
            ]);
        }
        
        // Trim all string inputs
        $fieldsToTrim = [
            'customer_name', 'customer_email', 'address_line_1', 'address_line_2',
            'city', 'district', 'postal_code', 'delivery_notes', 'order_notes'
        ];
        
        foreach ($fieldsToTrim as $field) {
            if ($this->has($field)) {
                $this->merge([$field => trim($this->$field)]);
            }
        }
    }
}