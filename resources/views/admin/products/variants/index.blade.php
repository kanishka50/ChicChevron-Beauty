@extends('admin.layouts.app')

@section('title', 'Manage Product Variants')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-semibold text-gray-800">
            Manage Variants: {{ $product->name }}
        </h1>
        <p class="text-gray-600 mt-1">Add and manage different variants for this product</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.products.show', $product) }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Back to Product
        </a>
        <a href="{{ route('admin.products.variants.create', $product) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Add New Variant
        </a>
    </div>
</div>

<!-- Variants Table -->
<div class="bg-white rounded-lg shadow-md p-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Variant
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    SKU
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Stock
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($variants as $variant)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $variant->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($variant->size) Size: {{ $variant->size }} @endif
                                @if($variant->color) Color: {{ $variant->color }} @endif
                                @if($variant->scent) Scent: {{ $variant->scent }} @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $variant->sku }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            Rs. {{ number_format($variant->price, 2) }}
                            @if($variant->discount_price)
                                <span class="text-red-600 line-through text-xs">
                                    Rs. {{ number_format($variant->price, 2) }}
                                </span>
                                <span class="text-green-600">
                                    Rs. {{ number_format($variant->discount_price, 2) }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500">
                            Cost: Rs. {{ number_format($variant->cost_price, 2) }} 
                            ({{ $variant->profit_margin }}% margin)
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $variant->inventory->current_stock ?? 0 }}
                            @if($variant->inventory && $variant->inventory->current_stock <= $variant->inventory->low_stock_threshold)
                                <span class="text-xs text-red-600">(Low stock)</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.variants.edit', $variant) }}" 
                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </a>
                        <button onclick="toggleVariantStatus({{ $variant->id }})" 
                                class="text-yellow-600 hover:text-yellow-900 mr-3">
                            {{ $variant->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                        @if($variants->count() > 1 && !$variant->orderItems()->exists())
                            <form action="{{ route('admin.variants.destroy', $variant) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No variants found. Add your first variant.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
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