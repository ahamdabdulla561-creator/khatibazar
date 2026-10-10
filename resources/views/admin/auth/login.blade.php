<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Khati Bazar</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-200 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Khati Bazar Logo" class="h-20 w-auto mx-auto mb-3 bg-white p-2 rounded-2xl shadow-lg border border-slate-700">
            <h1 class="text-2xl font-extrabold text-white">Admin Panel Login</h1>
            <p class="text-xs text-slate-400 mt-1">Access the Khati Bazar Admin Dashboard</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-xl text-xs mb-6 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="remember" value="1">

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Email / Mobile Number</label>
                <input 
                    type="text" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    placeholder="admin@khatibajar.com" 
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm focus:ring-2 focus:ring-green-500 text-white focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="••••••••" 
                    class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-sm focus:ring-2 focus:ring-green-500 text-white focus:outline-none"
                >
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-600/30 transition text-sm">
                Login
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:underline text-slate-400"><i class="fa-solid fa-arrow-left mr-1"></i> Go to main site</a>
        </div>
    </div>
</body>
</html>
