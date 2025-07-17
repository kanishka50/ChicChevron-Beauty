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
            'link_type' => 'required|in:product,category,url,none',
            'link_value' => 'nullable|required_unless:link_type,none|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ];
        
        // Image validation
        if ($this->isMethod('post')) {
            // For create, desktop image is required
            $rules['image_desktop'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1200,min_height=400';
            $rules['image_mobile'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            // For update, both are optional
            $rules['image_desktop'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1200,min_height=400';
            $rules['image_mobile'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
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
            'image_desktop.dimensions' => 'Desktop banner image must be at least 1200x400 pixels.',
            'image_desktop.max' => 'Banner image must not exceed 2MB.',
            'link_value.required_unless' => 'Please provide a link value when link type is not "none".',
        ];
    }
}