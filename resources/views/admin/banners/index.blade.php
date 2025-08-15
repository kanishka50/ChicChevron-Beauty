@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Banner Management</h1>
        <a href="{{ route('admin.banners.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Banner
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Banners Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-20">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-40">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-64">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-48">Link</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-28 text-center">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider w-32 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="banners-tbody">
                    @forelse($banners as $banner)
                        <tr data-id="{{ $banner->id }}" class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <span class="drag-handle cursor-move text-gray-400 mr-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                    </span>
                                    <span class="text-sm text-gray-900">{{ $banner->sort_order }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <img src="{{ $banner->desktop_image_url }}" 
                                    alt="{{ $banner->title }}" 
                                    class="h-16 w-28 object-cover rounded-lg border border-gray-200">
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $banner->title ?: 'No title' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($banner->link_type !== 'none' && $banner->link_value)
                                    <span class="text-sm text-blue-600">
                                        {{ ucfirst($banner->link_type) }}: {{ $banner->link_value }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">No link</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="toggleStatus({{ $banner->id }})" 
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full transition-colors
                                        {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 mb-2">No banners found</p>
                                    <a href="{{ route('admin.banners.create') }}" class="text-blue-500 hover:text-blue-600 font-medium">
                                        Create your first banner
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($banners->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Toggle banner status
function toggleStatus(bannerId) {
    fetch(`/admin/banners/${bannerId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Sortable for drag and drop reordering
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('banners-tbody');
    if (tbody && tbody.children.length > 0) {
        new Sortable(tbody, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const bannerIds = Array.from(tbody.children).map(tr => tr.dataset.id);
                
                fetch('{{ route("admin.banners.update-order") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ banners: bannerIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update order numbers in UI
                        Array.from(tbody.children).forEach((tr, index) => {
                            const orderCell = tr.querySelector('td:first-child .text-sm');
                            if (orderCell) {
                                orderCell.textContent = index + 1;
                            }
                        });
                    }
                });
            }
        });
    }
});
</script>
@endpush
@endsection