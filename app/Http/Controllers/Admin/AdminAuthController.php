<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = trim($request->email);
        $password = $request->password;

        // Search for admin by email, phone, or name
        $admin = User::where('role', 'admin')
            ->where(function ($query) use ($input) {
                $query->where('email', $input)
                      ->orWhere('phone', $input)
                      ->orWhere('name', 'like', "%{$input}%");
            })
            ->first();

        if ($admin && Hash::check($password, $admin->password)) {
            if ($admin->isSuspended()) {
                return redirect()->back()->with('error', 'আপনার অ্যাডমিন অ্যাকাউন্টটি স্থগিত করা রয়েছে।');
            }

            Auth::login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            AuditLog::log('Admin Login', "অ্যাডমিন {$admin->name} সিস্টেমে লগইন করেছেন।", $admin->id);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'অ্যাডমিন ড্যাশবোর্ডে স্বাগতম!');
        }

        return redirect()->back()->withInput()->with('error', 'অ্যাডমিন ইমেইল/ফোন নম্বর অথবা পাসওয়ার্ড সঠিক নয়।');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            AuditLog::log('Admin Logout', "অ্যাডমিন " . Auth::user()->name . " সিস্টেম থেকে লগআউট করেছেন।");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'আপনি অ্যাডমিন প্যানেল থেকে সফলভাবে লগআউট করেছেন।');
    }
}
