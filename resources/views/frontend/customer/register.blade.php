@extends('layouts.app')

@section('title', 'Create an Account - Khati Bajar')

@section('content')
<div class="bg-gray-50 py-12 flex items-center justify-center min-h-[75vh]">
    <div class="max-w-md w-full px-4">
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl">
            
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 font-bold">
                    খ
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">Create Account</h1>
                <p class="text-xs text-gray-500 mt-1">Create an account to start shopping at Khati Bajar</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="Enter your name" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        required 
                        placeholder="017XXXXXXXX" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                    @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Address (Optional)</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        placeholder="example@gmail.com" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="At least 6 characters" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        placeholder="Re-enter your password" 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white"
                    >
                </div>

                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-sm">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-500 border-t pt-4">
                Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:underline">Login</a>
            </div>

        </div>
    </div>
</div>
@endsection
