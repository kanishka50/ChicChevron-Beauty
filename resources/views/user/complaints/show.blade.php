@extends('layouts.app')

@section('title', 'Complaint Details - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.complaints.index') }}" class="text-gray-500 hover:text-gray-700">My Complaints</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">#{{ $complaint->complaint_number }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Complaint Details -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-gray-900">
                                Complaint #{{ $complaint->complaint_number }}
                            </h1>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
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
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $complaint->subject }}</h2>
                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($complaint->description)) !!}
                        </div>

                        @if($complaint->attachments && count($complaint->attachments) > 0)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Attachments</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($complaint->attachments as $attachment)
                                        <a href="{{ Storage::url($attachment) }}" 
                                           target="_blank"
                                           class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">Attachment {{ $loop->iteration }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Responses -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Communication History</h2>
                    </div>
                    
                    <div class="p-6">
                        @if($complaint->responses->isEmpty())
                            <p class="text-gray-500 text-center py-8">No responses yet. We'll update you soon.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($complaint->responses as $response)
                                    <div class="flex space-x-3 {{ $response->responder_type === 'admin' ? '' : 'flex-row-reverse space-x-reverse' }}">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $response->responder_type === 'admin' ? 'bg-pink-100' : 'bg-gray-100' }}">
                                                @if($response->responder_type === 'admin')
                                                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 {{ $response->responder_type === 'admin' ? '' : 'text-right' }}">
                                            <div class="inline-block {{ $response->responder_type === 'admin' ? 'bg-gray-100' : 'bg-pink-100' }} rounded-lg px-4 py-2 max-w-sm">
                                                <p class="text-sm text-gray-800">{!! nl2br(e($response->message)) !!}</p>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $response->responder_type === 'admin' ? 'Support Team' : 'You' }} • 
                                                {{ $response->created_at->format('M d, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Reply Form -->
                        @if(!in_array($complaint->status, ['resolved', 'closed']))
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <form action="{{ route('user.complaints.respond', $complaint) }}" method="POST">
                                    @csrf
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                            Add a response
                                        </label>
                                        <textarea id="message"
                                                  name="message" 
                                                  rows="3"
                                                  placeholder="Type your message here..."
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                                                  required></textarea>
                                    </div>
                                    <div class="mt-3 flex justify-end">
                                        <button type="submit" 
                                                class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                                            Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Complaint Info -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Complaint Information</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Category</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($complaint->category) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Priority</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium 
                                        @if($complaint->priority === 'high')
                                            bg-red-100 text-red-800
                                        @elseif($complaint->priority === 'medium')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-green-100 text-green-800
                                        @endif
                                    ">
                                        {{ ucfirst($complaint->priority) }} Priority
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Submitted</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $complaint->created_at->format('F d, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Last Updated</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $complaint->updated_at->format('F d, Y \a\t g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Related Order -->
                @if($complaint->order)
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Related Order</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm">
                                <p class="font-medium text-gray-900">#{{ $complaint->order->order_number }}</p>
                                <p class="text-gray-600 mt-1">{{ $complaint->order->created_at->format('F d, Y') }}</p>
                                <p class="text-gray-600">LKR {{ number_format($complaint->order->total_amount, 2) }}</p>
                            </div>
                            <a href="{{ route('user.orders.show', $complaint->order) }}" 
                               class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                View Order Details
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                @if($complaint->status === 'resolved')
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <p class="text-sm text-gray-600 mb-4">
                                Is your issue resolved to your satisfaction?
                            </p>
                            <form action="{{ route('user.complaints.close', $complaint) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    Close Complaint
                                </button>
                            </form>
                            <form action="{{ route('user.complaints.reopen', $complaint) }}" method="POST" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                    Reopen Complaint
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Mark responses as read when page loads
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("user.complaints.mark-responses-read", $complaint) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });
});
</script>
@endsection