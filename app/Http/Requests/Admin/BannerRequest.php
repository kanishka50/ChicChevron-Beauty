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
            'title' => 'required|string|max:255',
            'link_url' => 'nullable|url|max:255',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ];
        
        // Image is required only for create
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1200,min_height=400';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=1200,min_height=400';
        }
        
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Please upload a banner image.',
            'image.dimensions' => 'Banner image must be at least 1200x400 pixels.',
            'image.max' => 'Banner image must not exceed 2MB.',
            'title.required' => 'Banner title is required.',
            'link_url.url' => 'Please enter a valid URL.',
        ];
    }
}