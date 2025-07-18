<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
     */
    public function verify(Request $request, $id, $hash)
    {
        // Log the verification attempt for debugging
        Log::info('Email verification attempt', [
            'id' => $id,
            'hash' => $hash,
            'expires' => $request->query('expires'),
            'current_time' => time(),
            'url' => $request->fullUrl()
        ]);

        // Find the user by ID
        $user = User::find($id);
        
        if (!$user) {
            Log::error('User not found for verification', ['id' => $id]);
            abort(403, 'Invalid verification link.');
        }
        
        // Verify the hash matches the user's email
        if (!hash_equals((string) $hash, sha1($user->email))) {
            Log::error('Hash mismatch for verification', [
                'expected' => sha1($user->email),
                'received' => $hash
            ]);
            abort(403, 'Invalid verification link.');
        }
        
        // Check expiration time
        $expires = $request->query('expires');
        if ($expires && time() > $expires) {
            Log::error('Verification link expired', [
                'expires' => $expires,
                'current_time' => time()
            ]);
            abort(403, 'Verification link has expired.');
        }
        
        // For development environment with ngrok, skip signature validation
        // In production, uncomment the following block
        /*
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link.');
        }
        */
        
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
            Log::info('User email verified successfully', ['user_id' => $user->id]);
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