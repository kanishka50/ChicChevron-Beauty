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
            'texture_id' => 'nullable|exists:textures,id',
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
        // Convert empty texture to null
        if ($this->texture_id === '' || $this->texture_id === null) {
            $this->merge(['texture_id' => null]);
        }
    }
}