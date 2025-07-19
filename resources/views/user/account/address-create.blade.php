<!-- SEPARATOR -->
---
<!-- ADD ADDRESS PAGE -->
@extends('layouts.app')

@section('title', 'Add New Address - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.account.addresses') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Add Address</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Breadcrumb -->
        <nav class="hidden lg:block mb-6 text-sm">
            <ol class="flex items-center space-x-1">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Home</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('user.account.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">My Account</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('user.account.addresses') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Addresses</a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Add New</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Address</h1>
            <p class="text-gray-600">Add a new delivery address to your account</p>
        </div>

        <!-- Address Form Card -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-primary-50 to-white border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Delivery Address Details</h2>
                </div>
            </div>

            <form action="{{ route('user.account.addresses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Address Lines -->
                <div class="space-y-4">
                    <div class="group">
                        <label for="address_line_1" class="form-label flex items-center justify-between">
                            Address Line 1
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="address_line_1" 
                                   name="address_line_1" 
                                   value="{{ old('address_line_1') }}"
                                   placeholder="House/Building No, Street Name"
                                   class="form-input pl-10 transition-all duration-200 @error('address_line_1') border-red-300 @enderror"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        </div>
                        @error('address_line_1')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="address_line_2" class="form-label">
                            Address Line 2
                            <span class="text-gray-400 text-xs font-normal ml-2">(Optional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="address_line_2" 
                                   name="address_line_2" 
                                   value="{{ old('address_line_2') }}"
                                   placeholder="Apartment, Suite, Unit, etc."
                                   class="form-input pl-10 transition-all duration-200 @error('address_line_2') border-red-300 @enderror">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        @error('address_line_2')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- City -->
                    <div class="group">
                        <label for="city" class="form-label flex items-center justify-between">
                            City
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="{{ old('city') }}"
                                   placeholder="Colombo"
                                   class="form-input pl-10 transition-all duration-200 @error('city') border-red-300 @enderror"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                        </div>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- District -->
                    <div class="group">
                        <label for="district" class="form-label flex items-center justify-between">
                            District
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="district" 
                                    name="district" 
                                    class="form-input pl-10 appearance-none transition-all duration-200 @error('district') border-red-300 @enderror"
                                    required>
                                <option value="">Select District</option>
                                <optgroup label="Western Province">
                                    <option value="Colombo" {{ old('district') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                                    <option value="Gampaha" {{ old('district') == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                                    <option value="Kalutara" {{ old('district') == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                                </optgroup>
                                <optgroup label="Central Province">
                                    <option value="Kandy" {{ old('district') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                                    <option value="Matale" {{ old('district') == 'Matale' ? 'selected' : '' }}>Matale</option>
                                    <option value="Nuwara Eliya" {{ old('district') == 'Nuwara Eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                                </optgroup>
                                <optgroup label="Southern Province">
                                    <option value="Galle" {{ old('district') == 'Galle' ? 'selected' : '' }}>Galle</option>
                                    <option value="Matara" {{ old('district') == 'Matara' ? 'selected' : '' }}>Matara</option>
                                    <option value="Hambantota" {{ old('district') == 'Hambantota' ? 'selected' : '' }}>Hambantota</option>
                                </optgroup>
                                <optgroup label="Other Districts">
                                    <option value="Jaffna" {{ old('district') == 'Jaffna' ? 'selected' : '' }}>Jaffna</option>
                                    <option value="Batticaloa" {{ old('district') == 'Batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                                    <option value="Ampara" {{ old('district') == 'Ampara' ? 'selected' : '' }}>Ampara</option>
                                    <option value="Trincomalee" {{ old('district') == 'Trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                                    <option value="Kurunegala" {{ old('district') == 'Kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                                    <option value="Anuradhapura" {{ old('district') == 'Anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                                    <option value="Ratnapura" {{ old('district') == 'Ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                                </optgroup>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('district')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postal Code -->
                    <div class="group">
                        <label for="postal_code" class="form-label">
                            Postal Code
                            <span class="text-gray-400 text-xs font-normal ml-2">(Optional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   value="{{ old('postal_code') }}"
                                   placeholder="10100"
                                   maxlength="5"
                                   pattern="[0-9]{5}"
                                   class="form-input pl-10 transition-all duration-200 @error('postal_code') border-red-300 @enderror">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('postal_code')
                            <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Set as Default -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" 
                               id="is_default" 
                               name="is_default" 
                               value="1"
                               {{ old('is_default') ? 'checked' : '' }}
                               class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                        <div>
                            <span class="text-sm font-medium text-gray-900">Set as default delivery address</span>
                            <p class="text-xs text-gray-600 mt-0.5">This address will be pre-selected during checkout</p>
                        </div>
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('user.account.addresses') }}" 
                       class="btn btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="btn btn-primary group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Add Address
                    </button>
                </div>
            </form>
        </div>
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
</style>
@endpush

@push('scripts')
<script>
// Add input animation
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.parentElement.classList.add('scale-[1.02]');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.parentElement.classList.remove('scale-[1.02]');
    });
});

// Postal code validation
document.getElementById('postal_code')?.addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
});
</script>
@endpush
@endsection