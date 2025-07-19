@extends('layouts.app')

@section('title', 'My Profile - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-primary-50/20 to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Mobile Header -->
        <div class="lg:hidden mb-6 bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between">
            <a href="{{ route('user.account.index') }}" class="touch-target">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">My Profile</h1>
            <div class="w-10"></div>
        </div>

        <!-- Desktop Breadcrumb -->
        <nav class="hidden lg:block mb-6 text-sm">
            <ol class="flex items-center space-x-1">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors">Home</a>
                </li>
                <li class="text-gray-400">/</li>
                <li>
                    <a href="{{ route('user.account.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">My Account</a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">Profile</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="hidden lg:block mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
            <p class="text-gray-600">Manage your personal information and security settings</p>
        </div>

        <!-- Profile Overview Card -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-6 mb-6 text-white shadow-lg">
            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <span class="text-3xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-white/90">{{ $user->email }}</p>
                    <p class="text-sm text-white/75 mt-1">Member since {{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <form action="{{ route('user.account.profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')

                <!-- Success Message -->
                @if(session('success'))
                    <div class="m-6 mb-0 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between animate-fadeIn">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Personal Information Section -->
                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Personal Information
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="group">
                            <label for="name" class="form-label flex items-center justify-between">
                                Full Name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="form-input pl-10 transition-all duration-200 @error('name') border-red-300 @enderror"
                                       required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="group">
                            <label for="email" class="form-label flex items-center justify-between">
                                Email Address
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="form-input pl-10 transition-all duration-200 @error('email') border-red-300 @enderror"
                                       required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="group lg:col-span-2">
                            <label for="phone" class="form-label">
                                Phone Number
                                <span class="text-gray-400 text-xs font-normal ml-2">(Optional)</span>
                            </label>
                            <div class="relative">
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+94 77 123 4567"
                                       class="form-input pl-10 transition-all duration-200 @error('phone') border-red-300 @enderror">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">For order updates and delivery notifications</p>
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-100 p-6 space-y-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                Security Settings
                            </h3>
                            <p class="text-sm text-gray-600 mt-1 ml-11">Change your password</p>
                        </div>
                        <button type="button" id="togglePasswordSection" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                            <span id="passwordToggleText">Change Password</span>
                        </button>
                    </div>

                    <div id="passwordSection" class="hidden space-y-6 animate-slideDown">
                        <!-- Current Password -->
                        <div class="group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="relative">
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="form-input pr-12 transition-all duration-200 @error('current_password') border-red-300 @enderror"
                                       autocomplete="current-password">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('current_password')"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="h-5 w-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="current_password_show">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg class="h-5 w-5 hidden transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="current_password_hide">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- New Password -->
                            <div class="group">
                                <label for="password" class="form-label">New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-input pr-12 transition-all duration-200 @error('password') border-red-300 @enderror"
                                           autocomplete="new-password">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('password')"
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="h-5 w-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_show">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg class="h-5 w-5 hidden transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_hide">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 animate-slideDown">{{ $message }}</p>
                                @enderror
                                
                                <!-- Password Strength Indicator -->
                                <div class="mt-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs text-gray-500">Password Strength</span>
                                        <span class="text-xs text-gray-500" id="passwordStrengthText">-</span>
                                    </div>
                                    <div class="h-1 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="passwordStrengthBar" class="h-full bg-gray-300 transition-all duration-300 ease-out" style="width: 0%"></div>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="group">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="form-input pr-12 transition-all duration-200"
                                           autocomplete="new-password">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('password_confirmation')"
                                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="h-5 w-5 transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_confirmation_show">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg class="h-5 w-5 hidden transition-all duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_confirmation_hide">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="hidden mt-1 text-sm animate-slideDown">
                                    <span class="text-green-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Passwords match
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                

                <!-- Form Actions -->
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <a href="{{ route('user.account.index') }}" class="btn btn-secondary text-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Account
                    </a>
                    <button type="submit" class="btn btn-primary group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    .animate-slideDown {
        animation: slideDown 0.3s ease-out;
    }
</style>
@endpush

@push('scripts')
<script>
// Toggle Password Visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const showIcon = document.getElementById(fieldId + '_show');
    const hideIcon = document.getElementById(fieldId + '_hide');
    
    if (field.type === 'password') {
        field.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        field.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}

// Toggle Password Section
document.getElementById('togglePasswordSection').addEventListener('click', function() {
    const section = document.getElementById('passwordSection');
    const toggleText = document.getElementById('passwordToggleText');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        toggleText.textContent = 'Cancel';
        this.classList.add('text-gray-600');
        this.classList.remove('text-primary-600');
    } else {
        section.classList.add('hidden');
        toggleText.textContent = 'Change Password';
        this.classList.remove('text-gray-600');
        this.classList.add('text-primary-600');
        // Clear password fields
        document.getElementById('current_password').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
    }
});

// Password Strength Checker
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    const strengthLevels = [
        { width: '0%', color: 'bg-gray-300', text: '-' },
        { width: '20%', color: 'bg-red-500', text: 'Very Weak' },
        { width: '40%', color: 'bg-orange-500', text: 'Weak' },
        { width: '60%', color: 'bg-yellow-500', text: 'Fair' },
        { width: '80%', color: 'bg-blue-500', text: 'Good' },
        { width: '100%', color: 'bg-green-500', text: 'Strong' }
    ];
    
    const level = strengthLevels[strength];
    strengthBar.style.width = level.width;
    strengthBar.className = `h-full transition-all duration-300 ease-out ${level.color}`;
    strengthText.textContent = level.text;
}

// Password Match Checker
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    const matchIndicator = document.getElementById('passwordMatch');
    
    if (confirmation && password === confirmation) {
        matchIndicator.classList.remove('hidden');
    } else {
        matchIndicator.classList.add('hidden');
    }
}

// Event Listeners
document.getElementById('password')?.addEventListener('input', function() {
    checkPasswordStrength(this.value);
    checkPasswordMatch();
});

document.getElementById('password_confirmation')?.addEventListener('input', checkPasswordMatch);

// Form Validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    
    // If trying to change password
    if (newPassword || confirmPassword || currentPassword) {
        if (!currentPassword) {
            e.preventDefault();
            alert('Please enter your current password to change your password.');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('New passwords do not match.');
            return;
        }
        
        if (newPassword && newPassword.length < 8) {
            e.preventDefault();
            alert('New password must be at least 8 characters long.');
            return;
        }
    }
});

// Add input animation
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.parentElement.classList.add('scale-[1.02]');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.parentElement.classList.remove('scale-[1.02]');
    });
});
</script>
@endpush
@endsection