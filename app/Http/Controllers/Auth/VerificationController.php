<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route('user.account.index')
            : view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     * 
     * This method handles the verification differently to ensure it works
     * even if the user is not logged in when clicking the link
     */
    public function verify(Request $request, $id, $hash)
    {
        // Find the user by ID
        $user = User::find($id);
        
        if (!$user) {
            abort(403, 'Invalid verification link.');
        }
        
        // Verify the hash matches the user's email
        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Invalid verification link.');
        }
        
        // Check if the signature is valid (for signed routes)
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }
        
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            // If user is not logged in, log them in
            if (!Auth::check()) {
                Auth::login($user);
            }
            
            return redirect()->route('user.account.index')
                ->with('message', 'Email already verified.');
        }
        
        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        // Log the user in if they're not already logged in
        if (!Auth::check()) {
            Auth::login($user);
        }
        
        return redirect()->route('user.account.index')
            ->with('verified', true)
            ->with('success', 'Your email has been verified successfully!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.account.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}