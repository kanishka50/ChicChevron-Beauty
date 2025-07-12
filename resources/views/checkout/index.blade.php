@extends('layouts.app')

@section('title', 'Checkout - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Checkout Forms -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_name') border-red-500 @enderror"
                                       required>
                                @error('customer_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone', Auth::user()->phone ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_phone') border-red-500 @enderror"
                                       required>
                                @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="customer_email" 
                                       name="customer_email" 
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('customer_email') border-red-500 @enderror"
                                       required>
                                @error('customer_email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Address</h2>
                        
                        @if($userAddresses->isNotEmpty())
                            <!-- Saved Addresses -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Select Delivery Address</label>
                                <div class="space-y-2">
                                    <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" 
                                               name="address_selection" 
                                               value="new"
                                               class="mt-1 text-pink-600 focus:ring-pink-500"
                                               checked
                                               onchange="useNewAddress()">
                                        <div class="flex-1">
                                            <div class="font-medium">Enter New Address</div>
                                            <div class="text-sm text-gray-500">Fill in the delivery details below</div>
                                        </div>
                                    </label>
                                    
                                    @foreach($userAddresses as $address)
                                        <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" 
                                                   name="address_selection" 
                                                   value="saved_{{ $address->id }}"
                                                   class="mt-1 text-pink-600 focus:ring-pink-500"
                                                   onchange="useSavedAddress({{ $address->toJson() }})">
                                            <div class="flex-1">
                                                <div class="font-medium">{{ $address->name }}</div>
                                                <div class="text-sm text-gray-600">{{ $address->phone }}</div>
                                                <div class="text-sm text-gray-600">{{ $address->address_line_1 }}</div>
                                                @if($address->address_line_2)
                                                    <div class="text-sm text-gray-600">{{ $address->address_line_2 }}</div>
                                                @endif
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->city }}, {{ $address->district }} {{ $address->postal_code }}
                                                </div>
                                                @if($address->is_default)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <hr class="mb-6">
                        @endif
                        
                        <!-- Address Form Fields -->
                        <div id="address-form" class="space-y-4">
                            <input type="hidden" name="saved_address_id" id="saved_address_id" value="">
                            
                            <div>
                                <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-2">
                                    Address Line 1 <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="address_line_1" 
                                       name="address_line_1" 
                                       value="{{ old('address_line_1') }}"
                                       placeholder="House/Building No, Street Name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('address_line_1') border-red-500 @enderror"
                                       required>
                                @error('address_line_1')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-2">
                                    Address Line 2 (Optional)
                                </label>
                                <input type="text" 
                                       id="address_line_2" 
                                       name="address_line_2" 
                                       value="{{ old('address_line_2') }}"
                                       placeholder="Apartment, Suite, Unit, etc."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('address_line_2') border-red-500 @enderror">
                                @error('address_line_2')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                        City <span class="text-red-500">*</span>
                                    </label>
                                    <select id="city" 
                                            name="city" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('city') border-red-500 @enderror"
                                            required>
                                        <option value="">Select City</option>
                                        @foreach(['Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya', 'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar', 'Mullaitivu', 'Vavuniya', 'Puttalam', 'Kurunegala', 'Anuradhapura', 'Polonnaruwa', 'Badulla', 'Moneragala', 'Ratnapura', 'Kegalle', 'Batticaloa', 'Ampara', 'Trincomalee'] as $city)
                                            <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
                                                {{ $city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="district" class="block text-sm font-medium text-gray-700 mb-2">
                                        District <span class="text-red-500">*</span>
                                    </label>
                                    <select id="district" 
                                            name="district" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('district') border-red-500 @enderror"
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
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Postal Code <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="postal_code" 
                                           name="postal_code" 
                                           value="{{ old('postal_code') }}"
                                           placeholder="00000"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 @error('postal_code') border-red-500 @enderror"
                                           required>
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Notes (Optional)
                                </label>
                                <textarea id="delivery_notes" 
                                          name="delivery_notes" 
                                          rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                                          placeholder="Any special delivery instructions">{{ old('delivery_notes') }}</textarea>
                            </div>

                            <!-- Save Address Option -->
                            <div class="mt-4">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           name="save_address" 
                                           value="1"
                                           class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                                           {{ old('save_address') ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">Save this address for future orders</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="space-y-3">
                            <!-- Cash on Delivery -->
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="cod" 
                                       class="mt-1 text-pink-600 focus:ring-pink-500"
                                       checked>
                                <div class="flex-1">
                                    <div class="font-medium">Cash on Delivery</div>
                                    <div class="text-sm text-gray-600">Pay when you receive your order (Max: Rs. 10,000)</div>
                                </div>
                            </label>

                            <!-- PayHere -->
                            <label class="flex items-start space-x-3 p-4 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="payhere" 
                                       class="mt-1 text-pink-600 focus:ring-pink-500">
                                <div class="flex-1">
                                    <div class="font-medium">Online Payment (PayHere)</div>
                                    <div class="text-sm text-gray-600">Pay securely with credit/debit cards</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Notes (Optional)</h2>
                        <textarea name="order_notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500"
                                  placeholder="Any special requests or notes for your order">{{ old('order_notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <!-- Order Items -->
                        <div class="space-y-3 mb-4">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->product_image }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 object-cover rounded">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</h3>
                                        @if($item->variant_details_formatted)
                                            <p class="text-xs text-gray-500">{{ $item->variant_details_formatted }}</p>
                                        @endif
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->total_price_formatted }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Totals -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span>{{ $cartSummary['subtotal_formatted'] }}</span>
                            </div>
                            
                            @if($cartSummary['discount_amount'] > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Discount</span>
                                    <span>-{{ $cartSummary['discount_formatted'] }}</span>
                                </div>
                            @endif
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span>{{ $cartSummary['shipping_formatted'] }}</span>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between text-lg font-medium">
                                    <span>Total</span>
                                    <span>{{ $cartSummary['total_formatted'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-6">
                            <label class="flex items-start space-x-2">
                                <input type="checkbox" 
                                       name="terms_accepted" 
                                       value="1"
                                       class="mt-1 text-pink-600 focus:ring-pink-500 @error('terms_accepted') border-red-500 @enderror"
                                       required>
                                <span class="text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-pink-600 hover:text-pink-700">Terms and Conditions</a> and <a href="#" class="text-pink-600 hover:text-pink-700">Privacy Policy</a>
                                </span>
                            </label>
                            @error('terms_accepted')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" 
                                class="w-full mt-6 bg-pink-600 text-white py-3 px-4 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 font-medium">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function useSavedAddress(address) {
    // Set saved address ID
    document.getElementById('saved_address_id').value = address.id;
    
    // Fill customer info
    document.getElementById('customer_name').value = address.name;
    document.getElementById('customer_phone').value = address.phone;
    
    // Fill address fields
    document.getElementById('address_line_1').value = address.address_line_1;
    document.getElementById('address_line_2').value = address.address_line_2 || '';
    document.getElementById('city').value = address.city;
    document.getElementById('district').value = address.district;
    document.getElementById('postal_code').value = address.postal_code;
    
    // Disable save address checkbox when using saved address
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.checked = false;
        saveCheckbox.disabled = true;
    }
    
    // Make form fields readonly
    const fields = ['customer_name', 'customer_phone', 'address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.readOnly = true;
            element.classList.add('bg-gray-100');
        }
    });
}

function useNewAddress() {
    // Clear saved address ID
    document.getElementById('saved_address_id').value = '';
    
    // Clear form fields
    const fields = ['address_line_1', 'address_line_2', 'city', 'district', 'postal_code'];
    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.value = '';
            element.readOnly = false;
            element.classList.remove('bg-gray-100');
        }
    });
    
    // Enable customer fields
    ['customer_name', 'customer_phone'].forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.readOnly = false;
            element.classList.remove('bg-gray-100');
        }
    });
    
    // Enable save address checkbox
    const saveCheckbox = document.querySelector('input[name="save_address"]');
    if (saveCheckbox) {
        saveCheckbox.disabled = false;
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
            // Don't prevent default - let form submit normally
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