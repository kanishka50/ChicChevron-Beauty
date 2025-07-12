@extends('layouts.app')

@section('title', 'Security Settings - ChicChevron Beauty')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('user.account.index') }}" class="text-gray-500 hover:text-gray-700">My Account</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900">Security</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Security Settings</h1>
            <p class="mt-2 text-gray-600">Manage your password and account security</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Change Password Section -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>
                
                <form action="{{ route('user.account.security.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Current Password
                        </label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('current_password') border-red-300 @enderror"
                               required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            New Password
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 @error('password') border-red-300 @enderror"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Must be at least 8 characters with uppercase, lowercase letters and numbers
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm New Password
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500"
                               required>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                                class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Sessions -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Active Sessions</h2>
                <p class="text-sm text-gray-600 mb-4">
                    These devices are currently logged into your account. If you see an unfamiliar device, 
                    you should log out from all other sessions and change your password.
                </p>

                <div class="space-y-3">
                    @foreach($sessions as $session)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $session->is_current ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($session->device === 'Mobile')
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @elseif($session->device === 'Tablet')
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $session->browser }} on {{ $session->platform }}
                                        @if($session->is_current)
                                            <span class="ml-2 text-xs text-green-600">(This device)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Last active {{ $session->last_activity_human }}
                                        @if($session->ip_address)
                                            • IP: {{ $session->ip_address }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <form action="{{ route('user.account.logout-other-sessions') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('This will log you out of all other devices. Continue?');"
                                class="text-sm text-red-600 hover:text-red-700 font-medium">
                            Log Out Other Sessions
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Two-Factor Authentication</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Add an extra layer of security to your account by enabling two-factor authentication.
                </p>
                
                @if($user->two_factor_enabled)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Two-factor authentication is enabled</h3>
                                <p class="mt-1 text-sm text-green-700">Your account is protected with two-factor authentication.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('user.account.security.two-factor') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Enable Two-Factor Authentication
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-lg shadow border border-red-200">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-red-900 mb-4">Delete Account</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Once your account is deleted, all of your data will be permanently removed. 
                    This action cannot be undone.
                </p>
                
                <form action="{{ route('user.account.delete') }}" 
                      method="POST" 
                      onsubmit="return confirmAccountDeletion();"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete My Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmAccountDeletion() {
    const message = "Are you absolutely sure you want to delete your account? This action cannot be undone and all your data will be permanently lost.";
    if (confirm(message)) {
        return confirm("This is your final warning. Your account will be permanently deleted. Continue?");
    }
    return false;
}
</script>
@endsection