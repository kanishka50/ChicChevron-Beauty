@extends('layouts.guest')

@section('title', 'Forgot Password - ChicChevron Beauty')

@section('content')
<div class="w-full max-w-md mx-auto">
    <!-- Logo/Brand -->
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Forgot Password?</h1>
        <p class="text-gray-600 mt-2">No worries, we'll send you reset instructions</p>
    </div>

    <!-- Description -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
        Enter your email address and we'll send you a password reset link to create a new password.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0016 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <!-- Reset Form Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" value="Email Address" class="text-sm font-medium text-gray-700 mb-2" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <x-text-input id="email" 
                                  class="block mt-1 w-full pl-10 pr-3 py-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                  type="email" 
                                  name="email" 
                                  :value="old('email')" 
                                  placeholder="your@email.com"
                                  required 
                                  autofocus />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <x-primary-button class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-lg shadow-lg transform transition-all duration-200 hover:scale-[1.02]">
                    Send Reset Link
                </x-primary-button>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium" href="{{ route('login') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Back to login
                </a>
            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-6 text-center text-sm text-gray-500">
        Didn't receive the email? Check your spam folder or 
        <a href="{{ route('contact') }}" class="text-primary-600 hover:text-primary-700 font-medium">contact support</a>
    </div>
</div>
@endsection