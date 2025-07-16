<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
        $action = $this->route()->getActionMethod();

        switch ($action) {
            case 'addStock':
                return $this->addStockRules();
            
            case 'adjustStock':
                return $this->adjustStockRules();
            
            case 'updateVariant':
                return $this->updateVariantRules();
            
            default:
                return [];
        }
    }

    /**
     * Rules for adding stock
     */
    private function addStockRules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:10000',
            'cost_per_unit' => 'required|numeric|min:0|max:999999.99',
            'reason' => 'required|string|max:255',
            'supplier' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:50',
        ];
    }

    /**
     * Rules for adjusting stock
     */
    private function adjustStockRules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'new_quantity' => 'required|integer|min:0|max:10000',
            'reason' => 'required|string|max:255|in:Damaged,Lost,Found,Count Correction,Return,Other',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Rules for updating variant inventory settings
     */
    private function updateVariantRules(): array
    {
        return [
            'current_stock' => 'required|integer|min:0|max:10000',
            'low_stock_threshold' => 'required|integer|min:0|max:1000',
            'reorder_point' => 'nullable|integer|min:0|max:1000',
            'reorder_quantity' => 'nullable|integer|min:1|max:10000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Please select a product.',
            'product_variant_id.required' => 'Please select a product variant.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 10,000.',
            'cost_per_unit.required' => 'Cost per unit is required.',
            'cost_per_unit.numeric' => 'Cost must be a valid number.',
            'reason.required' => 'Please provide a reason for this stock change.',
            'reason.in' => 'Please select a valid reason.',
            'new_quantity.min' => 'Stock quantity cannot be negative.',
            'low_stock_threshold.required' => 'Low stock threshold is required.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure numeric values are properly formatted
        if ($this->has('cost_per_unit')) {
            $this->merge([
                'cost_per_unit' => str_replace(',', '', $this->cost_per_unit)
            ]);
        }

        // Validate variant belongs to product
        if ($this->has('product_id') && $this->has('product_variant_id')) {
            $variant = \App\Models\ProductVariant::find($this->product_variant_id);
            if ($variant && $variant->product_id != $this->product_id) {
                $this->merge(['product_variant_id' => null]);
            }
        }
    }
}