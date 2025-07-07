@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Today's Sales -->
    <x-admin.card
        title="Today's Sales"
        value="LKR {{ number_format($stats['today_sales'], 2) }}"
        color="green"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-slot>
    </x-admin.card>

    <!-- Orders Today -->
    <x-admin.card
        title="Orders Today"
        value="{{ $stats['today_orders'] }}"
        color="blue"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </x-slot>
    </x-admin.card>

    <!-- Low Stock Products -->
    <x-admin.card
        title="Low Stock Products"
        value="{{ $stats['low_stock'] }}"
        color="yellow"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </x-slot>
        <a href="{{ route('admin.products.index') }}?filter=low_stock" class="text-sm text-yellow-600 hover:text-yellow-800">
            View products →
        </a>
    </x-admin.card>

    <!-- Out of Stock -->
    <x-admin.card
        title="Out of Stock"
        value="{{ $stats['out_of_stock'] }}"
        color="red"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </x-slot>
        <a href="{{ route('admin.products.index') }}?filter=out_of_stock" class="text-sm text-red-600 hover:text-red-800">
            View products →
        </a>
    </x-admin.card>

    <!-- New Customers -->
    <x-admin.card
        title="New Customers Today"
        value="{{ $stats['new_customers'] }}"
        color="indigo"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </x-slot>
    </x-admin.card>

    <!-- Pending Orders -->
    <x-admin.card
        title="Pending Orders"
        value="{{ $stats['pending_orders'] }}"
        color="purple"
    >
        <x-slot name="icon">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </x-slot>
        <a href="{{ route('admin.orders.index') }}?status=payment_completed" class="text-sm text-purple-600 hover:text-purple-800">
            Process orders →
        </a>
    </x-admin.card>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.products.create') }}" class="bg-primary-600 text-white p-4 rounded-lg text-center hover:bg-primary-700 transition flex items-center justify-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Product
        </a>
        <a href="{{ route('admin.orders.index') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition flex items-center justify-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            View Orders
        </a>
        <a href="{{ route('admin.categories.create') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition flex items-center justify-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Add Category
        </a>
        <a href="{{ route('admin.reports.index') }}" class="bg-gray-600 text-white p-4 rounded-lg text-center hover:bg-gray-700 transition flex items-center justify-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            View Reports
        </a>
    </div>
</div>

<!-- Recent Activities -->
@if($recentActivities->count() > 0)
<div class="mt-8">
    <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h2>
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($recentActivities as $activity)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($activity['type'] === 'order')
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $activity['message'] }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $activity['time']->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @if($activity['link'])
                            <a href="{{ $activity['link'] }}" class="text-sm text-primary-600 hover:text-primary-900">
                                View →
                            </a>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard stats every 60 seconds
    setInterval(function() {
        fetch('{{ route('admin.dashboard.refresh') }}')
            .then(response => response.json())
            .then(data => {
                // Update the stats on the page
                // You can implement this based on your needs
                console.log('Stats refreshed:', data);
            });
    }, 60000);
</script>
@endpush