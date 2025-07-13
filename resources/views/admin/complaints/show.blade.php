@extends('admin.layouts.app')

@section('title', 'Complaint #' . $complaint->complaint_number)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.complaints.index') }}" class="text-primary-600 hover:text-primary-700 text-sm mb-2 inline-block">
            ← Back to Complaints
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                Complaint #{{ $complaint->complaint_number }}
            </h1>
            
            <!-- Status Update Form -->
            <form action="{{ route('admin.complaints.update-status', $complaint) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                @method('PATCH')
                <select name="status" class="rounded-md border-gray-300 text-sm">
                    <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded-md text-sm hover:bg-gray-700">
                    Update Status
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Complaint Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Complaint Details</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $complaint->user->name }} ({{ $complaint->user->email }})
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $complaint->complaint_type_label }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subject</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $complaint->subject }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $complaint->description }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Conversation Thread -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Conversation History</h2>
                
                @if($complaint->responses->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($complaint->responses as $response)
                            <div class="border-l-2 {{ $response->is_admin_response ? 'border-blue-500' : 'border-gray-300' }} pl-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium {{ $response->is_admin_response ? 'text-blue-600' : 'text-gray-600' }}">
                                        {{ $response->is_admin_response ? ($response->admin->name ?? 'Admin') : $response->user->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $response->created_at->format('M d, Y h:i A') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-900">{{ $response->message }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4 mb-6">No conversation history yet.</p>
                @endif

                <!-- Response Form -->
                <form action="{{ route('admin.complaints.respond', $complaint) }}" method="POST" class="border-t pt-4">
                    @csrf
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Response
                    </label>
                    <textarea id="message" 
                            name="message" 
                            rows="4" 
                            required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Type your response here..."></textarea>
                    
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                            Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Information (if applicable) -->
        <div class="lg:col-span-1">
            @if($complaint->order)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Related Order</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.orders.show', $complaint->order) }}" class="text-primary-600 hover:text-primary-700">
                                    #{{ $complaint->order->order_number }}
                                </a>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $complaint->order->created_at->format('M d, Y') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Order Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ ucwords(str_replace('_', ' ', $complaint->order->status)) }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                Rs. {{ number_format($complaint->order->total_amount, 2) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            @endif

            <!-- Quick Info -->
            <div class="bg-white shadow rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold mb-4">Quick Info</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Filed On</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $complaint->created_at->format('F d, Y h:i A') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $complaint->status_color }}">
                                {{ $complaint->status_label }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Responses</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $complaint->responses->count() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection