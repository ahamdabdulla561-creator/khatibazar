<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'অ্যাডমিন প্যানেলে প্রবেশ করতে প্রথমে লগইন করুন।');
        }

        if (auth()->user()->role !== 'admin') {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'আপনার অ্যাডমিন অ্যাক্সেস পাওয়ার অনুমতি নেই।');
        }

        if (auth()->user()->isSuspended()) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'আপনার অ্যাডমিন অ্যাকাউন্টটি সাময়িকভাবে স্থগিত করা হয়েছে।');
        }

        return $next($request);
    }
}
