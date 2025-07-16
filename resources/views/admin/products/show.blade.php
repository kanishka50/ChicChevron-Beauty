@extends('admin.layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Product Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.products.variants.index', $product) }}" 
                   class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Manage Variants
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Products
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Images -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Images</h2>
                    
                    <!-- Main Image -->
                    <div class="mb-4">
                        <img src="{{ Storage::url($product->main_image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-64 object-cover rounded-lg">
                    </div>
                    
                    <!-- Additional Images -->
                    @if($product->images->isNotEmpty())
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($product->images as $image)
                                <img src="{{ Storage::url($image->image_path) }}" 
                                     alt="Product image" 
                                     class="w-full h-24 object-cover rounded">
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Stock Information with Visual Indicators -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Stock Information</h2>
                    @include('admin.products.partials.stock-indicators', [
                        'product' => $product, 
                        'detailed' => true, 
                        'showActions' => true
                    ])
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Product Name</p>
                            <p class="font-medium">{{ $product->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">SKU</p>
                            <p class="font-medium">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Brand</p>
                            <p class="font-medium">{{ $product->brand->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Category</p>
                            <p class="font-medium">{{ $product->category->path }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Product Type</p>
                            <p class="font-medium">{{ $product->productType->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Texture</p>
                            <p class="font-medium">{{ $product->texture->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Has Variants</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->has_multiple_variants ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->has_multiple_variants ? 'Yes (' . $product->variants()->count() . ')' : 'No' }}
                            </span>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-600 mb-2">Description</p>
                            <p class="text-gray-700">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Product Variants -->
                @if($product->variants->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">Product Variants ({{ $product->variants->count() }})</h2>
                            <a href="{{ route('admin.products.variants.index', $product) }}" 
                               class="text-sm bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded">
                                Manage All Variants
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($product->variants->take(5) as $variant)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $variant->name }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        @if($variant->size) Size: {{ $variant->size }} @endif
                                                        @if($variant->color) Color: {{ $variant->color }} @endif
                                                        @if($variant->scent) Scent: {{ $variant->scent }} @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500">{{ $variant->sku }}</td>
                                            <td class="px-4 py-2">
                                                <div class="text-sm">
                                                    @if($variant->discount_price)
                                                        <span class="text-gray-500 line-through">LKR {{ number_format($variant->price, 2) }}</span>
                                                        <span class="text-green-600 font-medium">LKR {{ number_format($variant->discount_price, 2) }}</span>
                                                    @else
                                                        <span class="text-gray-900">LKR {{ number_format($variant->price, 2) }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="text-sm">
                                                    <span class="{{ $variant->available_stock > 10 ? 'text-green-600' : ($variant->available_stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                                        {{ $variant->available_stock }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($product->variants->count() > 5)
                                <div class="px-4 py-2 text-sm text-gray-500">
                                    ... and {{ $product->variants->count() - 5 }} more variants
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Product Attributes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Attributes</h2>
                    
                    <div class="space-y-4">
                        @if($product->suitable_for)
                            <div>
                                <p class="text-sm text-gray-600">Suitable For</p>
                                <p class="font-medium">{{ $product->suitable_for }}</p>
                            </div>
                        @endif
                        
                        @if($product->fragrance)
                            <div>
                                <p class="text-sm text-gray-600">Fragrance</p>
                                <p class="font-medium">{{ $product->fragrance }}</p>
                            </div>
                        @endif
                        
                        @if($product->how_to_use)
                            <div>
                                <p class="text-sm text-gray-600">How to Use</p>
                                <p class="text-gray-700">{{ $product->how_to_use }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ingredients -->
                @if($product->ingredients->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Ingredients</h2>
                        
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->ingredients as $ingredient)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    {{ $ingredient->ingredient_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Product Statistics</h2>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Views</p>
                            <p class="font-bold text-2xl">{{ $product->views_count }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Reviews</p>
                            <p class="font-bold text-2xl">{{ $product->review_count }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Average Rating</p>
                            <p class="font-bold text-2xl">{{ number_format($product->average_rating, 1) }}/5</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection