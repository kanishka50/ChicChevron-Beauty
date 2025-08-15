@extends('admin.layouts.app')

@section('title', 'Manage Product Variants')

@section('content')
<div class="container-fluid px-4 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Manage Variants: {{ $product->name }}
            </h1>
            <p class="text-gray-600 mt-1">Add and manage different variants for this product</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.show', $product) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Back to Product
            </a>
            <a href="{{ route('admin.products.variants.create', $product) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Add New Variant
            </a>
        </div>
    </div>

    <!-- Variants Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if(session('success'))
            <div class="bg-green-50 border-b border-green-200 px-4 py-3">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-b border-red-200 px-4 py-3">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-800">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-64">
                            Variant Details
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-40">
                            SKU
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48">
                            Pricing
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-32 text-center">
                            Stock
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-24 text-center">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48 text-center">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($variants as $variant)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $variant->name }}
                                    </p>
                                    <div class="text-xs text-gray-500 mt-1 space-x-3">
                                        @if($variant->size) 
                                            <span class="inline-flex items-center">
                                                <span class="font-medium">Size:</span> {{ $variant->size }}
                                            </span>
                                        @endif
                                        @if($variant->color) 
                                            <span class="inline-flex items-center">
                                                <span class="font-medium">Color:</span> {{ $variant->color }}
                                            </span>
                                        @endif
                                        @if($variant->scent) 
                                            <span class="inline-flex items-center">
                                                <span class="font-medium">Scent:</span> {{ $variant->scent }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-900 font-mono">{{ $variant->sku }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    @if($variant->discount_price)
                                        <div class="text-sm">
                                            <span class="text-gray-500 line-through">Rs. {{ number_format($variant->price, 2) }}</span>
                                        </div>
                                        <div class="text-sm font-medium text-green-600">
                                            Rs. {{ number_format($variant->discount_price, 2) }}
                                        </div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">
                                            Rs. {{ number_format($variant->price, 2) }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">
                                        Cost: Rs. {{ number_format($variant->cost_price, 2) }}
                                        <span class="ml-1 font-medium {{ $variant->profit_margin > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ({{ $variant->profit_margin }}%)
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div>
                                    <span class="text-sm font-semibold
                                        @if(($variant->inventory->current_stock ?? 0) > 20) text-green-600
                                        @elseif(($variant->inventory->current_stock ?? 0) > 0) text-yellow-600
                                        @else text-red-600
                                        @endif">
                                        {{ $variant->inventory->current_stock ?? 0 }}
                                    </span>
                                    @if($variant->inventory && $variant->inventory->current_stock <= $variant->inventory->low_stock_threshold)
                                        <span class="block text-xs text-red-600 mt-1">Low stock</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $variant->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.variants.edit', $variant) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <button onclick="toggleVariantStatus({{ $variant->id }})" 
                                            class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">
                                        {{ $variant->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    @if($variants->count() > 1 && !$variant->orderItems()->exists())
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.variants.destroy', $variant) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">No variants found</p>
                                    <a href="{{ route('admin.products.variants.create', $product) }}" class="text-blue-500 hover:text-blue-600 font-medium">Add your first variant</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleVariantStatus(variantId) {
    if (!confirm('Are you sure you want to change this variant status?')) return;
    
    fetch(`/admin/variants/${variantId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection