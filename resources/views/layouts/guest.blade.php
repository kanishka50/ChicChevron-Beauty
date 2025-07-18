<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ec4899">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>@yield('title', config('app.name', 'ChicChevron Beauty'))</title>
    
    <meta name="description" content="@yield('description', 'Join ChicChevron Beauty - Your trusted source for premium beauty products in Sri Lanka')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Additional mobile-optimized styles for auth pages */
        @media (max-width: 640px) {
            .auth-container {
                border-radius: 0;
                box-shadow: none;
                border-top: 4px solid #ec4899;
            }
        }
        
        /* Custom animation for auth forms */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .auth-form {
            animation: slideUp 0.5s ease-out;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="min-h-screen flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo/Brand Section -->
        <div class="flex justify-center mb-6 sm:mb-8 auth-form">
            <a href="/" class="group">
                <div class="text-center">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-primary-600 group-hover:text-primary-700 transition-colors duration-200">
                        ChicChevron Beauty
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Premium Beauty Products</p>
                </div>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="w-full max-w-md mx-auto auth-form" style="animation-delay: 0.1s;">
            <div class="auth-container bg-white py-8 px-6 sm:px-10 shadow-xl sm:rounded-xl">
                <!-- Page Title (if set) -->
                @if(View::hasSection('auth-title'))
                    <div class="mb-6 text-center">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                            @yield('auth-title')
                        </h2>
                        @if(View::hasSection('auth-subtitle'))
                            <p class="mt-2 text-sm text-gray-600">
                                @yield('auth-subtitle')
                            </p>
                        @endif
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 sm:mt-8 text-center space-y-4 auth-form" style="animation-delay: 0.2s;">
            <!-- Custom Footer Content -->
            @yield('footer')
            
            <!-- Common Footer Links -->
            <div class="text-xs sm:text-sm text-gray-500">
                <div class="flex items-center justify-center space-x-2 sm:space-x-4">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Home
                    </a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('about') }}" class="hover:text-primary-600 transition-colors">About</a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('contact') }}" class="hover:text-primary-600 transition-colors">Contact</a>
                    <span class="text-gray-400">•</span>
                    <a href="{{ route('privacy') }}" class="hover:text-primary-600 transition-colors">Privacy</a>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="text-xs text-gray-400 mt-4">
                <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-4 flex items-center justify-center text-xs text-gray-500">
                <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Secure & Encrypted
            </div>
        </div>
    </div>
    
    <!-- Toast Container for Auth Pages -->
    <div id="toast-container" class="fixed bottom-4 right-4 left-4 sm:left-auto z-50 space-y-2 pointer-events-none">
        <!-- Toasts will be inserted here -->
    </div>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white rounded-lg p-6 shadow-xl">
                <div class="flex items-center">
                    <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-3 text-gray-900 font-medium">Processing...</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Toast function for auth pages
        window.showToast = function(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            toast.className = `transform transition-all duration-300 p-4 rounded-lg shadow-lg pointer-events-auto ${
                type === 'success' ? 'bg-green-600' : type === 'warning' ? 'bg-yellow-600' : 'bg-red-600'
            } text-white max-w-sm w-full sm:w-auto ml-auto`;
            
            const icon = type === 'success' 
                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>'
                : type === 'warning'
                ? '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>'
                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>';
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        ${icon}
                    </svg>
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Trigger animation
            setTimeout(() => {
                toast.style.transform = 'translateY(-10px)';
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        };
        
        // Loading overlay functions
        window.showLoading = function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        };
        
        window.hideLoading = function() {
            document.getElementById('loading-overlay').classList.add('hidden');
        };
        
        // Form validation helper
        window.validateForm = function(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            return isValid;
        };
        
        // Auto-focus first input on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('input:not([type="hidden"]):not([type="submit"])');
            if (firstInput) {
                firstInput.focus();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>