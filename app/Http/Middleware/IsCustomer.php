<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'আপনার অ্যাকাউন্টে প্রবেশ করতে লগইন করুন।');
        }

        if (auth()->user()->isSuspended()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'আপনার অ্যাকাউন্টটি স্থগিত করা হয়েছে। অনুগ্রহ করে কর্তৃপক্ষের সাথে যোগাযোগ করুন।');
        }

        return $next($request);
    }
}
