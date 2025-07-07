@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="mb-4 text-sm text-gray-600">
    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Email Address -->
    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="flex items-center justify-between mt-4">
        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
            Back to login
        </a>

        <x-primary-button>
            Email Password Reset Link
        </x-primary-button>
    </div>
</form>
@endsection