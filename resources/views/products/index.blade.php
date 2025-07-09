@extends('layouts.app')

@section('title', 'Products - ChicChevron Beauty')
@section('description', 'Browse our complete collection of premium beauty products. Filter by brand, category, price, and more.')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-pink-600">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900">Products</li>
            @if(request('category'))
                @php $category = \App\Models\Category::find(request('category')); @endphp
                @if($category)
                    <li><span class="text-gray-400">/</span></li>
                    <li class="text-gray-900">{{ $category->name }}</li>
                @endif
            @endif
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        @if(request()->hasAny(['category', 'brands', 'colors', 'textures', 'min_price', 'max_price', 'min_rating']))
                            <a href="{{ route('products.index') }}" class="text-sm text-pink-600 hover:text-pink-700">
                                Clear All
                            </a>
                        @endif
                    </div>

                    <form id="filter-form" method="GET" action="{{ route('products.index') }}">
                        <!-- Category Filter -->
                        @if($filters['categories']->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Category</h4>
                                <div class="space-y-2">
                                    @foreach($filters['categories'] as $category)
                                        <label class="flex items-center">
                                            <input 
                                                type="radio" 
                                                name="category" 
                                                value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $category->name }} ({{ $category->products_count }})
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Brand Filter -->
                        @if($filters['brands']->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Brand</h4>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($filters['brands'] as $brand)
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                name="brands[]" 
                                                value="{{ $brand->id }}"
                                                {{ in_array($brand->id, (array)request('brands', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $brand->name }} ({{ $brand->products_count }})
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Price Range</h4>
                            <div class="space-y-3">
                                <div class="flex gap-2">
                                    <input 
                                        type="number" 
                                        name="min_price" 
                                        value="{{ request('min_price') }}"
                                        placeholder="Min"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-pink-500"
                                        min="0"
                                        max="{{ $filters['priceRange']['max'] }}"
                                    >
                                    <input 
                                        type="number" 
                                        name="max_price" 
                                        value="{{ request('max_price') }}"
                                        placeholder="Max"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-pink-500"
                                        min="{{ $filters['priceRange']['min'] }}"
                                        max="{{ $filters['priceRange']['max'] }}"
                                    >
                                </div>
                                <button 
                                    type="submit" 
                                    class="w-full bg-pink-600 text-white py-2 px-4 rounded-md text-sm hover:bg-pink-700 transition-colors"
                                >
                                    Apply Price Filter
                                </button>
                            </div>
                        </div>

                        <!-- Color Filter -->
                        @if($filters['colors']->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Colors</h4>
                                <div class="grid grid-cols-6 gap-2">
                                    @foreach($filters['colors'] as $color)
                                        <label class="flex flex-col items-center cursor-pointer group">
                                            <input 
                                                type="checkbox" 
                                                name="colors[]" 
                                                value="{{ $color->id }}"
                                                {{ in_array($color->id, (array)request('colors', [])) ? 'checked' : '' }}
                                                class="sr-only"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <div class="w-8 h-8 rounded-full border-2 group-hover:scale-110 transition-transform {{ in_array($color->id, (array)request('colors', [])) ? 'border-pink-600 ring-2 ring-pink-200' : 'border-gray-300' }}"
                                                 style="background-color: {{ $color->color_code }}"
                                                 title="{{ $color->name }}">
                                            </div>
                                            <span class="text-xs text-gray-600 mt-1 text-center">{{ substr($color->name, 0, 4) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Texture Filter -->
                        @if($filters['textures']->isNotEmpty())
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Texture</h4>
                                <div class="space-y-2">
                                    @foreach($filters['textures'] as $texture)
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                name="textures[]" 
                                                value="{{ $texture->id }}"
                                                {{ in_array($texture->id, (array)request('textures', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">{{ $texture->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Rating Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Minimum Rating</h4>
                            <div class="space-y-2">
                                @for($rating = 4; $rating >= 1; $rating--)
                                    <label class="flex items-center">
                                        <input 
                                            type="radio" 
                                            name="min_rating" 
                                            value="{{ $rating }}"
                                            {{ request('min_rating') == $rating ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                            onchange="document.getElementById('filter-form').submit()"
                                        >
                                        <div class="ml-2 flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-sm text-gray-600">& up</span>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Stock Status Filter -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Availability</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="stock_status" 
                                        value="in_stock"
                                        {{ request('stock_status') == 'in_stock' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                        onchange="document.getElementById('filter-form').submit()"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">In Stock</span>
                                </label>
                                <label class="flex items-center">
                                    <input 
                                        type="radio" 
                                        name="stock_status" 
                                        value="out_of_stock"
                                        {{ request('stock_status') == 'out_of_stock' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                        onchange="document.getElementById('filter-form').submit()"
                                    >
                                    <span class="ml-2 text-sm text-gray-700">Out of Stock</span>
                                </label>
                            </div>
                        </div>

                        <!-- Keep other parameters -->
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            @if(request('q'))
                                Search Results for "{{ request('q') }}"
                            @elseif(request('category'))
                                @php $category = \App\Models\Category::find(request('category')); @endphp
                                {{ $category->name ?? 'Products' }}
                            @else
                                All Products
                            @endif
                        </h1>
                        <p class="text-gray-600">{{ $totalProducts }} products found</p>
                    </div>

                    <!-- Sort Options -->
                    <div class="mt-4 sm:mt-0">
                        <select 
                            name="sort" 
                            onchange="updateSort(this.value)"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['category', 'brands', 'colors', 'textures', 'min_price', 'max_price', 'min_rating', 'stock_status']))
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Active Filters:</h3>
                        <div class="flex flex-wrap gap-2">
                            @if(request('category'))
                                @php $category = \App\Models\Category::find(request('category')); @endphp
                                @if($category)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                        Category: {{ $category->name }}
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-2 text-pink-600 hover:text-pink-800">×</a>
                                    </span>
                                @endif
                            @endif

                            @if(request('brands'))
                                @foreach((array)request('brands') as $brandId)
                                    @php $brand = \App\Models\Brand::find($brandId); @endphp
                                    @if($brand)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                            Brand: {{ $brand->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['brands' => array_diff((array)request('brands'), [$brandId])]) }}" class="ml-2 text-pink-600 hover:text-pink-800">×</a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif

                            @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                    Price: Rs. {{ request('min_price', 0) }} - Rs. {{ request('max_price', '∞') }}
                                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="ml-2 text-pink-600 hover:text-pink-800">×</a>
                                </span>
                            @endif

                            @if(request('min_rating'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                    Rating: {{ request('min_rating') }}+ stars
                                    <a href="{{ request()->fullUrlWithQuery(['min_rating' => null]) }}" class="ml-2 text-pink-600 hover:text-pink-800">×</a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            @include('components.shop.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- No Products Found -->
                    <div class="text-center py-12">
                        <div class="max-w-md mx-auto">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6M9 20h6a2 2 0 002-2V6a2 2 0 00-2-2H9a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-600 mb-4">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                Clear all filters
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateSort(sortValue) {
            const url = new URL(window.location);
            url.searchParams.set('sort', sortValue);
            window.location.href = url.toString();
        }

        // Mobile filter toggle
        function toggleMobileFilters() {
            const filterSidebar = document.querySelector('.lg\\:w-1\\/4');
            filterSidebar.classList.toggle('hidden');
        }

        // Auto-submit price filter on Enter
        document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('filter-form').submit();
                }
            });
        });
    </script>
@endpush