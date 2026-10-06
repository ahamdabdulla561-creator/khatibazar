@extends('layouts.app')

@section('title', 'Customer Login - Khati Bajar')

@section('content')
<div class="bg-gray-50 py-12 flex items-center justify-center min-h-[70vh]">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl">
            
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 font-bold">
                    খ
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">Customer Login</h1>
                <p class="text-xs text-gray-500 mt-1">Enter your details to access your account</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number or Email</label>
                    <input 
                        type="text" 
                        name="login" 
                        value="{{ old('login') }}" 
                        required 
                        placeholder="017XXXXXXXX or example@gmail.com" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="••••••••" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-gray-600">
                        <input type="checkbox" name="remember" class="text-brand-600 rounded">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-sm">
                    Login
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-500 border-t pt-4">
                New customer? <a href="{{ route('register') }}" class="text-brand-600 font-bold hover:underline">Create an account</a>
            </div>

        </div>
    </div>
</div>
@endsection
