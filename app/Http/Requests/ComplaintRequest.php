<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|in:product,delivery,payment,service,other',
            'order_id' => 'nullable|exists:orders,id',
            'subject' => 'required|string|min:5|max:200',
            'description' => 'required|string|min:20|max:2000',
            'priority' => 'required|in:low,medium,high',
            'contact_methods' => 'nullable|array',
            'contact_methods.*' => 'in:email,phone',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB max per file
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'category.required' => 'Please select a complaint category.',
            'category.in' => 'Please select a valid complaint category.',
            'order_id.exists' => 'The selected order is invalid.',
            'subject.required' => 'Please provide a subject for your complaint.',
            'subject.min' => 'Subject must be at least 5 characters long.',
            'subject.max' => 'Subject cannot exceed 200 characters.',
            'description.required' => 'Please describe your complaint in detail.',
            'description.min' => 'Description must be at least 20 characters long.',
            'description.max' => 'Description cannot exceed 2000 characters.',
            'priority.required' => 'Please select a priority level.',
            'priority.in' => 'Please select a valid priority level.',
            'contact_methods.*.in' => 'Invalid contact method selected.',
            'attachments.max' => 'You can upload a maximum of 5 files.',
            'attachments.*.mimes' => 'Only JPG, JPEG, PNG, and PDF files are allowed.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'order_id' => 'order',
            'contact_methods.*' => 'contact method',
            'attachments.*' => 'attachment',
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Ensure at least email is selected if no contact methods provided
        if (empty($this->contact_methods)) {
            $this->merge([
                'contact_methods' => ['email']
            ]);
        }
    }
}