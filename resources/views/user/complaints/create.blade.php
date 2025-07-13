@extends('layouts.app')

@section('title', 'File a Complaint')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">File a Complaint</h1>

    <form method="POST" action="{{ route('user.complaints.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow sm:rounded-lg p-6">
            <!-- Order Selection (Optional) -->
            <div>
                <label for="order_id" class="block text-sm font-medium text-gray-700">
                    Related Order (Optional)
                </label>
                <select id="order_id" name="order_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- No specific order --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            Order #{{ $order->order_number }} - {{ $order->created_at->format('M d, Y') }} - Rs. {{ number_format($order->total_amount, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('order_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Complaint Type -->
            <div class="mt-4">
                <label for="complaint_type" class="block text-sm font-medium text-gray-700">
                    Complaint Type <span class="text-red-500">*</span>
                </label>
                <select id="complaint_type" name="complaint_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Select complaint type --</option>
                    <option value="product_not_received" {{ old('complaint_type') == 'product_not_received' ? 'selected' : '' }}>
                        Product Not Received
                    </option>
                    <option value="wrong_product" {{ old('complaint_type') == 'wrong_product' ? 'selected' : '' }}>
                        Wrong Product Delivered
                    </option>
                    <option value="damaged_product" {{ old('complaint_type') == 'damaged_product' ? 'selected' : '' }}>
                        Damaged Product
                    </option>
                    <option value="other" {{ old('complaint_type') == 'other' ? 'selected' : '' }}>
                        Other Issue
                    </option>
                </select>
                @error('complaint_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div class="mt-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       value="{{ old('subject') }}"
                       required 
                       maxlength="255"
                       placeholder="Brief description of your issue"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="6" 
                          required
                          maxlength="2000"
                          placeholder="Please provide detailed information about your complaint..."
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Maximum 2000 characters</p>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('user.complaints.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                Cancel
            </a>
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                Submit Complaint
            </button>
        </div>
    </form>
</div>
@endsection