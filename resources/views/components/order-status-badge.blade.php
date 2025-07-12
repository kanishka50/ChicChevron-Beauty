{{-- resources/views/components/order-status-badge.blade.php --}}
<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
    @switch($status)
        @case('payment_completed')
            bg-blue-100 text-blue-800
            @break
        @case('processing')
            bg-yellow-100 text-yellow-800
            @break
        @case('shipping')
            bg-indigo-100 text-indigo-800
            @break
        @case('completed')
            bg-green-100 text-green-800
            @break
        @case('cancelled')
            bg-red-100 text-red-800
            @break
        @default
            bg-gray-100 text-gray-800
    @endswitch
">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>