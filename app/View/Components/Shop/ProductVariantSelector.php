<?php

namespace App\View\Components\Shop;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product;

class ProductVariantSelector extends Component
{
    public $product;
    public $variants;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->variants = $product->variants()
            ->where('is_active', true)
            ->with('inventory')
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.shop.product-variant-selector');
    }
}