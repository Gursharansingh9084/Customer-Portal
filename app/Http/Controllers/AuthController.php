<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    /* To display login form*/
    public function showLoginForm()
    {
        return view('auth.login');
    }
    /* To Submit login form*/
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['error' => 'Invalid credentials.']);
        }

        $mfaToken = rand(100000, 999999);
        $user->update([
            'mfa_token' => $mfaToken,
            'mfa_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::raw("Your MFA Code: $mfaToken", function ($message) use ($user) {
            $message->to($user->email)->subject('MFA Verification Code');
        });

        session(['mfa_user_id' => $user->id]);

        return redirect()->route('mfa');
    }


    /* To display MFA form*/
    public function showMfaForm()
    {
        return view('auth.mfa');
    }

    /* To Submit MFA form*/
    public function verifyMfa(Request $request)
    {
        $request->validate(['mfa_code' => 'required|digits:6']);
    
        $user = User::find(session('mfa_user_id'));
    
        if (!$user || $user->mfa_token !== $request->mfa_code || Carbon::now()->gt($user->mfa_expires_at)) {
            return back()->with('error', 'Invalid or expired MFA token.');
        }
    
        $user->update([
            'mfa_token' => null,
            'mfa_expires_at' => null,
        ]);
    
        Auth::login($user);
    
        session()->forget('mfa_user_id');
    
        return redirect()->route('customers.index')->with('success', 'MFA verification successful.');
    }

    /* To resend MFA code*/
    public function resendMfaCode()
    {
        $user = User::find(session('mfa_user_id')) ?? Auth::user();
    
        if (!$user) {
            return back()->with('error', 'No user session found for MFA.');
        }
    
        $mfaToken = rand(100000, 999999);
        
        $user->update([
            'mfa_token' => $mfaToken,
            'mfa_expires_at' => Carbon::now()->addMinutes(10),
        ]);
    
        Mail::raw("Your MFA Code: $mfaToken", function ($message) use ($user) {
            $message->to($user->email)->subject('MFA Verification Code');
        });
    
        session(['mfa_user_id' => $user->id]);
    
        return back()->with('success', 'A new MFA code has been sent to your email.');
    }
    
    /* To logout*/
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
