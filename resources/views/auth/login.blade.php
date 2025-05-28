@extends('layouts.auth')

@section('title', 'Sign In - MediCare')

@section('content')
    <div class="max-w-md w-full space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <div class="bg-gray-900 p-3 rounded-2xl shadow-lg inline-block mb-6">
                        <i class="fas fa-sign-in-alt text-white text-3xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome back</h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Sign in to your MediCare account
                    </p>
                </div>

                <!-- Login Form -->
                <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-gray-200/20 dark:border-gray-700/20">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-600"></i>{{ __('Email Address') }}
                            </label>
                            <input id="email" type="email" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="Enter your email address">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>{{ __('Password') }}
                            </label>
                            <input id="password" type="password" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white/50 dark:bg-gray-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 focus:ring-red-500 @enderror" 
                                name="password" required autocomplete="current-password"
                                placeholder="Enter your password">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember Me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 transition-colors duration-200">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-xl font-semibold text-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Sign In') }}
                        </button>

                        <!-- Register Link -->
                        @if (Route::has('register'))
                            <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Don't have an account? 
                                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-500 transition-colors duration-200">
                                        Sign up here
                                    </a>
                                </p>
                            </div>
                        @endif                    </form>
                </div>
            </div>
        </div>
@endsection
