@extends('layouts.app')

@section('title', 'My Complaints - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Complaints</h1>
                <p class="mt-2 text-gray-600">Track and manage your submitted complaints</p>
            </div>
            <a href="{{ route('user.complaints.create') }}" 
               class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Submit New Complaint
            </a>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <a href="{{ route('user.complaints.index') }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ !request('status') ? 'border-pink-500 text-pink-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Complaints
                    </a>
                    <a href="{{ route('user.complaints.index', ['status' => 'pending']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Pending
                    </a>
                    <a href="{{ route('user.complaints.index', ['status' => 'in_progress']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'in_progress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        In Progress
                    </a>
                    <a href="{{ route('user.complaints.index', ['status' => 'resolved']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'resolved' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Resolved
                    </a>
                    <a href="{{ route('user.complaints.index', ['status' => 'closed']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'closed' ? 'border-gray-500 text-gray-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Closed
                    </a>
                </nav>
            </div>
        </div>

        @if($complaints->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No complaints found</h3>
                <p class="mt-2 text-gray-500">
                    @if(request('status'))
                        You don't have any {{ str_replace('_', ' ', request('status')) }} complaints.
                    @else
                        You haven't submitted any complaints yet.
                    @endif
                </p>
                @if(!request('status'))
                    <div class="mt-6">
                        <a href="{{ route('user.complaints.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                            Submit a Complaint
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- Complaints List -->
            <div class="space-y-4">
                @foreach($complaints as $complaint)
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Complaint Header -->
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Complaint #{{ $complaint->complaint_number }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @switch($complaint->status)
                                                @case('pending')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('in_progress')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @case('resolved')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('closed')
                                                    bg-gray-100 text-gray-800
                                                    @break
                                            @endswitch
                                        ">
                                            {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ ucfirst($complaint->category) }}
                                        </span>
                                    </div>

                                    <!-- Order Info -->
                                    @if($complaint->order)
                                        <p class="text-sm text-gray-600 mb-2">
                                            Related to Order: 
                                            <a href="{{ route('user.orders.show', $complaint->order) }}" 
                                               class="text-blue-600 hover:text-blue-700 font-medium">
                                                #{{ $complaint->order->order_number }}
                                            </a>
                                        </p>
                                    @endif

                                    <!-- Subject -->
                                    <p class="text-gray-900 font-medium mb-2">{{ $complaint->subject }}</p>

                                    <!-- Description Preview -->
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $complaint->description }}</p>

                                    <!-- Dates -->
                                    <div class="mt-3 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>Submitted: {{ $complaint->created_at->format('M d, Y') }}</span>
                                        <span>•</span>
                                        <span>Last Updated: {{ $complaint->updated_at->format('M d, Y') }}</span>
                                    </div>

                                    <!-- Unread Responses Badge -->
                                    @if($complaint->responses()->where('is_read', false)->count() > 0)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded">
                                                {{ $complaint->responses()->where('is_read', false)->count() }} new response(s)
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="ml-4">
                                    <a href="{{ route('user.complaints.show', $complaint) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">
                                        View Details
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($complaints->hasPages())
                <div class="mt-8">
                    {{ $complaints->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection