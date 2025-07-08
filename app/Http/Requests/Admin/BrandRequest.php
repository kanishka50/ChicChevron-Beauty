<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
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
        $brand = $this->route('brand');
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands')->ignore($brand)
            ],
            'logo' => [
                $brand ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The brand name is required.',
            'name.unique' => 'This brand name already exists.',
            'logo.required' => 'The brand logo is required for new brands.',
            'logo.image' => 'The file must be an image.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg, webp.',
            'logo.max' => 'The logo may not be greater than 2MB.',
        ];
    }
}