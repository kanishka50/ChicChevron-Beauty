@extends('layouts.app')

@section('title', 'Checkout - ChicChevron Beauty')

@section('content')
<div class="container-responsive py-6 md:py-10">
    <!-- Breadcrumb -->
    <nav class="mb-6 md:mb-8">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li><a href="{{ route('home') }}" class="hover:text-plum-600">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('cart.index') }}" class="hover:text-plum-600">Cart</a></li>
            <li>/</li>
            <li class="text-gray-900 font-medium">Checkout</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Checkout</h1>
        <p class="text-gray-500 mt-1 text-sm">Complete your order</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
            <!-- Left Column - Checkout Forms -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Customer Information -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <span class="w-7 h-7 bg-plum-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                            Customer Information
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="customer_name"
                                       name="customer_name"
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('customer_name') border-red-500 @enderror"
                                       placeholder="Enter your full name"
                                       required>
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel"
                                       id="customer_phone"
                                       name="customer_phone"
                                       value="{{ old('customer_phone', Auth::user()->phone ?? '') }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('customer_phone') border-red-500 @enderror"
                                       placeholder="07X XXX XXXX"
                                       required>
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       id="customer_email"
                                       name="customer_email"
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('customer_email') border-red-500 @enderror"
                                       placeholder="your@email.com"
                                       required>
                                @error('customer_email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <span class="w-7 h-7 bg-plum-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                            Delivery Address
                        </h2>
                    </div>

                    <div class="p-6">
                        @if($userAddresses->isNotEmpty())
                            <!-- Saved Addresses -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Select Delivery Address</label>
                                <div class="space-y-3">
                                    <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl hover:border-plum-300 cursor-pointer transition-colors group">
                                        <input type="radio"
                                               name="address_selection"
                                               value="new"
                                               class="mt-1 text-plum-600 focus:ring-plum-500 w-4 h-4"
                                               checked
                                               onchange="useNewAddress()">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900 text-sm">Enter New Address</div>
                                            <div class="text-xs text-gray-500 mt-0.5">Fill in the delivery details below</div>
                                        </div>
                                    </label>

                                    @foreach($userAddresses as $address)
                                        <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl hover:border-plum-300 cursor-pointer transition-colors group">
                                            <input type="radio"
                                                   name="address_selection"
                                                   value="saved_{{ $address->id }}"
                                                   class="mt-1 text-plum-600 focus:ring-plum-500 w-4 h-4"
                                                   onchange="useSavedAddress({{ $address->toJson() }})">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-medium text-gray-900 text-sm">{{ $address->name }}</span>
                                                    @if($address->is_default)
                                                        <span class="text-xs text-plum-600 font-medium">Default</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $address->phone }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ $address->address_line_1 }}</div>
                                                @if($address->address_line_2)
                                                    <div class="text-xs text-gray-500">{{ $address->address_line_2 }}</div>
                                                @endif
                                                <div class="text-xs text-gray-500">
                                                    {{ $address->city }}, {{ $address->district }} {{ $address->postal_code }}
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <hr class="mb-6 border-gray-100">
                        @endif
                            
                        <!-- Address Form Fields -->
                        <div id="address-form" class="space-y-4">
                            <input type="hidden" name="saved_address_id" id="saved_address_id" value="">

                            <div>
                                <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Address Line 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="address_line_1"
                                       name="address_line_1"
                                       value="{{ old('address_line_1') }}"
                                       placeholder="House/Building No, Street Name"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('address_line_1') border-red-500 @enderror"
                                       required>
                                @error('address_line_1')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Address Line 2 <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <input type="text"
                                       id="address_line_2"
                                       name="address_line_2"
                                       value="{{ old('address_line_2') }}"
                                       placeholder="Apartment, Suite, Unit, etc."
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('address_line_2') border-red-500 @enderror">
                                @error('address_line_2')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        id="city"
                                        name="city"
                                        value="{{ old('city') }}"
                                        placeholder="Enter your city"
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                        required>
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="district" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        District <span class="text-red-500">*</span>
                                    </label>
                                    <select id="district"
                                            name="district"
                                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('district') border-red-500 @enderror"
                                            required>
                                        <option value="">Select District</option>
                                        @foreach(['Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya', 'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar', 'Mullaitivu', 'Vavuniya', 'Puttalam', 'Kurunegala', 'Anuradhapura', 'Polonnaruwa', 'Badulla', 'Moneragala', 'Ratnapura', 'Kegalle', 'Batticaloa', 'Ampara', 'Trincomalee'] as $district)
                                            <option value="{{ $district }}" {{ old('district') == $district ? 'selected' : '' }}>
                                                {{ $district }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('district')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Postal Code <span class="text-gray-400 text-xs">(Optional)</span>
                                    </label>
                                    <input type="text"
                                           id="postal_code"
                                           name="postal_code"
                                           value="{{ old('postal_code') }}"
                                           placeholder="00000"
                                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Delivery Notes <span class="text-gray-400 text-xs">(Optional)</span>
                                </label>
                                <textarea id="delivery_notes"
                                          name="delivery_notes"
                                          rows="2"
                                          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent"
                                          placeholder="Any special delivery instructions">{{ old('delivery_notes') }}</textarea>
                            </div>

                            <!-- Save Address Option -->
                            <div class="pt-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           name="save_address"
                                           value="1"
                                           class="w-4 h-4 rounded border-gray-300 text-plum-600 focus:ring-plum-500"
                                           {{ old('save_address') ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-600">Save this address for future orders</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <span class="w-7 h-7 bg-plum-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                            Payment Method
                        </h2>
                    </div>

                    <div class="p-6">
                        <!-- Cash on Delivery - Only Option -->
                        <div class="flex items-start gap-3 p-4 border-2 border-plum-200 bg-plum-50/30 rounded-xl">
                            <input type="hidden" name="payment_method" value="cod">
                            <div class="mt-0.5">
                                <svg class="w-5 h-5 text-plum-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">Cash on Delivery</span>
                                    <span class="text-xs text-green-600 font-medium">Available</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5">Pay when you receive your order</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden lg:sticky lg:top-4">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Order Summary</h2>
                    </div>

                    <div class="p-6">
                        <!-- Order Items -->
                        <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cartItems as $item)
                                <div class="flex items-start gap-3">
                                    <img src="{{ $item->product_image }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-14 h-14 object-cover rounded-lg flex-shrink-0 bg-gray-50">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 line-clamp-1">{{ $item->product->name }}</h3>
                                        @if($item->variant_details_formatted)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant_details_formatted }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 flex-shrink-0">
                                        {{ $item->total_price_formatted }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Totals -->
                        <div class="border-t border-gray-100 pt-4 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-medium text-gray-900">{{ $cartSummary['subtotal_formatted'] }}</span>
                            </div>

                            @if($cartSummary['discount_amount'] > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-green-600">Discount</span>
                                    <span class="font-medium text-green-600">-{{ $cartSummary['discount_formatted'] }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Shipping</span>
                                <span class="font-medium text-gray-900">{{ $cartSummary['shipping_formatted'] }}</span>
                            </div>

                            <div class="border-t border-gray-100 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-plum-700">{{ $cartSummary['total_formatted'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-6">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox"
                                       name="terms_accepted"
                                       value="1"
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-plum-600 focus:ring-plum-500 @error('terms_accepted') border-red-500 @enderror"
                                       required>
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-plum-600 hover:text-plum-700 underline">Terms and Conditions</a> and <a href="#" class="text-plum-600 hover:text-plum-700 underline">Privacy Policy</a>
                                </span>
                            </label>
                            @error('terms_accepted')
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit"
                                class="w-full mt-6 bg-plum-700 hover:bg-plum-800 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                            Place Order
                        </button>

                        <!-- Security Note -->
                        <p class="mt-4 text-center text-xs text-gray-400">
                            Your information is secure
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Loading Overlay -->
<div id="checkout-loading" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl p-8 shadow-xl">
        <div class="flex flex-col items-center gap-4">
            <div class="w-10 h-10 border-3 border-gray-200 border-t-plum-600 rounded-full animate-spin"></div>
            <span class="text-gray-700 font-medium">Processing your order...</span>
        </div>
    </div>
</div>

<script>
function useSavedAddress(address) {
    // Set saved address ID
    document.getElementById('saved_address_id').value = address.id;
    
    // DON'T change customer info - keep the logged-in user's details
    // Only fill address fields
    document.getElementById('address_line_1').value = address.address_line_1;
    document.getElementById('address_line_2').value = address.address_line_2 || '';
    document.getElementById('city').value = address.city;
    document.getElementById('district').value = address.district;
    document.getElementById('postal_code').value = address.postal_code || '';
    
    // Disable save address checkbox when using saved address
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.checked = false;
        saveCheckbox.disabled = true;
        saveCheckbox.closest('label').classList.add('opacity-60', 'cursor-not-allowed');
    }
    
    // Make ONLY address fields readonly (not customer fields)
    const fields = ['address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.readOnly = true;
            element.classList.add('bg-gray-50', 'cursor-not-allowed');
        }
    });
}

function useNewAddress() {
    // Clear saved address ID
    document.getElementById('saved_address_id').value = '';
    
    // Clear ONLY address fields (not customer fields)
    const fields = ['address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.value = '';
            element.readOnly = false;
            element.classList.remove('bg-gray-50', 'cursor-not-allowed');
        }
    });
    
    // Enable save address checkbox
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.disabled = false;
        saveCheckbox.closest('label').classList.remove('opacity-60', 'cursor-not-allowed');
    }
}

// Auto-select district based on city (common mapping for Sri Lanka)
document.getElementById('city')?.addEventListener('change', function() {
    const cityDistrictMap = {
        'Colombo': 'Colombo',
        'Gampaha': 'Gampaha',
        'Kalutara': 'Kalutara',
        'Kandy': 'Kandy',
        'Matale': 'Matale',
        'Nuwara Eliya': 'Nuwara Eliya',
        'Galle': 'Galle',
        'Matara': 'Matara',
        'Hambantota': 'Hambantota',
        'Jaffna': 'Jaffna',
        'Kilinochchi': 'Kilinochchi',
        'Mannar': 'Mannar',
        'Mullaitivu': 'Mullaitivu',
        'Vavuniya': 'Vavuniya',
        'Puttalam': 'Puttalam',
        'Kurunegala': 'Kurunegala',
        'Anuradhapura': 'Anuradhapura',
        'Polonnaruwa': 'Polonnaruwa',
        'Badulla': 'Badulla',
        'Moneragala': 'Moneragala',
        'Ratnapura': 'Ratnapura',
        'Kegalle': 'Kegalle',
        'Batticaloa': 'Batticaloa',
        'Ampara': 'Ampara',
        'Trincomalee': 'Trincomalee'
    };
    
    const district = cityDistrictMap[this.value];
    if (district) {
        document.getElementById('district').value = district;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    
    if (checkoutForm) {
        // Override fetch to block counter requests during checkout
        const originalFetch = window.fetch;
        let isSubmitting = false;
        
        checkoutForm.addEventListener('submit', function(e) {
            // Show loading overlay
            document.getElementById('checkout-loading').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            console.log('Checkout form submitting...');
            
            // Set flag
            isSubmitting = true;
            window._isCheckoutInProgress = true;
            
            // Override fetch to block counter requests
            window.fetch = function(url, options) {
                if (isSubmitting && (url.includes('/cart/count') || url.includes('/wishlist/count'))) {
                    console.log('Blocked counter request during checkout:', url);
                    return Promise.resolve({
                        json: () => Promise.resolve({ count: 0 }),
                        ok: true
                    });
                }
                return originalFetch.apply(this, arguments);
            };
            
            // Disable all event listeners temporarily
            const stopAllEvents = function(e) {
                if (isSubmitting) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                }
            };
            
            window.addEventListener('cart-updated', stopAllEvents, true);
            window.addEventListener('wishlist-updated', stopAllEvents, true);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span> Processing order...';
            }
            
            // Disable all links and buttons
            document.querySelectorAll('a, button').forEach(el => {
                if (!el.closest('#checkout-form')) {
                    el.style.pointerEvents = 'none';
                    el.style.opacity = '0.6';
                }
            });
            
            console.log('All AJAX and events blocked during checkout');
        });
    }
});

// Also override XMLHttpRequest for older code
(function() {
    const originalXHR = window.XMLHttpRequest;
    
    window.XMLHttpRequest = function() {
        const xhr = new originalXHR();
        const originalOpen = xhr.open;
        
        xhr.open = function(method, url) {
            if (window._isCheckoutInProgress && (url.includes('/cart/count') || url.includes('/wishlist/count'))) {
                console.log('Blocked XHR counter request during checkout:', url);
                // Return a dummy response
                this.send = function() {
                    Object.defineProperty(this, 'responseText', {
                        get: function() { return '{"count": 0}'; }
                    });
                    Object.defineProperty(this, 'readyState', {
                        get: function() { return 4; }
                    });
                    Object.defineProperty(this, 'status', {
                        get: function() { return 200; }
                    });
                    if (this.onreadystatechange) {
                        this.onreadystatechange();
                    }
                };
                return;
            }
            return originalOpen.apply(this, arguments);
        };
        
        return xhr;
    };
})();
</script>

@endsection