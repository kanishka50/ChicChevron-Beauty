@extends('layouts.app')

@section('title', 'Add New Address - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.account.index') }}" class="text-gray-500 hover:text-gray-700">My Account</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.account.addresses') }}" class="text-gray-500 hover:text-gray-700">Addresses</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">Add New</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Address</h1>
            <p class="mt-2 text-gray-600">Add a new delivery address to your account</p>
        </div>

        <!-- Address Form -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('user.account.addresses.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Address Line 1 -->
                <div>
                    <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1">
                        Address Line 1 <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="address_line_1" 
                           name="address_line_1" 
                           value="{{ old('address_line_1') }}"
                           placeholder="House/Building No, Street Name"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('address_line_1') border-red-300 @enderror"
                           required>
                    @error('address_line_1')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address Line 2 -->
                <div>
                    <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1">
                        Address Line 2 (Optional)
                    </label>
                    <input type="text" 
                           id="address_line_2" 
                           name="address_line_2" 
                           value="{{ old('address_line_2') }}"
                           placeholder="Apartment, Suite, Unit, etc."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('address_line_2') border-red-300 @enderror">
                    @error('address_line_2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="city" 
                               name="city" 
                               value="{{ old('city') }}"
                               placeholder="Colombo"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('city') border-red-300 @enderror"
                               required>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">
                            District <span class="text-red-500">*</span>
                        </label>
                        <select id="district" 
                                name="district" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('district') border-red-300 @enderror"
                                required>
                            <option value="">Select District</option>
                            <option value="Colombo" {{ old('district') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                            <option value="Gampaha" {{ old('district') == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                            <option value="Kalutara" {{ old('district') == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                            <option value="Kandy" {{ old('district') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                            <option value="Matale" {{ old('district') == 'Matale' ? 'selected' : '' }}>Matale</option>
                            <option value="Nuwara Eliya" {{ old('district') == 'Nuwara Eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                            <option value="Galle" {{ old('district') == 'Galle' ? 'selected' : '' }}>Galle</option>
                            <option value="Matara" {{ old('district') == 'Matara' ? 'selected' : '' }}>Matara</option>
                            <option value="Hambantota" {{ old('district') == 'Hambantota' ? 'selected' : '' }}>Hambantota</option>
                            <option value="Jaffna" {{ old('district') == 'Jaffna' ? 'selected' : '' }}>Jaffna</option>
                            <option value="Mannar" {{ old('district') == 'Mannar' ? 'selected' : '' }}>Mannar</option>
                            <option value="Vavuniya" {{ old('district') == 'Vavuniya' ? 'selected' : '' }}>Vavuniya</option>
                            <option value="Mullaitivu" {{ old('district') == 'Mullaitivu' ? 'selected' : '' }}>Mullaitivu</option>
                            <option value="Kilinochchi" {{ old('district') == 'Kilinochchi' ? 'selected' : '' }}>Kilinochchi</option>
                            <option value="Batticaloa" {{ old('district') == 'Batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                            <option value="Ampara" {{ old('district') == 'Ampara' ? 'selected' : '' }}>Ampara</option>
                            <option value="Trincomalee" {{ old('district') == 'Trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                            <option value="Kurunegala" {{ old('district') == 'Kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                            <option value="Puttalam" {{ old('district') == 'Puttalam' ? 'selected' : '' }}>Puttalam</option>
                            <option value="Anuradhapura" {{ old('district') == 'Anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                            <option value="Polonnaruwa" {{ old('district') == 'Polonnaruwa' ? 'selected' : '' }}>Polonnaruwa</option>
                            <option value="Badulla" {{ old('district') == 'Badulla' ? 'selected' : '' }}>Badulla</option>
                            <option value="Monaragala" {{ old('district') == 'Monaragala' ? 'selected' : '' }}>Monaragala</option>
                            <option value="Ratnapura" {{ old('district') == 'Ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                            <option value="Kegalle" {{ old('district') == 'Kegalle' ? 'selected' : '' }}>Kegalle</option>
                        </select>
                        @error('district')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Postal Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="postal_code" 
                               name="postal_code" 
                               value="{{ old('postal_code') }}"
                               placeholder="10100"
                               maxlength="5"
                               pattern="[0-9]{5}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('postal_code') border-red-300 @enderror"
                               required>
                        @error('postal_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Set as Default -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_default" 
                           name="is_default" 
                           value="1"
                           {{ old('is_default') ? 'checked' : '' }}
                           class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                    <label for="is_default" class="ml-2 text-sm text-gray-700">
                        Set as default delivery address
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('user.account.addresses') }}" class="text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                        Add Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection