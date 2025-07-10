<?php

namespace App\View\Components\Shop;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product;

class VariantSelector extends Component
{
    public $product;
    public $sizeVariants;
    public $colorVariants;
    public $scentVariants;
    public $variantCombinations;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->sizeVariants = $product->variants()->ofType('size')->active()->get();
        $this->colorVariants = $product->variants()->ofType('color')->active()->get();
        $this->scentVariants = $product->variants()->ofType('scent')->active()->get();
        $this->variantCombinations = $product->variantCombinations()
            ->with(['sizeVariant', 'colorVariant', 'scentVariant', 'inventory'])
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.shop.variant-selector');
    }
}