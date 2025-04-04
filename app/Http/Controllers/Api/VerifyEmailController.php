<?php

namespace App\Http\Controllers\Api;

use App\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));
    
        if (!$user) {
            return redirect(env('FRONTEND_URL') . '/email-verification?status=error&message=User not found');
        }
    
        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONTEND_URL') . '/email-verification?status=success&message=Email already verified');
        }
    
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    
        return redirect(env('FRONTEND_URL') . '/email-verification?status=success&message=Email verified successfully');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 200);
        }

        event(new Registered($user));

        return response()->json(['message' => 'Verification email sent successfully. Please check your inbox.'], 200);
    }

}
