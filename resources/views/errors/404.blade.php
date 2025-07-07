@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="text-6xl font-bold text-primary-600 mb-4">404</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Page Not Found</h1>
        <p class="text-gray-600 mb-8">Sorry, we couldn't find the page you're looking for.</p>
        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-block bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition">
                Go to Homepage
            </a>
            <div>
                <a href="{{ url()->previous() }}" class="text-primary-600 hover:text-primary-700">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection