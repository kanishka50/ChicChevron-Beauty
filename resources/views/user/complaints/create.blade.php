@extends('layouts.app')

@section('title', 'Submit Complaint - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.complaints.index') }}" class="text-gray-500 hover:text-gray-700">My Complaints</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">Submit Complaint</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Submit a Complaint</h1>
            <p class="mt-2 text-gray-600">We're sorry to hear you're having an issue. Please provide details below.</p>
        </div>

        <!-- Complaint Form -->
        <form action="{{ route('user.complaints.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
                        Complaint Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('category') border-red-300 @enderror"
                            required>
                        <option value="">Select a category</option>
                        <option value="product" {{ old('category') == 'product' ? 'selected' : '' }}>Product Issue</option>
                        <option value="delivery" {{ old('category') == 'delivery' ? 'selected' : '' }}>Delivery Problem</option>
                        <option value="payment" {{ old('category') == 'payment' ? 'selected' : '' }}>Payment Issue</option>
                        <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>Customer Service</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Selection -->
                <div>
                    <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Related Order (Optional)
                    </label>
                    <select id="order_id" 
                            name="order_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('order_id') border-red-300 @enderror">
                        <option value="">Select an order if applicable</option>
                        @foreach($recentOrders as $order)
                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                Order #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }} - LKR {{ number_format($order->total_amount, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Select an order if your complaint is related to a specific purchase</p>
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="subject" 
                           name="subject" 
                           value="{{ old('subject') }}"
                           placeholder="Brief description of your issue"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('subject') border-red-300 @enderror"
                           required>
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description"
                              name="description" 
                              rows="6"
                              placeholder="Please provide detailed information about your complaint..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('description') border-red-300 @enderror"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Include as much detail as possible to help us resolve your issue quickly</p>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Priority Level
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="priority" 
                                   value="low" 
                                   {{ old('priority', 'low') == 'low' ? 'checked' : '' }}
                                   class="text-pink-600 focus:ring-pink-500">
                            <span class="ml-2">
                                <span class="font-medium">Low</span> - General feedback or minor issue
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="priority" 
                                   value="medium" 
                                   {{ old('priority') == 'medium' ? 'checked' : '' }}
                                   class="text-pink-600 focus:ring-pink-500">
                            <span class="ml-2">
                                <span class="font-medium">Medium</span> - Issue affecting your experience
                            </span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="priority" 
                                   value="high" 
                                   {{ old('priority') == 'high' ? 'checked' : '' }}
                                   class="text-pink-600 focus:ring-pink-500">
                            <span class="ml-2">
                                <span class="font-medium">High</span> - Urgent issue requiring immediate attention
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Attachments -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Attachments (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-pink-600 hover:text-pink-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-pink-500">
                                    <span>Upload files</span>
                                    <input id="attachments" 
                                           name="attachments[]" 
                                           type="file" 
                                           class="sr-only"
                                           multiple
                                           accept="image/*,.pdf">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB each</p>
                        </div>
                    </div>
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Preference -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Contact Method
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="contact_methods[]" 
                                   value="email" 
                                   {{ in_array('email', old('contact_methods', ['email'])) ? 'checked' : '' }}
                                   class="text-pink-600 focus:ring-pink-500">
                            <span class="ml-2">Email</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="contact_methods[]" 
                                   value="phone" 
                                   {{ in_array('phone', old('contact_methods', [])) ? 'checked' : '' }}
                                   class="text-pink-600 focus:ring-pink-500">
                            <span class="ml-2">Phone</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('user.complaints.index') }}" 
                       class="text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                        Submit Complaint
                    </button>
                </div>
            </div>
        </form>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Need Immediate Assistance?</h3>
            <p class="text-blue-700 mb-4">
                For urgent matters, you can reach our customer service team directly:
            </p>
            <div class="space-y-2 text-blue-700">
                <p class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Phone: +94 11 234 5678
                </p>
                <p class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email: support@chicchevron.lk
                </p>
                <p class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Hours: Monday - Saturday, 9:00 AM - 6:00 PM
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// File upload preview
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const fileList = files.map(file => `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`).join(', ');
    
    if (files.length > 0) {
        const helpText = e.target.closest('.flex').nextElementSibling;
        helpText.textContent = `Selected: ${fileList}`;
        helpText.classList.add('text-green-600');
    }
});
</script>
@endsection