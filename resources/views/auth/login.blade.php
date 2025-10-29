@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md">
        @if (session('success'))
            <div class="p-3 rounded bg-green-500 text-green-100 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="p-3 rounded bg-red-500 text-red-100 mb-4">{{ session('error') }}</div>
        @endif
        <!-- Login Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-gray-800 mb-2">Welcome Back</h1>
                <p class="text-sm text-gray-500">Please login to your account</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <!-- Email Input -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                            placeholder="Enter your email"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 outline-none"
                            placeholder="Enter your password"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition duration-200"
                >
                    Sign In
                </button>

            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" id="createAccountBtn" class="text-blue-600 font-medium hover:text-blue-700 transition duration-200">
                        Create account
                    </a>
                </p>
            </div>

        </div>

        <!-- Footer Text -->
        <p class="text-center text-xs text-gray-500 mt-6">
            © 2025 Sqiva. All rights reserved.
        </p>
    </div>
@endsection