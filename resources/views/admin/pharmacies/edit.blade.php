@extends('layouts.admin')

@section('title', 'Edit Pharmacy - Medi App')

@push('styles')
    <style>
        /* Custom toggle switch styles */
        .toggle-switch {
            position: relative;
            transition: background-color 0.3s ease;
        }

        .toggle-thumb {
            position: absolute;
            top: 2px;
            left: 4px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 50%;
            height: 24px;
            width: 24px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Pharmacy</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Update pharmacy information and settings</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">Please fix the following errors:</h4>
                </div>
                <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pharmacies.update', $pharmacy) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT') <!-- User Account Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Account Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-purple-500"></i>Contact Person Name
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $pharmacy->user->name) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-purple-500"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $pharmacy->user->email) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-purple-500"></i>Phone Number
                            </label>
                            <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', $pharmacy->user->phone_number) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Password (Leave blank to keep current)
                            </label>
                            <input type="password" name="password" id="password"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-purple-500"></i>Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div> <!-- Pharmacy Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pharmacy Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="pharmacy_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-pills mr-2 text-purple-500"></i>Pharmacy Name
                            </label>
                            <input type="text" name="pharmacy_name" id="pharmacy_name"
                                value="{{ old('pharmacy_name', $pharmacy->pharmacy_name) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('pharmacy_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="license_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-certificate mr-2 text-purple-500"></i>License Number
                            </label>
                            <input type="text" name="license_number" id="license_number"
                                value="{{ old('license_number', $pharmacy->license_number) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pharmacist_in_charge"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-md mr-2 text-purple-500"></i>Pharmacist in Charge
                            </label>
                            <input type="text" name="pharmacist_in_charge" id="pharmacist_in_charge"
                                value="{{ old('pharmacist_in_charge', $pharmacy->pharmacist_in_charge) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('pharmacist_in_charge')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-file-alt mr-2 text-purple-500"></i>Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">{{ old('description', $pharmacy->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Location Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>Address
                        </label>
                        <textarea name="address" id="address" rows="3" required
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">{{ old('address', $pharmacy->user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-city mr-2 text-purple-500"></i>City
                            </label>
                            <input type="text" name="city" id="city"
                                value="{{ old('city', $pharmacy->city) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flag mr-2 text-purple-500"></i>State
                            </label>
                            <input type="text" name="state" id="state"
                                value="{{ old('state', $pharmacy->state) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-mail-bulk mr-2 text-purple-500"></i>Postal Code
                            </label>
                            <input type="text" name="postal_code" id="postal_code"
                                value="{{ old('postal_code', $pharmacy->postal_code) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-globe mr-2 text-purple-500"></i>Country
                            </label>
                            <input type="text" name="country" id="country"
                                value="{{ old('country', $pharmacy->country) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Working Hours & Availability -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Working Hours & Availability</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="opening_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>Opening Time
                            </label>

                            <input type="time" name="opening_time" id="opening_time"
                                value="{{ old('opening_time', substr($pharmacy->opening_time, 11, 5)) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="closing_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>Closing Time
                            </label>
                            <input type="time" name="closing_time" id="closing_time"
                                value="{{ old('closing_time', substr($pharmacy->closing_time, 11, 5)) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-calendar-week mr-2 text-purple-500"></i>Working Days
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                            @php
                                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                $currentWorkingDays = old(
                                    'working_days',
                                    is_array($pharmacy->working_days) ? $pharmacy->working_days : [],
                                );
                            @endphp
                            @foreach ($days as $index => $day)
                                <label
                                    class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}"
                                        {{ in_array($day, $currentWorkingDays) ? 'checked' : '' }}
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span
                                        class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $dayLabels[$index] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('working_days')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Services & Specializations -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Services & Specializations</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="specializations"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-star mr-2 text-purple-500"></i>Specializations
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @php
                                $specializations = [
                                    'General Pharmacy',
                                    'Clinical Pharmacy',
                                    'Compounding Pharmacy',
                                    'Veterinary Pharmacy',
                                    'Oncology Pharmacy',
                                    'Pediatric Pharmacy',
                                    'Geriatric Pharmacy',
                                    'Psychiatric Pharmacy',
                                    'Hospital Pharmacy',
                                    'Community Pharmacy',
                                    'Industrial Pharmacy',
                                    'Nuclear Pharmacy',
                                ];

                                if (is_string($pharmacy->specializations)) {
                                    $decoded_specializations = json_decode($pharmacy->specializations, true);
                                    // Check if JSON decoding was successful
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        // Update $pharmacy->specializations with the decoded value.
                                        // This allows the subsequent `is_array($pharmacy->specializations)` check
                                        // to work correctly if it was a JSON string representing an array.
                                        $pharmacy->specializations = $decoded_specializations;
                                    } else {
                                        // If JSON was invalid, set to an empty array to prevent errors
                                        // and allow the subsequent logic to treat it as "no specializations".
                                        $pharmacy->specializations = [];
                                    }
                                }

                                $currentSpecializations = old(
                                    'specializations',
                                    is_array($pharmacy->specializations) ? $pharmacy->specializations : [],
                                );
                            @endphp
                            @foreach ($specializations as $specialization)
                                <label
                                    class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                    <input type="checkbox" name="specializations[]" value="{{ $specialization }}"
                                        {{ in_array($specialization, $currentSpecializations) ? 'checked' : '' }}
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span
                                        class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $specialization }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('specializations')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center">
                            <input type="hidden" name="home_delivery_available" value="0">
                            <input type="checkbox" name="home_delivery_available" id="home_delivery_available"
                                value="1"
                                {{ old('home_delivery_available', $pharmacy->home_delivery_available) ? 'checked' : '' }}
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="home_delivery_available"
                                class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-truck mr-2 text-purple-500"></i>Offers Delivery Service
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="accepts_insurance" value="0">
                            <input type="checkbox" name="accepts_insurance" id="accepts_insurance" value="1"
                                {{ old('accepts_insurance', $pharmacy->accepts_insurance) ? 'checked' : '' }}
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="accepts_insurance" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-shield-alt mr-2 text-purple-500"></i>Accepts Insurance
                            </label>
                        </div>
                    </div>

                    <div id="delivery_info"
                        class="{{ old('home_delivery_available', $pharmacy->home_delivery_available) ? '' : 'hidden' }}">
                        <label for="home_delivery_fee"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-purple-500"></i>Delivery Fee (LKR)
                        </label>
                        <input type="number" name="home_delivery_fee" id="home_delivery_fee"
                            value="{{ old('home_delivery_fee', $pharmacy->home_delivery_fee) }}" min="0"
                            step="0.01"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                        @error('home_delivery_fee')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Contact Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="emergency_contact"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone-alt mr-2 text-purple-500"></i>Emergency Contact
                            </label>
                            <input type="text" name="emergency_contact" id="emergency_contact"
                                value="{{ old('emergency_contact', $pharmacy->emergency_contact) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('emergency_contact')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-globe mr-2 text-purple-500"></i>Website
                            </label>
                            <input type="url" name="website" id="website"
                                value="{{ old('website', $pharmacy->website) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.pharmacies.show', $pharmacy) }}"
                    class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gray-900 dark:bg-gray-800">
                    <i class="fas fa-save mr-2"></i>
                    Update Pharmacy
                </button>
            </div>
        </form>
    </div>
    <script>
        // Toggle delivery info visibility
        document.getElementById('home_delivery_available').addEventListener('change', function() {
            const deliveryInfo = document.getElementById('delivery_info');
            if (this.checked) {
                deliveryInfo.classList.remove('hidden');
            } else {
                deliveryInfo.classList.add('hidden');
            }
        });
    </script>
@endsection
