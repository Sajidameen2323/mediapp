@extends('layouts.auth')

@section('title', 'Register - MediCare')

@section('content')
    <div class="max-w-lg w-full space-y-8">            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="mx-auto h-16 w-16 bg-gray-900 rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Join MediCare</h2>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                    Create your patient account and take control of your healthcare journey
                </p>
                <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-xl">
                    <i class="fas fa-user text-green-600 dark:text-green-400 mr-2"></i>
                    <span class="text-sm font-medium text-green-800 dark:text-green-300">Patient Registration</span>
                </div>
            </div>

            <!-- Registration Form -->
            <div
                class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 border border-gray-200/20 dark:border-gray-700/20">
                     <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
                        @csrf                        <!-- Personal Information Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fas fa-user-circle text-blue-500 dark:text-blue-400 mr-3"></i>
                                Personal Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Full Name -->
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-user text-gray-400 dark:text-gray-500 mr-2"></i>Full Name *
                                    </label>
                                    <input id="name" name="name" type="text" required autofocus
                                        value="{{ old('name') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-400 focus:ring-red-500 @enderror"
                                        placeholder="Enter your full name">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-envelope text-gray-400 dark:text-gray-500 mr-2"></i>Email Address *
                                    </label>
                                    <input id="email" name="email" type="email" required
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-400 focus:ring-red-500 @enderror"
                                        placeholder="Enter your email address">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-phone text-gray-400 dark:text-gray-500 mr-2"></i>Phone Number
                                    </label>
                                    <input id="phone_number" name="phone_number" type="tel"
                                        value="{{ old('phone_number') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone_number') border-red-400 focus:ring-red-500 @enderror"
                                        placeholder="Enter your phone number">
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-birthday-cake text-gray-400 dark:text-gray-500 mr-2"></i>Date of Birth
                                    </label>
                                    <input id="date_of_birth" name="date_of_birth" type="date"
                                        value="{{ old('date_of_birth') }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('date_of_birth') border-red-400 focus:ring-red-500 @enderror">
                                    @error('date_of_birth')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="md:col-span-2">
                                    <label for="gender" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-venus-mars text-gray-400 dark:text-gray-500 mr-2"></i>Gender
                                    </label>
                                    <select id="gender" name="gender"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('gender') border-red-400 focus:ring-red-500 @enderror">
                                        <option value="">Select your gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>                        <!-- Address Section -->
                        <div class="border-b border-gray-200 dark:border-gray-600 pb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fas fa-map-marker-alt text-blue-500 dark:text-blue-400 mr-3"></i>
                                Address Information
                            </h3>

                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-home text-gray-400 dark:text-gray-500 mr-2"></i>Address
                                </label>
                                <textarea id="address" name="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('address') border-red-400 focus:ring-red-500 @enderror"
                                    placeholder="Enter your complete address">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>                        <!-- Security Section -->
                        <div class="pb-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fas fa-shield-alt text-blue-500 dark:text-blue-400 mr-3"></i>
                                Account Security
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-lock text-gray-400 dark:text-gray-500 mr-2"></i>Password *
                                    </label>
                                    <input id="password" name="password" type="password" required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-400 focus:ring-red-500 @enderror"
                                        placeholder="Create a strong password">
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-lock text-gray-400 dark:text-gray-500 mr-2"></i>Confirm Password *
                                    </label>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Confirm your password">
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-700">
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">Password Requirements:</h4>
                                <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                                    <li class="flex items-center"><i class="fas fa-check-circle mr-2"></i>At least 8
                                        characters long</li>
                                    <li class="flex items-center"><i class="fas fa-check-circle mr-2"></i>Include both
                                        uppercase and lowercase letters</li>
                                    <li class="flex items-center"><i class="fas fa-check-circle mr-2"></i>Include at least
                                        one number</li>
                                </ul>
                            </div>
                        </div>                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit"
                                class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-semibold text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-[1.02] transition-all duration-300">
                                <i class="fas fa-user-plus mr-3"></i>
                                Create My Patient Account
                            </button>
                        </div>

                        <!-- Terms -->
                        <div class="text-center pt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                By creating an account, you agree to our
                                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-medium">Terms of
                                    Service</a>
                                and
                                <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-medium">Privacy Policy</a>
                            </p>
                        </div>
                    </form>
                      <!-- Login Link -->
            <div class="mt-8 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400">Already have an account?</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Sign in to your account
                    </a>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-900 dark:text-blue-300">
                            Healthcare Professional?
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                            <p>
                                If you're a doctor, lab technician, pharmacist, or administrator,
                                please contact your system administrator to create your professional account.
                            </p>
                            <p class="mt-2 flex items-center">
                                <i class="fas fa-envelope mr-2"></i>
                                <a href="mailto:admin@medicare.com" class="font-medium hover:text-blue-600 dark:hover:text-blue-300">
                                    admin@medicare.com
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
