@extends('layouts.app')

@section('title', 'Products - ChicChevron Beauty')
@section('description', 'Browse our complete collection of premium beauty products. Filter by brand, category, price, and more.')

@section('breadcrumbs')
    <!-- Modern Breadcrumbs -->
    <nav aria-label="Breadcrumb" class="mb-6">
        <ol class="flex items-center space-x-2 text-sm flex-wrap">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Home
                </a>
            </li>
            <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
            <li class="text-gray-900 font-medium">Products</li>
            @if(request('category'))
                @php $category = \App\Models\Category::find(request('category')); @endphp
                @if($category)
                    <li><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                    <li class="text-gray-900 font-medium">{{ $category->name }}</li>
                @endif
            @endif
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-4">
            <button onclick="toggleMobileFilters()" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 rounded-xl px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span class="font-medium">Filters</span>
                @if(request()->hasAny(['category', 'brands', 'min_price', 'max_price']))
                    <span class="bg-primary-600 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ count(array_filter(request()->all())) }}
                    </span>
                @endif
            </button>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            <!-- Sidebar Filters -->
            <aside id="filter-sidebar" class="hidden lg:block lg:w-80 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Filters
                        </h3>
                        @if(request()->hasAny(['category', 'brands', 'min_price', 'max_price']))
                            <a href="{{ route('products.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                Clear All
                            </a>
                        @endif
                    </div>

                    <form id="filter-form" method="GET" action="{{ route('products.index') }}">
                        <!-- Category Filter -->
                        @if($filters['categories']->isNotEmpty())
                            <div class="border-b border-gray-200 pb-6 mb-6">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center justify-between">
                                    <span>Category</span>
                                    <span class="text-xs text-gray-500">{{ $filters['categories']->count() }}</span>
                                </h4>
                                <div class="space-y-2.5">
                                    @foreach($filters['categories'] as $category)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 -m-2 rounded-lg transition-colors">
                                            <input 
                                                type="radio" 
                                                name="category" 
                                                value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'checked' : '' }}
                                                class="w-4 h-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <span class="ml-3 text-sm text-gray-700 flex-1">
                                                {{ $category->name }}
                                            </span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                {{ $category->products_count }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Brand Filter -->
                        @if($filters['brands']->isNotEmpty())
                            <div class="border-b border-gray-200 pb-6 mb-6">
                                <h4 class="font-semibold text-gray-900 mb-4 flex items-center justify-between">
                                    <span>Brand</span>
                                    <span class="text-xs text-gray-500">{{ $filters['brands']->count() }}</span>
                                </h4>
                                <div class="space-y-2.5 max-h-48 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
                                    @foreach($filters['brands'] as $brand)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 -m-2 rounded-lg transition-colors">
                                            <input 
                                                type="checkbox" 
                                                name="brands[]" 
                                                value="{{ $brand->id }}"
                                                {{ in_array($brand->id, (array)request('brands', [])) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                onchange="document.getElementById('filter-form').submit()"
                                            >
                                            <span class="ml-3 text-sm text-gray-700 flex-1">
                                                {{ $brand->name }}
                                            </span>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                {{ $brand->products_count }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Price Range Filter -->
                        <div class="border-b border-gray-200 pb-6 mb-6">
                            <h4 class="font-semibold text-gray-900 mb-4">Price Range</h4>
                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <label class="text-xs text-gray-600 mb-1 block">Min</label>
                                        <input 
                                            type="number" 
                                            name="min_price" 
                                            value="{{ request('min_price') }}"
                                            placeholder="0"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            min="0"
                                            max="{{ $filters['priceRange']['max'] }}"
                                        >
                                    </div>
                                    <div class="flex items-end pb-2">
                                        <span class="text-gray-400">—</span>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs text-gray-600 mb-1 block">Max</label>
                                        <input 
                                            type="number" 
                                            name="max_price" 
                                            value="{{ request('max_price') }}"
                                            placeholder="{{ $filters['priceRange']['max'] }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                            min="{{ $filters['priceRange']['min'] }}"
                                            max="{{ $filters['priceRange']['max'] }}"
                                        >
                                    </div>
                                </div>
                                <button 
                                    type="submit" 
                                    class="w-full bg-primary-600 text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors"
                                >
                                    Apply Price Filter
                                </button>
                            </div>
                        </div>

                        <!-- Keep other parameters -->
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Header -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                                @if($isSearchResult)
                                    Search Results for "{{ $searchQuery }}"
                                @elseif(request('category'))
                                    @php $category = \App\Models\Category::find(request('category')); @endphp
                                    {{ $category->name ?? 'Products' }}
                                @else
                                    All Products
                                @endif
                            </h1>
                            <p class="text-gray-600 text-sm mt-1">
                                <span class="font-medium">{{ $totalProducts }}</span> 
                                {{ $isSearchResult ? 'results' : 'products' }} found
                            </p>
                            
                            @if($isSearchResult && $totalProducts == 0)
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        No products found for your search. Try different keywords or browse our categories.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Sort Options -->
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <label class="text-sm text-gray-600 whitespace-nowrap">Sort by:</label>
                            <select 
                                name="sort" 
                                onchange="updateSort(this.value)"
                                class="flex-1 sm:flex-initial border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            >
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->hasAny(['category', 'brands', 'min_price', 'max_price', 'stock_status']))
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-900">Active Filters</h3>
                            <a href="{{ route('products.index') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                Clear All
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if(request('category'))
                                @php $category = \App\Models\Category::find(request('category')); @endphp
                                @if($category)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-700">
                                        <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                                        {{ $category->name }}
                                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 text-gray-400 hover:text-gray-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </span>
                                @endif
                            @endif

                            @if(request('brands'))
                                @foreach((array)request('brands') as $brandId)
                                    @php $brand = \App\Models\Brand::find($brandId); @endphp
                                    @if($brand)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-700">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                            {{ $brand->name }}
                                            <a href="{{ request()->fullUrlWithQuery(['brands' => array_diff((array)request('brands'), [$brandId])]) }}" class="ml-1 text-gray-400 hover:text-gray-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </a>
                                        </span>
                                    @endif
                                @endforeach
                            @endif

                            @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-700">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    Rs. {{ number_format(request('min_price', 0)) }} - {{ request('max_price') ? 'Rs. ' . number_format(request('max_price')) : '∞' }}
                                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="ml-1 text-gray-400 hover:text-gray-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Products Grid -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                            @include('components.shop.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    <!-- Modern Pagination -->
                    <div class="mt-12 flex items-center justify-center">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
                @else
                    <!-- No Products Found - Enhanced -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-600 mb-6">We couldn't find any products matching your criteria. Try adjusting your filters or explore our categories.</p>
                            
                            <div class="space-y-3">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-primary-600 text-white rounded-xl font-medium hover:bg-primary-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear All Filters
                                </a>
                                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Filter Sidebar -->
<div id="mobile-filter-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleMobileFilters()"></div>
<div id="mobile-filter-sidebar" class="fixed inset-y-0 left-0 w-full max-w-sm bg-white z-50 transform -translate-x-full transition-transform duration-300 lg:hidden">
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Filters</h3>
            <button onclick="toggleMobileFilters()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="overflow-y-auto h-full pb-20 px-4">
        <!-- Mobile Filter Form -->
        <form id="mobile-filter-form" method="GET" action="{{ route('products.index') }}" class="py-4">
            <!-- Category Filter -->
            @if($filters['categories']->isNotEmpty())
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center justify-between">
                        <span>Category</span>
                        <span class="text-xs text-gray-500">{{ $filters['categories']->count() }}</span>
                    </h4>
                    <div class="space-y-2.5">
                        @foreach($filters['categories'] as $category)
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 -m-2 rounded-lg transition-colors">
                                <input 
                                    type="radio" 
                                    name="category" 
                                    value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'checked' : '' }}
                                    class="w-4 h-4 border-gray-300 text-primary-600 focus:ring-primary-500"
                                    onchange="document.getElementById('mobile-filter-form').submit()"
                                >
                                <span class="ml-3 text-sm text-gray-700 flex-1">
                                    {{ $category->name }}
                                </span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    {{ $category->products_count }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Brand Filter -->
            @if($filters['brands']->isNotEmpty())
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center justify-between">
                        <span>Brand</span>
                        <span class="text-xs text-gray-500">{{ $filters['brands']->count() }}</span>
                    </h4>
                    <div class="space-y-2.5 max-h-48 overflow-y-auto">
                        @foreach($filters['brands'] as $brand)
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 -m-2 rounded-lg transition-colors">
                                <input 
                                    type="checkbox" 
                                    name="brands[]" 
                                    value="{{ $brand->id }}"
                                    {{ in_array($brand->id, (array)request('brands', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    onchange="document.getElementById('mobile-filter-form').submit()"
                                >
                                <span class="ml-3 text-sm text-gray-700 flex-1">
                                    {{ $brand->name }}
                                </span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    {{ $brand->products_count }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Price Range Filter -->
            <div class="border-b border-gray-200 pb-6 mb-6">
                <h4 class="font-semibold text-gray-900 mb-4">Price Range</h4>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="text-xs text-gray-600 mb-1 block">Min</label>
                            <input 
                                type="number" 
                                name="min_price" 
                                value="{{ request('min_price') }}"
                                placeholder="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                min="0"
                                max="{{ $filters['priceRange']['max'] }}"
                            >
                        </div>
                        <div class="flex items-end pb-2">
                            <span class="text-gray-400">—</span>
                        </div>
                        <div class="flex-1">
                            <label class="text-xs text-gray-600 mb-1 block">Max</label>
                            <input 
                                type="number" 
                                name="max_price" 
                                value="{{ request('max_price') }}"
                                placeholder="{{ $filters['priceRange']['max'] }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                min="{{ $filters['priceRange']['min'] }}"
                                max="{{ $filters['priceRange']['max'] }}"
                            >
                        </div>
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-primary-600 text-white py-2.5 px-4 rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors"
                    >
                        Apply Price Filter
                    </button>
                </div>
            </div>

            <!-- Keep other parameters -->
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        </form>

        <!-- Apply/Clear Buttons -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 flex gap-3">
            <button onclick="toggleMobileFilters()" class="flex-1 bg-primary-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-primary-700 transition-colors">
                Apply Filters
            </button>
            <a href="{{ route('products.index') }}" class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center">
                Clear All
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Scrollbar styles */
    .scrollbar-thin {
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }
    
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
        border-radius: 3px;
    }
    
    /* Mobile filter animation */
    #mobile-filter-sidebar.active {
        transform: translateX(0);
    }
    
    #mobile-filter-overlay.active {
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    function updateSort(sortValue) {
        const url = new URL(window.location);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    }

    // Enhanced mobile filter toggle
    function toggleMobileFilters() {
        const sidebar = document.getElementById('mobile-filter-sidebar');
        const overlay = document.getElementById('mobile-filter-overlay');
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        // Prevent body scroll when filter is open
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    // Auto-submit price filter on Enter
    document.querySelectorAll('input[name="min_price"], input[name="max_price"]').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('filter-form').submit();
            }
        });
    });

    // Smooth scroll to top when pagination changes
    if (window.location.search.includes('page=')) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Add loading state to form submission
    document.getElementById('filter-form').addEventListener('submit', function() {
        // Add loading indicator
        const buttons = this.querySelectorAll('button[type="submit"]');
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        });
    });
</script>
@endpush