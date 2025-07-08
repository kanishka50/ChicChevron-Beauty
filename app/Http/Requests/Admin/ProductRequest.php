<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($product)
            ],
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id',
            'texture_id' => 'nullable|exists:textures,id',
            'cost_price' => 'required|numeric|min:0|max:9999999.99',
            'selling_price' => 'required|numeric|min:0|max:9999999.99|gte:cost_price',
            'discount_price' => 'nullable|numeric|min:0|max:9999999.99|lt:selling_price',
            'main_image' => [
                $product ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'how_to_use' => 'nullable|string',
            'suitable_for' => 'nullable|string|max:255',
            'fragrance' => 'nullable|string|max:100',
            'ingredients' => 'nullable|array',
            'ingredients.*' => 'nullable|string|max:255',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'brand_id.required' => 'Please select a brand.',
            'brand_id.exists' => 'The selected brand is invalid.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'product_type_id.required' => 'Please select a product type.',
            'product_type_id.exists' => 'The selected product type is invalid.',
            'cost_price.required' => 'The cost price is required.',
            'cost_price.numeric' => 'The cost price must be a number.',
            'selling_price.required' => 'The selling price is required.',
            'selling_price.numeric' => 'The selling price must be a number.',
            'selling_price.gte' => 'The selling price must be greater than or equal to cost price.',
            'discount_price.lt' => 'The discount price must be less than selling price.',
            'main_image.required' => 'The main product image is required.',
            'main_image.image' => 'The file must be an image.',
            'main_image.max' => 'The image may not be greater than 2MB.',
            'additional_images.*.image' => 'All additional files must be images.',
            'additional_images.*.max' => 'Each image may not be greater than 2MB.',
            'sku.unique' => 'This SKU already exists.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert empty discount price to null
        if ($this->discount_price === '' || $this->discount_price === null) {
            $this->merge(['discount_price' => null]);
        }
        
        // Convert empty texture to null
        if ($this->texture_id === '' || $this->texture_id === null) {
            $this->merge(['texture_id' => null]);
        }
    }
}