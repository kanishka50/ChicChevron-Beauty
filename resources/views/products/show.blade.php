@extends('layouts.app')

@section('title', $product->name . ' - ChicChevron Beauty')
@section('description', Str::limit(strip_tags($product->description), 160))

@section('content')
    <div class="container-responsive py-6 md:py-10">
        <!-- Breadcrumb -->
        <nav class="mb-6 md:mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-plum-600">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('products.index') }}" class="hover:text-plum-600">Products</a></li>
                @if($product->category)
                    <li>/</li>
                    <li><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="hover:text-plum-600">{{ $product->category->name }}</a></li>
                @endif
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-gray-50 rounded-lg overflow-hidden">
                    <img id="main-image"
                         src="{{ $product->main_image ? asset('storage/' . $product->main_image) : '/placeholder.jpg' }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-contain p-6 cursor-zoom-in"
                         onclick="openImageModal(this.src)">
                </div>

                <!-- Thumbnails -->
                @if(($product->gallery_images && count($product->gallery_images) > 0) || $product->main_image)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @if($product->main_image)
                            <button class="flex-shrink-0 w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border-2 border-plum-500"
                                    onclick="changeMainImage('{{ asset('storage/' . $product->main_image) }}', this)">
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endif
                        @if($product->gallery_images)
                            @foreach($product->gallery_images as $imagePath)
                                <button class="flex-shrink-0 w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border-2 border-transparent hover:border-gray-300"
                                        onclick="changeMainImage('{{ asset('storage/' . $imagePath) }}', this)">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Brand & Title -->
                <div>
                    @if($product->brand)
                        <a href="{{ route('products.index', ['brands' => [$product->brand->id]]) }}" class="text-sm text-plum-600 hover:text-plum-700 font-medium">
                            {{ $product->brand->name }}
                        </a>
                    @endif
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>
                </div>

                <!-- Price -->
                <div id="price-display">
                    @if($product->variants->count() > 1)
                        <p class="text-sm text-gray-500 mb-1">Starting from</p>
                    @endif
                    <p class="text-3xl font-bold text-gray-900">
                        Rs. {{ number_format($product->variants->min('price'), 0) }}
                    </p>
                </div>

                <!-- Stock Status -->
                <div id="stock-status">
                    @if($product->getStockLevel() > 0)
                        <span class="inline-flex items-center gap-2 text-green-700 text-sm">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            In Stock
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-red-600 text-sm">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Variant Selection -->
                @if($product->variants->count() > 0)
                    <div id="variant-selection" class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">
                                @if($product->variants->count() > 1)
                                    Select Option
                                @else
                                    Option
                                @endif
                            </span>
                            <span id="selected-variant-label" class="text-sm text-plum-600 font-medium"></span>
                        </div>

                        <div id="variant-error" class="hidden text-sm text-red-600 bg-red-50 rounded-xl px-4 py-3 border border-red-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Please select an option before adding to cart
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($product->variants as $variant)
                                @php
                                    $variantStock = $variant->inventory ? ($variant->inventory->stock_quantity - $variant->inventory->reserved_quantity) : 0;
                                    $isOutOfStock = $variantStock <= 0;
                                    $hasDiscount = $variant->discount_price && $variant->discount_price < $variant->price;
                                @endphp
                                <div class="variant-option group relative {{ $isOutOfStock ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}"
                                     data-variant-id="{{ $variant->id }}"
                                     data-price="{{ $variant->effective_price }}"
                                     data-original-price="{{ $variant->price }}"
                                     data-discount-price="{{ $variant->discount_price }}"
                                     data-stock="{{ $variantStock }}"
                                     data-sku="{{ $variant->sku }}"
                                     data-name="{{ $variant->display_name }}"
                                     @if(!$isOutOfStock) onclick="selectProductVariant(this)" @endif>

                                    <!-- Card Container -->
                                    <div class="variant-card relative bg-white border-2 border-gray-100 rounded-xl p-4 transition-all duration-200 {{ $isOutOfStock ? '' : 'hover:border-plum-300 hover:shadow-md' }}">

                                        <!-- Selection Indicator -->
                                        <div class="check-indicator absolute -top-2 -right-2 w-6 h-6 bg-plum-600 rounded-full items-center justify-center hidden shadow-lg">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>

                                        <!-- Discount Badge -->
                                        @if($hasDiscount && !$isOutOfStock)
                                            @php
                                                $discountPercent = round((($variant->price - $variant->discount_price) / $variant->price) * 100);
                                            @endphp
                                            <div class="absolute -top-2 -left-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                                                -{{ $discountPercent }}%
                                            </div>
                                        @endif

                                        <!-- Out of Stock Overlay -->
                                        @if($isOutOfStock)
                                            <div class="absolute inset-0 bg-gray-50/50 rounded-xl flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded-full border border-gray-200">Sold Out</span>
                                            </div>
                                        @endif

                                        <!-- Content -->
                                        <div class="text-center space-y-2">
                                            <!-- Variant Name -->
                                            <p class="text-sm font-semibold text-gray-900 leading-tight">{{ $variant->display_name }}</p>

                                            <!-- Price -->
                                            <div class="space-y-0.5">
                                                @if($hasDiscount)
                                                    <p class="text-base font-bold text-plum-700">Rs. {{ number_format($variant->discount_price, 0) }}</p>
                                                    <p class="text-xs text-gray-400 line-through">Rs. {{ number_format($variant->price, 0) }}</p>
                                                @else
                                                    <p class="text-base font-bold text-gray-900">Rs. {{ number_format($variant->price, 0) }}</p>
                                                @endif
                                            </div>

                                            <!-- Stock Indicator -->
                                            @if(!$isOutOfStock && $variantStock <= 5)
                                                <p class="text-[10px] text-orange-600 font-medium">Only {{ $variantStock }} left</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity & Add to Cart -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">Quantity</span>
                        <div class="flex items-center border border-gray-200 rounded-lg">
                            <button type="button" onclick="changeQuantity(-1)" class="px-4 py-2 text-gray-600 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input type="number" id="quantity" value="1" min="1" max="10" class="w-12 text-center border-0 focus:ring-0 text-sm font-medium">
                            <button type="button" onclick="changeQuantity(1)" class="px-4 py-2 text-gray-600 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button id="add-to-cart-btn" onclick="addToCart()"
                                class="flex-1 bg-plum-700 hover:bg-plum-800 text-white py-3.5 px-6 rounded-lg font-semibold transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Add to Cart
                        </button>
                        <button onclick="toggleWishlist({{ $product->id }})"
                                class="p-3.5 border border-gray-200 rounded-lg hover:border-plum-400 hover:bg-plum-50 transition-colors"
                                data-product-id="{{ $product->id }}">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Short Description -->
                @if($product->description)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                            {{ Str::limit(strip_tags($product->description), 200) }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="mt-12 md:mt-16">
            <div class="border-b border-gray-200">
                <nav class="flex gap-8">
                    <button class="tab-button pb-4 text-sm font-medium border-b-2 border-plum-600 text-plum-600" onclick="showTab('description')">
                        Description
                    </button>
                    @if($product->ingredients)
                        <button class="tab-button pb-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" onclick="showTab('ingredients')">
                            Ingredients
                        </button>
                    @endif
                    @if($product->how_to_use)
                        <button class="tab-button pb-4 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" onclick="showTab('how-to-use')">
                            How to Use
                        </button>
                    @endif
                </nav>
            </div>

            <div class="py-8">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose prose-gray max-w-none text-gray-600">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Ingredients Tab -->
                @if($product->ingredients)
                    <div id="ingredients-tab" class="tab-content hidden">
                        <div class="text-gray-600">
                            <p class="font-medium text-gray-900 mb-4">Ingredients</p>
                            <p>{{ $product->ingredients }}</p>
                        </div>
                    </div>
                @endif

                <!-- How to Use Tab -->
                @if($product->how_to_use)
                    <div id="how-to-use-tab" class="tab-content hidden">
                        <div class="text-gray-600">
                            <p class="font-medium text-gray-900 mb-4">How to Use</p>
                            <div class="prose prose-gray max-w-none">
                                {!! nl2br(e($product->how_to_use)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
            <div class="mt-12 md:mt-16 pt-8 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">You May Also Like</h2>
                    <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-sm text-plum-600 hover:text-plum-700 font-medium">
                        View All
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('components.shop.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black/90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white/80 hover:text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modal-image" src="" alt="" class="max-w-full max-h-[90vh] object-contain" onclick="event.stopPropagation()">
    </div>
@endsection

@push('scripts')
<script>
    let selectedVariant = null;
    const variantCount = {{ $product->variants->count() }};

    function changeMainImage(src, thumbnail) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('button[onclick^="changeMainImage"]').forEach(btn => {
            btn.classList.remove('border-plum-500');
            btn.classList.add('border-transparent');
        });
        thumbnail.classList.remove('border-transparent');
        thumbnail.classList.add('border-plum-500');
    }

    function openImageModal(src) {
        document.getElementById('modal-image').src = src;
        document.getElementById('image-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('image-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + delta;
        value = Math.max(parseInt(input.min), Math.min(parseInt(input.max), value));
        input.value = value;
    }

    function selectProductVariant(element) {
        document.getElementById('variant-error').classList.add('hidden');

        // Reset all variant cards
        document.querySelectorAll('.variant-option').forEach(opt => {
            const card = opt.querySelector('.variant-card');
            const checkIndicator = opt.querySelector('.check-indicator');
            card.classList.remove('border-plum-500', 'bg-plum-50/50', 'shadow-md');
            card.classList.add('border-gray-100');
            checkIndicator.classList.add('hidden');
            checkIndicator.classList.remove('flex');
        });

        // Highlight selected card
        const selectedCard = element.querySelector('.variant-card');
        const selectedCheck = element.querySelector('.check-indicator');
        selectedCard.classList.remove('border-gray-100');
        selectedCard.classList.add('border-plum-500', 'bg-plum-50/50', 'shadow-md');
        selectedCheck.classList.remove('hidden');
        selectedCheck.classList.add('flex');

        selectedVariant = {
            id: element.dataset.variantId,
            price: parseFloat(element.dataset.price),
            originalPrice: parseFloat(element.dataset.originalPrice),
            discountPrice: element.dataset.discountPrice ? parseFloat(element.dataset.discountPrice) : null,
            stock: parseInt(element.dataset.stock),
            sku: element.dataset.sku,
            name: element.dataset.name
        };

        // Update selected label
        document.getElementById('selected-variant-label').textContent = selectedVariant.name;

        // Update price display
        const priceDisplay = document.getElementById('price-display');
        if (selectedVariant.discountPrice && selectedVariant.discountPrice < selectedVariant.originalPrice) {
            priceDisplay.innerHTML = `
                <p class="text-3xl font-bold text-gray-900">Rs. ${selectedVariant.discountPrice.toLocaleString()}</p>
                <p class="text-sm text-gray-400 line-through">Rs. ${selectedVariant.originalPrice.toLocaleString()}</p>
            `;
        } else {
            priceDisplay.innerHTML = `<p class="text-3xl font-bold text-gray-900">Rs. ${selectedVariant.price.toLocaleString()}</p>`;
        }

        // Update stock status
        const stockStatus = document.getElementById('stock-status');
        if (selectedVariant.stock > 0) {
            stockStatus.innerHTML = `<span class="inline-flex items-center gap-2 text-green-700 text-sm"><span class="w-2 h-2 bg-green-500 rounded-full"></span>In Stock (${selectedVariant.stock} available)</span>`;
            document.getElementById('add-to-cart-btn').disabled = false;
        } else {
            stockStatus.innerHTML = `<span class="inline-flex items-center gap-2 text-red-600 text-sm"><span class="w-2 h-2 bg-red-500 rounded-full"></span>Out of Stock</span>`;
            document.getElementById('add-to-cart-btn').disabled = true;
        }

        document.getElementById('quantity').max = Math.min(selectedVariant.stock, 10);
    }

    async function addToCart() {
        if (variantCount > 0 && !selectedVariant) {
            document.getElementById('variant-error').classList.remove('hidden');
            document.getElementById('variant-selection').scrollIntoView({ behavior: 'smooth', block: 'center' });
            showToast('Please select an option', 'error');
            return;
        }

        if (selectedVariant && selectedVariant.stock <= 0) {
            showToast('Selected option is out of stock', 'error');
            return;
        }

        const button = document.getElementById('add-to-cart-btn');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'Adding...';

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    product_variant_id: selectedVariant?.id || null,
                    quantity: parseInt(document.getElementById('quantity').value)
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                button.textContent = 'Added!';
                button.classList.remove('bg-plum-700');
                button.classList.add('bg-green-600');
                showToast('Added to cart', 'success');
                window.dispatchEvent(new Event('cart-updated'));

                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-plum-700');
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message || 'Error adding to cart');
            }
        } catch (error) {
            button.textContent = originalText;
            button.disabled = false;
            showToast(error.message, 'error');
        }
    }

    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-plum-600', 'text-plum-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        document.getElementById(tabName + '-tab')?.classList.remove('hidden');
        event.target.classList.remove('border-transparent', 'text-gray-500');
        event.target.classList.add('border-plum-600', 'text-plum-600');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (variantCount === 1) {
            const firstVariant = document.querySelector('.variant-option:not(.opacity-50)');
            if (firstVariant) selectProductVariant(firstVariant);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeImageModal();
        });
    });
</script>
@endpush

<style>
/* Variant card hover effect */
.variant-option:not(.opacity-40):hover .variant-card {
    transform: translateY(-2px);
}

/* Smooth transitions */
.variant-card {
    transition: all 0.2s ease;
}
</style>
