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
        /* Enhanced animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        .auth-form {
            animation: slideUp 0.6s ease-out;
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        /* Background pattern */
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(236, 72, 153, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(236, 72, 153, 0.03) 0%, transparent 50%);
        }
        
        /* Glass effect for modern look */
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        /* Mobile optimizations */
        @media (max-width: 640px) {
            .auth-container {
                border-radius: 1.5rem 1.5rem 0 0;
                box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1);
            }
        }
        
        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        /* Toast animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .toast-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        .toast-exit {
            animation: slideOutRight 0.3s ease-out;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50 min-h-screen bg-pattern">
    <div class="min-h-screen flex flex-col justify-center py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <!-- Decorative Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
        
        <!-- Logo/Brand Section -->
        <div class="relative z-10 flex justify-center mb-6 sm:mb-8 auth-form">
            <a href="/" class="group transform transition-all duration-300 hover:scale-105">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full mb-4 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <span class="text-white font-bold text-2xl sm:text-3xl">CB</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent">
                        ChicChevron Beauty
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 font-medium">Premium Beauty Products</p>
                </div>
            </a>
        </div>

        <!-- Auth Card Container -->
        <div class="relative z-10 w-full max-w-md mx-auto auth-form" style="animation-delay: 0.1s;">
            <div class="auth-container glass-effect backdrop-blur-lg py-8 px-6 sm:px-10 shadow-2xl sm:rounded-2xl border border-white/20">
                <!-- Gradient Border Top -->
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-400 via-primary-600 to-purple-600 sm:rounded-t-2xl"></div>
                
                <!-- Page Title (if set) -->
                @if(View::hasSection('auth-title'))
                    <div class="mb-6 text-center">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                            @yield('auth-title')
                        </h2>
                        @if(View::hasSection('auth-subtitle'))
                            <p class="mt-2 text-sm text-gray-600 max-w-sm mx-auto">
                                @yield('auth-subtitle')
                            </p>
                        @endif
                    </div>
                @endif
                
                <!-- Main Content -->
                @yield('content')
            </div>
            
            <!-- Card Shadow Effect -->
            <div class="absolute inset-0 -z-10 bg-gradient-to-br from-primary-200/20 to-purple-200/20 blur-3xl"></div>
        </div>

        <!-- Footer Links -->
        <div class="relative z-10 mt-6 sm:mt-8 text-center space-y-4 auth-form" style="animation-delay: 0.2s;">
            <!-- Custom Footer Content -->
            @yield('footer')
            
            <!-- Common Footer Links -->
            <div class="text-xs sm:text-sm text-gray-600">
                <div class="flex items-center justify-center space-x-3 sm:space-x-4">
                    <a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors inline-flex items-center group">
                        <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="text-xs text-gray-500 mt-4">
                <p>&copy; {{ date('Y') }} ChicChevron Beauty. All rights reserved.</p>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-4 flex items-center justify-center text-xs text-gray-600 space-x-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Secure & Encrypted</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 left-4 sm:left-auto z-50 space-y-2 pointer-events-none max-w-sm mx-auto sm:mx-0">
        <!-- Toasts will be inserted here -->
    </div>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white rounded-2xl p-6 shadow-2xl transform transition-all">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full border-4 border-gray-200"></div>
                        <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-primary-600 border-t-transparent spinner"></div>
                    </div>
                    <span class="text-gray-900 font-medium">Processing...</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success Modal (hidden by default) -->
    <div id="success-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center h-full p-4">
            <div class="bg-white rounded-2xl p-8 shadow-2xl transform transition-all max-w-sm w-full">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2" id="success-modal-title">Success!</h3>
                    <p class="text-sm text-gray-600" id="success-modal-message">Your action was completed successfully.</p>
                    <button onclick="closeSuccessModal()" class="mt-4 btn btn-primary">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Enhanced toast function
        window.showToast = function(message, type = 'success', duration = 5000) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const bgColor = {
                'success': 'bg-gradient-to-r from-green-500 to-green-600',
                'warning': 'bg-gradient-to-r from-yellow-500 to-yellow-600',
                'error': 'bg-gradient-to-r from-red-500 to-red-600',
                'info': 'bg-gradient-to-r from-blue-500 to-blue-600'
            }[type] || 'bg-gradient-to-r from-gray-500 to-gray-600';
            
            const icon = {
                'success': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
                'warning': '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
                'error': '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
                'info': '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
            }[type];
            
            toast.className = `toast-enter pointer-events-auto ${bgColor} text-white rounded-xl shadow-lg overflow-hidden`;
            toast.innerHTML = `
                <div class="p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                ${icon}
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <button onclick="closeToast(this)" class="ml-4 flex-shrink-0 text-white/80 hover:text-white focus:outline-none transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white/20 h-1">
                    <div class="bg-white h-full transition-all duration-${duration}" style="animation: shrink ${duration}ms linear"></div>
                </div>
            `;

            toastContainer.appendChild(toast);

            // Auto remove after duration
            setTimeout(() => {
                closeToast(toast.querySelector('button'));
            }, duration);
        };
        
        // Close toast function
        window.closeToast = function(button) {
            const toast = button.closest('.toast-enter');
            toast.classList.remove('toast-enter');
            toast.classList.add('toast-exit');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        };
        
        // Loading overlay functions
        window.showLoading = function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        };
        
        window.hideLoading = function() {
            document.getElementById('loading-overlay').classList.add('hidden');
        };
        
        // Success modal functions
        window.showSuccessModal = function(title, message) {
            document.getElementById('success-modal-title').textContent = title;
            document.getElementById('success-modal-message').textContent = message;
            document.getElementById('success-modal').classList.remove('hidden');
        };
        
        window.closeSuccessModal = function() {
            document.getElementById('success-modal').classList.add('hidden');
        };
        
        // Enhanced form validation
        window.validateForm = function(formId) {
            const form = document.getElementById(formId);
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                const value = input.value.trim();
                const parent = input.closest('.form-group') || input.parentElement;
                
                if (!value) {
                    isValid = false;
                    input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.remove('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    
                    // Add error message if not exists
                    if (!parent.querySelector('.error-message')) {
                        const error = document.createElement('p');
                        error.className = 'error-message text-xs text-red-600 mt-1';
                        error.textContent = 'This field is required';
                        parent.appendChild(error);
                    }
                } else {
                    input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                    input.classList.add('border-gray-300', 'focus:border-primary-500', 'focus:ring-primary-500');
                    
                    // Remove error message if exists
                    const error = parent.querySelector('.error-message');
                    if (error) {
                        error.remove();
                    }
                }
            });
            
            return isValid;
        };
        
        // Auto-focus first input on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstInput = document.querySelector('input:not([type="hidden"]):not([type="submit"]):not([type="button"])');
            if (firstInput && !window.matchMedia('(max-width: 640px)').matches) {
                firstInput.focus();
            }
            
            // Add input animations
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('transform', 'scale-105');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('transform', 'scale-105');
                });
            });
        });
        
        // Progress bar animation for toasts
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shrink {
                from { width: 100%; }
                to { width: 0%; }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    @stack('scripts')
</body>
</html>