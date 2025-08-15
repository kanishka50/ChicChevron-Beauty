<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'link_type' => 'required|in:product,category,url,none',
            'link_value' => 'nullable|required_unless:link_type,none|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ];
        
        // Image validation - No dimension restrictions
        if ($this->isMethod('post')) {
            // For create, desktop image is required
            $rules['image_desktop'] = 'required|image|mimes:jpg,jpeg,png,webp|max:5120'; // 5MB max
            $rules['image_mobile'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'; // Optional
        } else {
            // For update, both are optional
            $rules['image_desktop'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
            $rules['image_mobile'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
        }
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image_desktop.required' => 'Please upload a desktop banner image.',
            'image_desktop.image' => 'The desktop banner must be an image file.',
            'image_desktop.mimes' => 'The desktop banner must be a file of type: jpg, jpeg, png, webp.',
            'image_desktop.max' => 'The desktop banner must not be larger than 5MB.',
            'image_mobile.image' => 'The mobile banner must be an image file.',
            'image_mobile.mimes' => 'The mobile banner must be a file of type: jpg, jpeg, png, webp.',
            'image_mobile.max' => 'The mobile banner must not be larger than 5MB.',
            'link_value.required_unless' => 'Please provide a link value when link type is not "none".',
        ];
    }
}