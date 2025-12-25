@extends('layouts.app')

@section('title', 'Create Account - ChicChevron Beauty')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Create Account</h1>
                <p class="text-gray-500 mt-1">Join ChicChevron Beauty today</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter your full name"
                           required
                           autofocus
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('name') border-red-400 @enderror" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('email') border-red-400 @enderror" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone (Optional) -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Phone Number <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input id="phone"
                           type="tel"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="07X XXX XXXX"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('phone') border-red-400 @enderror" />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Create a password"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('password') border-red-400 @enderror" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           placeholder="Confirm your password"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent" />
                </div>

                <!-- Terms -->
                <p class="text-xs text-gray-500">
                    By creating an account, you agree to our
                    <a href="{{ route('terms') }}" class="text-plum-600 hover:text-plum-700">Terms</a>
                    and
                    <a href="{{ route('privacy') }}" class="text-plum-600 hover:text-plum-700">Privacy Policy</a>.
                </p>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-2.5 px-4 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-lg transition-colors">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-8 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-plum-600 hover:text-plum-700 font-semibold">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
