@extends('admin.layouts.app')

@section('title', 'Textures')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Product Textures</h1>
            <p class="text-gray-600 mt-2">Manage texture options for products (Cream, Gel, Lotion, etc.)</p>
        </div>

        <!-- Add New Texture -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Add New Texture</h2>
            
            <form action="{{ route('admin.textures.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="flex-1">
                    <input type="text" 
                           name="name" 
                           placeholder="Enter texture name (e.g., Mist, Stick)" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                    Add Texture
                </button>
            </form>
        </div>

        <!-- Textures List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Texture Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($textures as $texture)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $texture->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($texture->is_default)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Default
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Custom
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $texture->products_count }} products
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(!$texture->is_default)
                                    @if($texture->products_count == 0)
                                        <form action="{{ route('admin.textures.destroy', $texture) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this texture?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">In use</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">System default</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No textures found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($textures->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $textures->links() }}
                </div>
            @endif
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">About Textures</h3>
            <p class="text-sm text-blue-700">
                Textures describe the physical form or consistency of beauty products. Default textures include:
                Cream, Gel, Lotion, Oil, Serum, Foam, Powder, Spray, and Balm. You can add custom textures as needed.
            </p>
        </div>
    </div>
@endsection