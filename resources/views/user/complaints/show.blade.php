@extends('layouts.app')

@section('title', 'Complaint #' . $complaint->complaint_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('user.complaints.index') }}" class="text-primary-600 hover:text-primary-700 text-sm mb-2 inline-block">
            ← Back to Complaints
        </a>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">
                Complaint #{{ $complaint->complaint_number }}
            </h1>
            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $complaint->status_color }}">
                {{ $complaint->status_label }}
            </span>
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Complaint Details</h2>
        
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Type</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->complaint_type_label }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Filed Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $complaint->created_at->format('F d, Y h:i A') }}</dd>
            </div>
            
            @if($complaint->order)
            <div>
                <dt class="text-sm font-medium text-gray-500">Related Order</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <a href="{{ route('user.orders.show', $complaint->order) }}" class="text-primary-600 hover:text-primary-700">
                        #{{ $complaint->order->order_number }}
                    </a>
                </dd>
            </div>
            @endif
        </dl>
        
        <div class="mt-4">
            <dt class="text-sm font-medium text-gray-500">Subject</dt>
            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $complaint->subject }}</dd>
        </div>
        
        <div class="mt-4">
            <dt class="text-sm font-medium text-gray-500">Description</dt>
            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $complaint->description }}</dd>
        </div>
    </div>

    <!-- Conversation Thread -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Conversation</h2>
        
        @if($complaint->responses->count() > 0)
            <div class="space-y-4">
                @foreach($complaint->responses as $response)
                    <div class="flex {{ $response->is_admin_response ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs lg:max-w-md {{ $response->is_admin_response ? 'bg-gray-100' : 'bg-primary-100' }} rounded-lg p-4">
                            <div class="flex items-center mb-1">
                                <span class="text-xs font-medium {{ $response->is_admin_response ? 'text-gray-600' : 'text-primary-600' }}">
                                    {{ $response->is_admin_response ? 'Support Team' : 'You' }}
                                </span>
                                <span class="text-xs text-gray-500 ml-2">
                                    {{ $response->created_at->format('M d, Y h:i A') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-900">{{ $response->message }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No responses yet.</p>
        @endif

        <!-- Response Form -->
        @if($complaint->status !== 'closed')
            <form method="POST" action="{{ route('user.complaints.respond', $complaint) }}" class="mt-6 border-t pt-6">
                @csrf
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Add a response
                </label>
                <textarea id="message" 
                          name="message" 
                          rows="3" 
                          required
                          maxlength="1000"
                          placeholder="Type your message here..."
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                        Send Response
                    </button>
                </div>
            </form>
        @else
            <div class="mt-6 border-t pt-6 text-center text-gray-500">
                This complaint is closed and cannot receive new responses.
            </div>
        @endif
    </div>
</div>
@endsection