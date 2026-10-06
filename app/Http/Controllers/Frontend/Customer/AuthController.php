<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
        return view('frontend.customer.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $fieldType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->isSuspended()) {
                Auth::logout();
                return redirect()->back()->with('error', 'আপনার অ্যাকাউন্টটি সাময়িকভাবে স্থগিত রয়েছে।');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', 'স্বাগতম! আপনি সফলভাবে লগইন করেছেন।');
        }

        return redirect()->back()->withInput()->with('error', 'ফোন নম্বর/ইমেইল অথবা পাসওয়ার্ড ভুল দিয়েছেন।');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('frontend.customer.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.unique' => 'এই মোবাইল নম্বরটি দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে।',
            'email.unique' => 'এই ইমেইলটি দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট তৈরি করা আছে।',
            'password.confirmed' => 'পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড মিলছে না।',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')->with('success', 'আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}
