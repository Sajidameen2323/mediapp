@extends('layouts.app')

@section('title', 'Edit Laboratory - Medi App')

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
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Laboratory</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Update laboratory information and settings</p>
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

        <form action="{{ route('admin.laboratories.update', $laboratory) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- User Account Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Account Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user mr-2 text-green-500"></i>Contact Person Name
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $laboratory->user->name) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-envelope mr-2 text-green-500"></i>Email Address
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $laboratory->user->email) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-green-500"></i>Phone Number
                            </label> <input type="text" name="phone_number" id="phone_number"
                                value="{{ old('phone_number', $laboratory->user->phone_number) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-green-500"></i>Password (leave blank to keep current)
                            </label>
                            <input type="password" name="password" id="password"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-lock mr-2 text-green-500"></i>Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laboratory Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Laboratory Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="lab_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flask mr-2 text-green-500"></i>Laboratory Name
                            </label>
                            <input type="text" name="lab_name" id="lab_name"
                                value="{{ old('lab_name', $laboratory->name) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('lab_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="license_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-certificate mr-2 text-green-500"></i>License Number
                            </label>
                            <input type="text" name="license_number" id="license_number"
                                value="{{ old('license_number', $laboratory->license_number) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="accreditation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-award mr-2 text-green-500"></i>Accreditation
                            </label>
                            <input type="text" name="accreditation" id="accreditation"
                                value="{{ old('accreditation', $laboratory->accreditation) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('accreditation')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-file-alt mr-2 text-green-500"></i>Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">{{ old('description', $laboratory->description) }}</textarea>
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
                            <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>Address
                        </label>
                        <textarea name="address" id="address" rows="3" required
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">{{ old('address', $laboratory->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-city mr-2 text-green-500"></i>City
                            </label>
                            <input type="text" name="city" id="city"
                                value="{{ old('city', $laboratory->city) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flag mr-2 text-green-500"></i>State
                            </label>
                            <input type="text" name="state" id="state"
                                value="{{ old('state', $laboratory->state) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-mail-bulk mr-2 text-green-500"></i>Postal Code
                            </label>
                            <input type="text" name="postal_code" id="postal_code"
                                value="{{ old('postal_code', $laboratory->postal_code) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-globe mr-2 text-green-500"></i>Country
                            </label>
                            <input type="text" name="country" id="country"
                                value="{{ old('country', $laboratory->country) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
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
                                <i class="fas fa-clock mr-2 text-green-500"></i>Opening Time
                            </label>
                            <input type="time" name="opening_time" id="opening_time"
                                value="{{ old('opening_time', \Carbon\Carbon::parse($laboratory->opening_time)->format('H:i')) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="closing_time"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-clock mr-2 text-green-500"></i>Closing Time
                            </label>

                            <input type="time" name="closing_time" id="closing_time"
                                value="{{ old('closing_time', \Carbon\Carbon::parse($laboratory->closing_time)->format('H:i')) }}" required
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-calendar-week mr-2 text-green-500"></i>Working Days
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3"> @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                            // Ensure working_days is always an array
                            $labWorkingDays = $laboratory->working_days ?? [];
                            if (is_string($labWorkingDays)) {
                                $labWorkingDays = json_decode($labWorkingDays, true) ?? [];
                            }
                            $oldWorkingDays = old('working_days', $labWorkingDays);
                            // Ensure old working days is also an array
                            if (is_string($oldWorkingDays)) {
                                $oldWorkingDays = json_decode($oldWorkingDays, true) ?? [];
                            }
                        @endphp
                            @foreach ($days as $index => $day)
                                <label
                                    class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                    <input type="checkbox" name="working_days[]" value="{{ $day }}"
                                        {{ in_array($day, $oldWorkingDays) ? 'checked' : '' }}
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
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

            <!-- Services & Contact -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Services & Contact Information</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="services_offered"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-list-ul mr-2 text-green-500"></i>Services Offered
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"> @php
                            $services = [
                                'Blood Tests',
                                'Urine Tests',
                                'X-Ray',
                                'CT Scan',
                                'MRI',
                                'Ultrasound',
                                'ECG',
                                'EEG',
                                'Endoscopy',
                                'Pathology',
                                'Microbiology',
                                'Biochemistry',
                                'Hematology',
                                'Immunology',
                                'Molecular Diagnostics',
                                'Histopathology',
                            ];
                            // Ensure services_offered is always an array
                            $labServices = $laboratory->services_offered ?? [];
                            if (is_string($labServices)) {
                                $labServices = json_decode($labServices, true) ?? [];
                            }
                            $oldServices = old('services_offered', $labServices);
                            // Ensure old services is also an array
                            if (is_string($oldServices)) {
                                $oldServices = json_decode($oldServices, true) ?? [];
                            }
                        @endphp
                            @foreach ($services as $service)
                                <label
                                    class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                    <input type="checkbox" name="services_offered[]" value="{{ $service }}"
                                        {{ in_array($service, $oldServices) ? 'checked' : '' }}
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $service }}</span>
                                </label>
                            @endforeach
                        </div> @error('services_offered')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="consultation_fee"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Consultation Fee
                            </label>
                            <input type="number" name="consultation_fee" id="consultation_fee"
                                value="{{ old('consultation_fee', $laboratory->consultation_fee) }}" required
                                step="0.01" min="0"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('consultation_fee')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone-alt mr-2 text-green-500"></i>Emergency Contact
                            </label>
                            <input type="text" name="emergency_contact" id="emergency_contact"
                                value="{{ old('emergency_contact', $laboratory->emergency_contact) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('emergency_contact')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-globe mr-2 text-green-500"></i>Website
                            </label>
                            <input type="url" name="website" id="website"
                                value="{{ old('website', $laboratory->website) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_person_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-tie mr-2 text-green-500"></i>Contact Person Name
                            </label>
                            <input type="text" name="contact_person_name" id="contact_person_name"
                                value="{{ old('contact_person_name', $laboratory->contact_person_name) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('contact_person_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_person_phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-phone-alt mr-2 text-green-500"></i>Contact Person Phone
                            </label>
                            <input type="text" name="contact_person_phone" id="contact_person_phone"
                                value="{{ old('contact_person_phone', $laboratory->contact_person_phone) }}"
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                            @error('contact_person_phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Equipment Details -->
                    <div>
                        <label for="equipment_details"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tools mr-2 text-green-500"></i>Equipment Details
                        </label>
                        <textarea name="equipment_details" id="equipment_details" rows="4"
                            placeholder="Describe the laboratory equipment and capabilities..."
                            class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">{{ old('equipment_details', $laboratory->equipment_details) }}</textarea>
                        @error('equipment_details')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Home Service Section -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Home Service Options</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="home_service_available" value="1"
                                        {{ old('home_service_available', $laboratory->home_service_available) ? 'checked' : '' }}
                                        id="home_service_available"
                                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-home mr-2 text-green-500"></i>Home Service Available
                                    </span>
                                </label>
                                @error('home_service_available')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="home_service_fee_container"
                                style="display: {{ old('home_service_available', $laboratory->home_service_available) ? 'block' : 'none' }};">
                                <label for="home_service_fee"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-money-bill mr-2 text-green-500"></i>Home Service Fee (LKR)
                                </label>
                                <input type="number" name="home_service_fee" id="home_service_fee"
                                    value="{{ old('home_service_fee', $laboratory->home_service_fee ?? '0') }}"
                                    min="0" step="0.01"
                                    class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                @error('home_service_fee')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('admin.laboratories.index') }}"
                    class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gray-900 dark:bg-gray-800">
                    <i class="fas fa-save mr-2"></i>
                    Update Laboratory
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const homeServiceCheckbox = document.getElementById('home_service_available');
                const feeContainer = document.getElementById('home_service_fee_container');
                const feeInput = document.getElementById('home_service_fee');

                function toggleHomeServiceFee() {
                    if (homeServiceCheckbox.checked) {
                        feeContainer.style.display = 'block';
                        feeInput.required = true;
                    } else {
                        feeContainer.style.display = 'none';
                        feeInput.required = false;
                        feeInput.value = '0';
                    }
                }

                homeServiceCheckbox.addEventListener('change', toggleHomeServiceFee);

                // Check initial state
                toggleHomeServiceFee();
            });
        </script>
    @endpush
@endsection
