@extends('admin.layouts.app')

@section('title', 'Colors')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Product Colors</h1>
            <p class="text-gray-600 mt-2">Manage color options for products</p>
        </div>

        <!-- Add New Color -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Color</h2>
            
            <form action="{{ route('admin.colors.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <input type="text" 
                           name="name" 
                           placeholder="Color name (e.g., Lavender)" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-48">
                    <input type="color" 
                           name="hex_code" 
                           id="color-picker"
                           value="#000000"
                           class="w-full h-10 px-2 py-1 border border-gray-300 rounded-md cursor-pointer"
                           onchange="updateHexInput(this.value)">
                </div>
                <div class="w-32">
                    <input type="text" 
                           name="hex_code_display" 
                           id="hex-code-input"
                           placeholder="#000000" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md uppercase"
                           pattern="^#[0-9A-Fa-f]{6}$"
                           maxlength="7"
                           onchange="updateColorPicker(this.value)">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                    Add Color
                </button>
            </form>
        </div>

        <!-- Colors Grid -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($colors as $color)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-16 h-16 rounded-lg border-2 border-gray-300 shadow-inner" 
                                     style="background-color: {{ $color->hex_code }}"></div>
                                <div class="text-right">
                                    @if($color->is_default)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Default</span>
                                    @else
                                        <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">Custom</span>
                                    @endif
                                </div>
                            </div>
                            
                            <h3 class="font-medium text-gray-900">{{ $color->name }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $color->hex_code }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $color->products_count }} products</p>
                            
                            @if(!$color->is_default)
                                <div class="mt-3 flex justify-end">
                                    @if($color->products_count == 0)
                                        <form action="{{ route('admin.colors.destroy', $color) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this color?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-sm text-gray-400">In use</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No colors found.
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if($colors->hasPages())
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    {{ $colors->links() }}
                </div>
            @endif
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">About Colors</h3>
            <p class="text-sm text-blue-700">
                Colors are used to define available color options for beauty products like lipsticks, nail polish, etc. 
                Default colors include common beauty product shades. You can add custom colors specific to your products.
            </p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function updateHexInput(value) {
        document.getElementById('hex-code-input').value = value.toUpperCase();
        // Also update the hidden input that will be submitted
        document.querySelector('input[name="hex_code"]').value = value.toUpperCase();
    }
    
    function updateColorPicker(value) {
        if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
            document.getElementById('color-picker').value = value;
            document.querySelector('input[name="hex_code"]').value = value.toUpperCase();
        }
    }
    
    // Initialize the hidden input
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'hex_code';
        hiddenInput.value = '#000000';
        form.appendChild(hiddenInput);
    });
</script>
@endpush