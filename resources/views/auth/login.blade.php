@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email Address -->
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" value="Password" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
<div class="block mt-4">
    <label for="remember" class="inline-flex items-center">
        <input id="remember" 
               type="checkbox" 
               class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" 
               name="remember" 
               value="1"
               {{ old('remember') ? 'checked' : '' }}>
        <span class="ml-2 text-sm text-gray-600">Remember me</span>
    </label>
</div>

    <div class="flex items-center justify-between mt-4">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
            Forgot your password?
        </a>

        <x-primary-button>
            Log in
        </x-primary-button>
    </div>

    <div class="mt-4 text-center">
        <span class="text-sm text-gray-600">Don't have an account?</span>
        <a class="text-sm text-primary-600 hover:text-primary-700" href="{{ route('register') }}">
            Register here
        </a>
    </div>
</form>
@endsection