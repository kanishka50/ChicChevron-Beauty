@extends('admin.layouts.app')

@section('title', 'Edit Variant')

@section('content')
<div class="container-fluid px-4 max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Edit Variant
            </h1>
            <p class="text-gray-600 mt-1">{{ $variant->name }}</p>
        </div>
        <a href="{{ route('admin.products.variants.index', $variant->product) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            Back to Variants
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.variants.update', $variant) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Variant Attributes Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Variant Attributes</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Update values for the attributes that apply to this variant. Leave blank if not applicable.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <input type="text" 
                               name="size" 
                               value="{{ old('size', $variant->size) }}"
                               placeholder="e.g., 50ml, Large, 100g"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('size') border-red-500 @enderror">
                        @error('size')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <input type="text" 
                               name="color" 
                               value="{{ old('color', $variant->color) }}"
                               placeholder="e.g., Red, Natural"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror">
                        @error('color')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scent</label>
                        <input type="text" 
                               name="scent" 
                               value="{{ old('scent', $variant->scent) }}"
                               placeholder="e.g., Rose, Vanilla"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('scent') border-red-500 @enderror">
                        @error('scent')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Variant Details Section -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Variant Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            SKU <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="sku" 
                               value="{{ old('sku', $variant->sku) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('sku') border-red-500 @enderror">
                        @error('sku')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cost Price (Rs.) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="cost_price" 
                               value="{{ old('cost_price', $variant->cost_price) }}"
                               step="0.01"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('cost_price') border-red-500 @enderror">
                        @error('cost_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Selling Price (Rs.) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price', $variant->price) }}"
                               step="0.01"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Price (Rs.) <span class="text-gray-500">(Optional)</span>
                        </label>
                        <input type="number" 
                               name="discount_price" 
                               value="{{ old('discount_price', $variant->discount_price) }}"
                               step="0.01"
                               placeholder="Leave empty for no discount"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('discount_price') border-red-500 @enderror">
                        @error('discount_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Current Stock Information -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Stock Information</h3>
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Current Stock</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $variant->inventory->current_stock ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Reserved Stock</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $variant->inventory->reserved_stock ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Available Stock</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $variant->available_stock }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Profit Margin</p>
                            <p class="text-2xl font-bold {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                                {{ $variant->profit_margin }}%
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.inventory.index') }}?search={{ $variant->sku }}" 
                           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Manage Stock Levels
                        </a>
                    </div>
                </div>
            </div>
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-14a1 1 0 00-1 1v6a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.products.variants.index', $variant->product) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Update Variant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection