<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $variant = $this->route('variant');
        $product = $this->route('product') ?? $variant?->product;
        
        return [
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'scent' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('product_variants')->ignore($variant)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'The selling price is required.',
            'cost_price.required' => 'The cost price is required.',
            'discount_price.lt' => 'The discount price must be less than the regular price.',
            'sku.unique' => 'This SKU already exists for another variant.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Ensure at least one attribute is provided
            if (!$this->size && !$this->color && !$this->scent) {
                $validator->errors()->add('variant', 'Please provide at least one variant attribute (size, color, or scent).');
            }
        });
    }
}