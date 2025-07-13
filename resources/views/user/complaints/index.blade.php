@extends('layouts.app')

@section('title', 'My Complaints')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Complaints</h1>
        <a href="{{ route('user.complaints.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
            File New Complaint
        </a>
    </div>

    @if($complaints->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($complaints as $complaint)
                    <li>
                        <a href="{{ route('user.complaints.show', $complaint) }}" class="block hover:bg-gray-50 px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-primary-600 truncate">
                                            #{{ $complaint->complaint_number }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $complaint->status_color }}">
                                                {{ $complaint->status_label }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $complaint->subject }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Type: {{ $complaint->complaint_type_label }}
                                            @if($complaint->order)
                                                • Order: #{{ $complaint->order->order_number }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <p>Filed on {{ $complaint->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-4">
            {{ $complaints->links() }}
        </div>
    @else
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6 text-center">
                <p class="text-gray-500">You haven't filed any complaints yet.</p>
                <div class="mt-3">
                    <a href="{{ route('user.complaints.create') }}" class="text-primary-600 hover:text-primary-700">
                        File your first complaint
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection