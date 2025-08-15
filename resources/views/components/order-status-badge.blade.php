@props(['status'])

@php
$statusConfig = [
    'payment_completed' => [
        'bg' => 'bg-blue-100',
        'text' => 'text-blue-800',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'label' => 'Confirmed'
    ],
    'processing' => [
        'bg' => 'bg-yellow-100',
        'text' => 'text-yellow-800',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'label' => 'Processing'
    ],
    'shipping' => [
        'bg' => 'bg-indigo-100',
        'text' => 'text-indigo-800',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>',
        'label' => 'Shipping'
    ],
    'completed' => [
        'bg' => 'bg-green-100',
        'text' => 'text-green-800',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
        'label' => 'Completed'
    ],
    'cancelled' => [
        'bg' => 'bg-red-100',
        'text' => 'text-red-800',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
        'label' => 'Cancelled'
    ],
];

$config = $statusConfig[$status] ?? [
    'bg' => 'bg-gray-100',
    'text' => 'text-gray-800',
    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'label' => ucfirst(str_replace('_', ' ', $status))
];
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $config['icon'] !!}
    </svg>
    {{ $config['label'] }}
</span>
