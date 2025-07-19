@extends('layouts.app')

@section('title', 'Complaint #' . $complaint->complaint_number . ' - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.complaints.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">#{{ $complaint->complaint_number }}</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block mb-6">
            <a href="{{ route('user.complaints.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Complaints
            </a>
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">
                    Complaint #{{ $complaint->complaint_number }}
                </h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $complaint->status_color }}">
                    {{ $complaint->status_label }}
                </span>
            </div>
        </div>

        <!-- Complaint Details Card -->
        <div class="bg-white rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-orange-50 to-white border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    Complaint Details
                </h2>
            </div>
            
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $complaint->complaint_type_label }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Filed Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $complaint->created_at->format('F d, Y h:i A') }}</dd>
                    </div>
                    
                    @if($complaint->order)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Related Order</dt>
                        <dd class="mt-1 text-sm">
                            <a href="{{ route('user.orders.show', $complaint->order) }}" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center">
                                #{{ $complaint->order->order_number }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </dd>
                    </div>
                    @endif
                    
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Subject</dt>
                        <dd class="mt-1 text-base font-medium text-gray-900">{{ $complaint->subject }}</dd>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-700 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $complaint->description }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Conversation Thread -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    Conversation
                </h2>
            </div>
            
            <div class="p-6">
                @if($complaint->responses->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($complaint->responses as $response)
                            <div class="flex {{ $response->is_admin_response ? 'justify-start' : 'justify-end' }}">
                                <div class="max-w-xs sm:max-w-md lg:max-w-lg">
                                    <div class="flex items-center mb-1 {{ $response->is_admin_response ? '' : 'justify-end' }}">
                                        <span class="text-xs font-medium {{ $response->is_admin_response ? 'text-gray-600' : 'text-primary-600' }}">
                                            {{ $response->is_admin_response ? 'Support Team' : 'You' }}
                                        </span>
                                        <span class="text-xs text-gray-500 ml-2">
                                            {{ $response->created_at->format('M d, h:i A') }}
                                        </span>
                                    </div>
                                    <div class="{{ $response->is_admin_response ? 'bg-gray-100' : 'bg-primary-100' }} rounded-2xl px-4 py-3">
                                        <p class="text-sm text-gray-900">{{ $response->message }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm">No responses yet. We'll get back to you soon!</p>
                    </div>
                @endif

                <!-- Response Form -->
                @if($complaint->status !== 'closed')
                    <form method="POST" action="{{ route('user.complaints.respond', $complaint) }}" class="border-t border-gray-200 pt-6">
                        @csrf
                        <label for="message" class="form-label">
                            Add a response
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="3" 
                                  required
                                  maxlength="1000"
                                  placeholder="Type your message here..."
                                  class="form-input"></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="btn btn-primary group">
                                <svg class="w-4 h-4 mr-2 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Send Response
                            </button>
                        </div>
                    </form>
                @else
                    <div class="border-t border-gray-200 pt-6 text-center">
                        <div class="inline-flex items-center space-x-2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm">This complaint is closed and cannot receive new responses.</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
