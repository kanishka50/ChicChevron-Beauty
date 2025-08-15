@extends('admin.layouts.app')

@section('title', 'Complaint #' . $complaint->complaint_number)

@section('content')
<div class="container-fluid px-4 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.complaints.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Complaints
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-800">
                Complaint #{{ $complaint->complaint_number }}
            </h1>
            
            <!-- Status Update Form -->
            <form action="{{ route('admin.complaints.update-status', $complaint) }}" method="POST" class="flex items-center space-x-3">
                @csrf
                @method('PATCH')
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Complaint Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Complaint Details</h2>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-600 mb-1">Customer</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $complaint->user->name }} ({{ $complaint->user->email }})
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600 mb-1">Type</dt>
                        <dd class="text-sm text-gray-900">{{ $complaint->complaint_type_label }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600 mb-1">Subject</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $complaint->subject }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600 mb-1">Description</dt>
                        <dd class="text-sm text-gray-900 whitespace-pre-wrap bg-gray-50 rounded-md p-4">{{ $complaint->description }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Conversation Thread -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Conversation History</h2>
                
                @if($complaint->responses->count() > 0)
                    <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                        @foreach($complaint->responses as $response)
                            <div class="border-l-4 {{ $response->is_admin_response ? 'border-blue-400 bg-blue-50' : 'border-gray-300 bg-gray-50' }} pl-4 pr-4 py-3 rounded-r-md">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium {{ $response->is_admin_response ? 'text-blue-700' : 'text-gray-700' }}">
                                        {{ $response->is_admin_response ? ($response->admin->name ?? 'Admin') : $response->user->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $response->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-800">{{ $response->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-sm">No conversation history yet.</p>
                    </div>
                @endif

                <!-- Response Form -->
                <form action="{{ route('admin.complaints.respond', $complaint) }}" method="POST" class="border-t border-gray-200 pt-6">
                    @csrf
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Response
                    </label>
                    <textarea id="message" 
                            name="message" 
                            rows="4" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Type your response here..."></textarea>
                    
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                            Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Info</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Filed On</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $complaint->created_at->format('F d, Y h:i A') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Current Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $complaint->status_color }}">
                                {{ $complaint->status_label }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Total Responses</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $complaint->responses->count() }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Order Information (if applicable) -->
            @if($complaint->order)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Related Order</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Order Number</dt>
                            <dd class="text-sm mt-1">
                                <a href="{{ route('admin.orders.show', $complaint->order) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                                    #{{ $complaint->order->order_number }}
                                </a>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Order Date</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $complaint->order->created_at->format('M d, Y') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Order Status</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ ucwords(str_replace('_', ' ', $complaint->order->status)) }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Total Amount</dt>
                            <dd class="text-sm font-medium text-gray-900 mt-1">
                                Rs. {{ number_format($complaint->order->total_amount, 2) }}
                            </dd>
                        </div>
                    </dl>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.orders.show', $complaint->order) }}" 
                           class="text-sm font-medium text-blue-600 hover:text-blue-700 inline-flex items-center">
                            View Order Details
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection