<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReviewRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'reviews' => 'required|array|min:1',
            'reviews.*.rating' => 'required|integer|between:1,5',
            'reviews.*.title' => 'required|string|max:100',
            'reviews.*.comment' => 'required|string|min:10|max:1000',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'reviews.*.rating' => 'rating',
            'reviews.*.title' => 'review title',
            'reviews.*.comment' => 'review comment',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'Order ID is required.',
            'order_id.exists' => 'Invalid order selected.',
            'reviews.required' => 'Please provide at least one review.',
            'reviews.*.rating.required' => 'Please select a rating for each product.',
            'reviews.*.rating.between' => 'Rating must be between 1 and 5 stars.',
            'reviews.*.title.required' => 'Please provide a title for your review.',
            'reviews.*.title.max' => 'Review title cannot exceed 100 characters.',
            'reviews.*.comment.required' => 'Please write a comment for your review.',
            'reviews.*.comment.min' => 'Review comment must be at least 10 characters long.',
            'reviews.*.comment.max' => 'Review comment cannot exceed 1000 characters.',
        ];
    }
}