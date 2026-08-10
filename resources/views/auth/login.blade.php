<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>Login - DCC Asset & Records Tracking</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-950 px-4">
        <div class="w-full max-w-md">
            {{-- Logo & Title --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 backdrop-blur rounded-2xl mb-4">
                    <i class="fa-solid fa-building-columns text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">DARTS</h1>
                <p class="text-emerald-200 text-sm mt-1">DCC Asset & Records Tracking System</p>
            </div>

            {{-- Login Card --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Sign in to your account</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                                   class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm @error('username') border-red-300 @enderror"
                                   placeholder="Enter your username">
                        </div>
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                   class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm @error('password') border-red-300 @enderror"
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-medium py-2.5 px-4 rounded-lg transition duration-150 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Sign In
                    </button>
                </form>
            </div>

            <p class="text-center text-emerald-300 text-xs mt-6">
                DCC Asset & Records Tracking System v2.0
            </p>
        </div>
    </div>
</body>
</html>
