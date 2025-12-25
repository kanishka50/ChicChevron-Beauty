@extends('layouts.app')

@section('title', 'Sign In - ChicChevron Beauty')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-500 mt-1">Sign in to continue shopping</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="you@example.com"
                           required
                           autofocus
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('email') border-red-400 @enderror" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-plum-600 hover:text-plum-700 font-medium">
                            Forgot password?
                        </a>
                    </div>
                    <input id="password"
                           type="password"
                           name="password"
                           placeholder="Enter your password"
                           required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-plum-500 focus:border-transparent @error('password') border-red-400 @enderror" />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember"
                           type="checkbox"
                           name="remember"
                           {{ old('remember') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-plum-600 focus:ring-plum-500" />
                    <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-2.5 px-4 bg-plum-700 hover:bg-plum-800 text-white font-semibold rounded-lg transition-colors">
                    Sign In
                </button>
            </form>

            <!-- Register Link -->
            <p class="mt-8 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-plum-600 hover:text-plum-700 font-semibold">Create one</a>
            </p>
        </div>
    </div>
</div>
@endsection
