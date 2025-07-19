<!-- CREATE COMPLAINT PAGE -->
@extends('layouts.app')

@section('title', 'File a Complaint - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.complaints.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">File Complaint</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">File a Complaint</h1>
            <p class="text-gray-600">We're here to help resolve any issues you may have</p>
        </div>

        <form method="POST" action="{{ route('user.complaints.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <!-- Form Header -->
                <div class="p-6 bg-gradient-to-r from-orange-50 to-white border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900">Complaint Details</h2>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Order Selection -->
                    <div>
                        <label for="order_id" class="form-label">
                            Related Order
                            <span class="text-gray-400 text-xs font-normal ml-2">(Optional)</span>
                        </label>
                        <select id="order_id" 
                                name="order_id" 
                                class="form-input">
                            <option value="">-- No specific order --</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                    Order #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }} - Rs {{ number_format($order->total_amount, 0) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select an order if your complaint is related to a specific purchase</p>
                        @error('order_id')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complaint Type -->
                    <div>
                        <label for="complaint_type" class="form-label">
                            Complaint Type <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-400 transition-colors">
                                <input type="radio" 
                                       name="complaint_type" 
                                       value="product_not_received" 
                                       class="sr-only"
                                       {{ old('complaint_type') == 'product_not_received' ? 'checked' : '' }}>
                                <div class="flex flex-1">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">
                                            Product Not Received
                                        </span>
                                        <span class="mt-1 text-xs text-gray-500">
                                            Order was placed but not delivered
                                        </span>
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-primary-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-400 transition-colors">
                                <input type="radio" 
                                       name="complaint_type" 
                                       value="wrong_product" 
                                       class="sr-only"
                                       {{ old('complaint_type') == 'wrong_product' ? 'checked' : '' }}>
                                <div class="flex flex-1">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">
                                            Wrong Product
                                        </span>
                                        <span class="mt-1 text-xs text-gray-500">
                                            Received different item than ordered
                                        </span>
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-primary-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-400 transition-colors">
                                <input type="radio" 
                                       name="complaint_type" 
                                       value="damaged_product" 
                                       class="sr-only"
                                       {{ old('complaint_type') == 'damaged_product' ? 'checked' : '' }}>
                                <div class="flex flex-1">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">
                                            Damaged Product
                                        </span>
                                        <span class="mt-1 text-xs text-gray-500">
                                            Product arrived in damaged condition
                                        </span>
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-primary-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                            </label>

                            <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none hover:border-primary-400 transition-colors">
                                <input type="radio" 
                                       name="complaint_type" 
                                       value="other" 
                                       class="sr-only"
                                       {{ old('complaint_type') == 'other' ? 'checked' : '' }}>
                                <div class="flex flex-1">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900">
                                            Other Issue
                                        </span>
                                        <span class="mt-1 text-xs text-gray-500">
                                            Different type of complaint
                                        </span>
                                    </div>
                                </div>
                                <svg class="h-5 w-5 text-primary-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="absolute -inset-px rounded-lg border-2 pointer-events-none" aria-hidden="true"></div>
                            </label>
                        </div>
                        @error('complaint_type')
                            <p class="mt-2 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="form-label">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               required 
                               maxlength="255"
                               placeholder="Brief description of your issue"
                               class="form-input">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="form-label">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="6" 
                                  required
                                  maxlength="2000"
                                  placeholder="Please provide detailed information about your complaint. Include order details, dates, and any other relevant information..."
                                  class="form-input">{{ old('description') }}</textarea>
                        <div class="mt-1 flex justify-between text-xs">
                            <p class="text-gray-500">Be as detailed as possible to help us resolve your issue quickly</p>
                            <p class="text-gray-500"><span id="charCount">0</span>/2000</p>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <a href="{{ route('user.complaints.index') }}" class="btn btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l-.512 2.805a1 1 0 01-1.976 0L9 19m3 0h6m-6 0h-6m12 0l.512 2.805a1 1 0 001.976 0L15 19m0 0l2.485-13.647A2 2 0 0015.506 3H8.494a2 2 0 00-1.98 2.353L9 19"></path>
                        </svg>
                        Submit Complaint
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
    
    input[type="radio"]:checked ~ div svg {
        display: block;
    }
    
    input[type="radio"]:checked ~ .absolute {
        border-color: rgb(219, 39, 119);
    }
</style>
@endpush

@push('scripts')
<script>
// Character counter
const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

if (description && charCount) {
    description.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
    
    // Initialize on page load
    charCount.textContent = description.value.length;
}

// Radio button selection enhancement
document.querySelectorAll('input[type="radio"][name="complaint_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove selected state from all labels
        document.querySelectorAll('label').forEach(label => {
            const border = label.querySelector('.absolute');
            if (border) {
                border.style.borderColor = '';
            }
        });
        
        // Add selected state to current label
        if (this.checked) {
            const label = this.closest('label');
            const border = label.querySelector('.absolute');
            if (border) {
                border.style.borderColor = 'rgb(219, 39, 119)';
            }
        }
    });
});
</script>
@endpush
@endsection