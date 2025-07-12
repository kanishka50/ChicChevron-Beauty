@extends('layouts.app')

@section('title', 'My Addresses - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.account.index') }}" class="text-gray-500 hover:text-gray-700">My Account</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">Addresses</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
                <p class="mt-2 text-gray-600">Manage your delivery addresses</p>
            </div>
            <a href="{{ route('user.account.addresses.create') }}" 
               class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Address
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($addresses->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No addresses saved</h3>
                <p class="mt-2 text-gray-500">Add your first delivery address to make checkout faster.</p>
                <div class="mt-6">
                    <a href="{{ route('user.account.addresses.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                        Add Your First Address
                    </a>
                </div>
            </div>
        @else
            <!-- Address Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white rounded-lg shadow border {{ $address->is_default ? 'border-pink-500' : 'border-gray-200' }}">
                        <!-- Default Badge -->
                        @if($address->is_default)
                            <div class="px-6 py-2 bg-pink-50 border-b border-pink-200">
                                <span class="text-sm font-medium text-pink-700">Default Address</span>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Address Type -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($address->type) }}
                                </span>
                                @if(!$address->is_default)
                                    <form action="{{ route('user.account.addresses.default', $address) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-700">
                                            Set as default
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Contact Info -->
                            <h3 class="font-semibold text-gray-900">{{ $address->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $address->phone }}</p>

                            <!-- Address -->
                            <div class="mt-3 text-sm text-gray-600">
                                <p>{{ $address->address_line_1 }}</p>
                                @if($address->address_line_2)
                                    <p>{{ $address->address_line_2 }}</p>
                                @endif
                                <p>{{ $address->city }}, {{ $address->district }}</p>
                                @if($address->postal_code)
                                    <p>{{ $address->postal_code }}</p>
                                @endif
                            </div>

                            @if($address->landmark)
                                <p class="mt-2 text-sm text-gray-500">
                                    <span class="font-medium">Landmark:</span> {{ $address->landmark }}
                                </p>
                            @endif

                            <!-- Actions -->
                            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                                <a href="{{ route('user.account.addresses.edit', $address) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Edit
                                </a>
                                @if(!$address->is_default)
                                    <form action="{{ route('user.account.addresses.delete', $address) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this address?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection