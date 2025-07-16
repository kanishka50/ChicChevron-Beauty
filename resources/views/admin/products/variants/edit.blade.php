@extends('admin.layouts.app')

@section('title', 'Edit Variant')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">
            Edit Variant: {{ $variant->name }}
        </h1>
        <a href="{{ route('admin.products.variants.index', $variant->product) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Back to Variants
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.variants.update', $variant) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Variant Attributes</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Update values for the attributes that apply to this variant. Leave blank if not applicable.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                        <input type="text" 
                               name="size" 
                               value="{{ old('size', $variant->size) }}"
                               placeholder="e.g., 50ml, Large, 100g"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                        <input type="text" 
                               name="color" 
                               value="{{ old('color', $variant->color) }}"
                               placeholder="e.g., Red, Natural"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scent</label>
                        <input type="text" 
                               name="scent" 
                               value="{{ old('scent', $variant->scent) }}"
                               placeholder="e.g., Rose, Vanilla"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Variant Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SKU <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="sku" 
                               value="{{ old('sku', $variant->sku) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost Price (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" 
                               name="cost_price" 
                               value="{{ old('cost_price', $variant->cost_price) }}"
                               step="0.01"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selling Price (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price', $variant->price) }}"
                               step="0.01"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount Price (Rs.)</label>
                        <input type="number" 
                               name="discount_price" 
                               value="{{ old('discount_price', $variant->discount_price) }}"
                               step="0.01"
                               placeholder="Optional"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Stock Information</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Current Stock</p>
                            <p class="font-medium text-lg">{{ $variant->inventory->current_stock ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reserved Stock</p>
                            <p class="font-medium text-lg">{{ $variant->inventory->reserved_stock ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Available Stock</p>
                            <p class="font-medium text-lg">{{ $variant->available_stock }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Profit Margin</p>
                            <p class="font-medium text-lg {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $variant->profit_margin }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.products.variants.index', $variant->product) }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Update Variant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection