<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;  // Change this line - use the correct base controller
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Wishlist;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class UserAccountController extends Controller
{
    

    /**
     * Display the user account dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['payment_completed', 'processing', 'shipping'])
                ->count(),
            'completed_orders' => Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('payment_status', 'completed')
                ->sum('total_amount'),
            'wishlist_count' => Wishlist::where('user_id', $user->id)->count(),
            'addresses_count' => UserAddress::where('user_id', $user->id)->count(),
            'complaints_count' => Complaint::where('user_id', $user->id)->count(),
        ];
        
        // Get recent orders with proper relationships
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.product' => function($query) {
                $query->select('id', 'name', 'slug', 'main_image');
            }])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate average order value
        $stats['average_order_value'] = $stats['total_orders'] > 0 
            ? $stats['total_spent'] / $stats['total_orders'] 
            : 0;
        
        return view('user.account.index', compact('user', 'stats', 'recentOrders'));
    }

    /**
     * Show the profile edit form
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.account.profile', compact('user'));
    }

    /**
 * Update user profile
 */
public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'],
        'current_password' => ['nullable', 'required_with:password', 'current_password'],
        'password' => ['nullable', 'confirmed', 'min:8'],
    ]);
    
    // Update using the User model directly
    $updateData = [
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
    ];
    
    // Add password to update data if provided
    if (!empty($validated['password'])) {
        $updateData['password'] = Hash::make($validated['password']);
    }
    
    // Update using the User model
    User::where('id', $user->id)->update($updateData);
    
    return redirect()->route('user.account.profile')
                    ->with('success', 'Profile updated successfully!');
}

    /**
     * Show user addresses
     */
    public function addresses()
    {
        $user = Auth::user();
        $addresses = UserAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.account.addresses', compact('addresses'));
    }

    /**
     * Create new address
     */
    public function createAddress()
    {
        return view('user.account.address-create');
    }

    /**
 * Store new address
 */
public function storeAddress(Request $request)
{
    $validated = $request->validate([
        'address_line_1' => ['required', 'string', 'max:255'],
        'address_line_2' => ['nullable', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:100'],
        'district' => ['required', 'string', 'max:100'],
        'postal_code' => ['nullable', 'string', 'max:10', 'regex:/^[0-9]+$/'],
        'is_default' => ['boolean'],
    ]);
    
    $validated['user_id'] = Auth::id();
    
    // If setting as default, unset other defaults
    if ($request->boolean('is_default')) {
        UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
    } else if (UserAddress::where('user_id', Auth::id())->count() === 0) {
        // If this is the first address, make it default
        $validated['is_default'] = true;
    }
    
    UserAddress::create($validated);
    
    return redirect()->route('user.account.addresses')
        ->with('success', 'Address added successfully!');
}

    /**
     * Edit address
     */
    public function editAddress(UserAddress $address)
    {
        // Ensure address belongs to user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.account.address-edit', compact('address'));
    }

    /**
     * Update address
     */
    public function updateAddress(Request $request, UserAddress $address)
{
    // Ensure address belongs to user
    if ($address->user_id !== Auth::id()) {
        abort(403);
    }
    
    $validated = $request->validate([
        'address_line_1' => ['required', 'string', 'max:255'],
        'address_line_2' => ['nullable', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:100'],
        'district' => ['required', 'string', 'max:100'],
        'postal_code' => ['nullable', 'string', 'max:10', 'regex:/^[0-9]+$/'],
        'is_default' => ['boolean'],
    ]);
    
    // If setting as default, unset other defaults
    if ($request->boolean('is_default') && !$address->is_default) {
        UserAddress::where('user_id', Auth::id())
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);
    }
    
    $address->update($validated);
    
    return redirect()->route('user.account.addresses')
        ->with('success', 'Address updated successfully!');
}

    /**
     * Delete address
     */
    public function deleteAddress(UserAddress $address)
    {
        // Ensure address belongs to user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Don't delete if it's the only address
        $userAddressCount = UserAddress::where('user_id', Auth::id())->count();
        
        if ($userAddressCount === 1) {
            return redirect()->route('user.account.addresses')
                ->with('error', 'You must have at least one address. Please add another address before deleting this one.');
        }
        
        // Check if any pending orders use this address
        $pendingOrdersWithAddress = Order::where('user_id', Auth::id())
            ->whereIn('status', ['payment_completed', 'processing', 'shipping'])
            ->where(function($query) use ($address) {
                $query->where('shipping_address_line_1', $address->address_line_1)
                      ->where('shipping_city', $address->city);
            })
            ->exists();
            
        if ($pendingOrdersWithAddress) {
            return redirect()->route('user.account.addresses')
                ->with('error', 'This address is being used in pending orders and cannot be deleted.');
        }
        
        // If deleting default, make another default
        if ($address->is_default) {
            $newDefault = UserAddress::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }
        
        $address->delete();
        
        return redirect()->route('user.account.addresses')
            ->with('success', 'Address deleted successfully!');
    }

    /**
     * Make an address default
     */
    public function makeDefaultAddress(UserAddress $address)
    {
        // Ensure address belongs to user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Unset all other defaults
        UserAddress::where('user_id', Auth::id())
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);
            
        // Set this as default
        $address->update(['is_default' => true]);
        
        return redirect()->route('user.account.addresses')
            ->with('success', 'Default address updated successfully!');
    }

    /**
     * Show security settings
     */
    public function security()
    {
        $user = Auth::user();
        
        // Get active sessions with basic browser detection
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->last_activity_human = \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                $session->is_current = $session->id === session()->getId();
                
                // Basic browser detection from user agent
                $userAgent = $session->user_agent ?? '';
                
                // Simple browser detection
                if (str_contains($userAgent, 'Chrome')) {
                    $session->browser = 'Chrome';
                } elseif (str_contains($userAgent, 'Firefox')) {
                    $session->browser = 'Firefox';
                } elseif (str_contains($userAgent, 'Safari')) {
                    $session->browser = 'Safari';
                } elseif (str_contains($userAgent, 'Edge')) {
                    $session->browser = 'Edge';
                } else {
                    $session->browser = 'Unknown';
                }
                
                // Simple platform detection
                if (str_contains($userAgent, 'Windows')) {
                    $session->platform = 'Windows';
                } elseif (str_contains($userAgent, 'Mac')) {
                    $session->platform = 'macOS';
                } elseif (str_contains($userAgent, 'Linux')) {
                    $session->platform = 'Linux';
                } elseif (str_contains($userAgent, 'Android')) {
                    $session->platform = 'Android';
                } elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
                    $session->platform = 'iOS';
                } else {
                    $session->platform = 'Unknown';
                }
                
                // Simple device detection
                if (str_contains($userAgent, 'Mobile')) {
                    $session->device = 'Mobile';
                } elseif (str_contains($userAgent, 'Tablet')) {
                    $session->device = 'Tablet';
                } else {
                    $session->device = 'Desktop';
                }
                
                return $session;
            });
            
        return view('user.account.security', compact('user', 'sessions'));
    }

    /**
     * Update password from security page
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ]);
        
        $user = Auth::user();
        
        // Update password using the User model
        User::where('id', $user->id)->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        // Log out other devices for security
        Auth::logoutOtherDevices($validated['password']);
        
        return redirect()->route('user.account.security')
            ->with('success', 'Password updated successfully! Other devices have been logged out.');
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        // Here you would implement 2FA logic
        // For now, we'll just show a placeholder message
        
        return redirect()->route('user.account.security')
            ->with('info', 'Two-factor authentication feature coming soon!');
    }

    /**
     * Logout from all other sessions
     */
    public function logoutOtherSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        
        Auth::logoutOtherDevices($request->password);
        
        // Delete other sessions from database
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();
        
        return redirect()->route('user.account.security')
            ->with('success', 'Successfully logged out from all other sessions!');
    }

    /**
     * Delete user account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'in:DELETE'],
        ], [
            'confirmation.in' => 'Please type DELETE to confirm account deletion.',
        ]);
        
        $user = Auth::user();
        
        // Check if user has any active orders
        $activeOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['payment_completed', 'processing', 'shipping'])
            ->exists();
            
        if ($activeOrders) {
            return redirect()->route('user.account.security')
                ->with('error', 'Cannot delete account while you have active orders. Please wait for all orders to be completed.');
        }
        
        // Mark user as deleted (soft delete if your User model uses SoftDeletes trait)
        // Otherwise, you might want to deactivate the account instead of deleting
        User::where('id', $user->id)->update([
            'email' => 'deleted_' . time() . '_' . $user->email,
            'is_active' => false, // Add this column to your users table if needed
            'deleted_at' => now(),
        ]);
        
        Auth::logout();
        
        return redirect()->route('home')
            ->with('success', 'Your account has been deleted successfully.');
    }
}