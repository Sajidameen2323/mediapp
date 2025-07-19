@extends('layouts.admin')

@section('title', 'Edit Doctor - Medi App')

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

        .service-card {
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-2px);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Doctor</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Update doctor information and settings</p>
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

        <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update the doctor's personal details</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full
                            Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $doctor->user->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email
                            Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $doctor->user->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                        <input type="tel" id="phone_number" name="phone_number"
                            value="{{ old('phone_number', $doctor->user->phone_number) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender</label>
                        <select id="gender" name="gender"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $doctor->user->gender) == 'male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="female" {{ old('gender', $doctor->user->gender) == 'female' ? 'selected' : '' }}>
                                Female</option>
                            <option value="other" {{ old('gender', $doctor->user->gender) == 'other' ? 'selected' : '' }}>
                                Other</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_of_birth"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', $doctor->user->date_of_birth ? $doctor->user->date_of_birth->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                            <span class="text-xs text-gray-500">(leave blank to keep current)</span>
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                            <span class="text-xs text-gray-500">(required if changing password)</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            placeholder="Enter complete address">{{ old('address', $doctor->user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Professional Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Update professional credentials and qualifications
                    </p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="specialization"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-stethoscope mr-2 text-green-500"></i>Specialization
                        </label>
                        <select name="specialization" id="specialization" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select a specialization...</option>
                            <option value="Cardiology" {{ old('specialization', $doctor->specialization) == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                            <option value="Dermatology" {{ old('specialization', $doctor->specialization) == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                            <option value="Gastroenterology" {{ old('specialization', $doctor->specialization) == 'Gastroenterology' ? 'selected' : '' }}>Gastroenterology</option>
                            <option value="Neurology" {{ old('specialization', $doctor->specialization) == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                            <option value="Orthopedics" {{ old('specialization', $doctor->specialization) == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                            <option value="Pediatrics" {{ old('specialization', $doctor->specialization) == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="Psychiatry" {{ old('specialization', $doctor->specialization) == 'Psychiatry' ? 'selected' : '' }}>Psychiatry</option>
                            <option value="Pulmonology" {{ old('specialization', $doctor->specialization) == 'Pulmonology' ? 'selected' : '' }}>Pulmonology</option>
                            <option value="Urology" {{ old('specialization', $doctor->specialization) == 'Urology' ? 'selected' : '' }}>Urology</option>
                            <option value="Gynecology" {{ old('specialization', $doctor->specialization) == 'Gynecology' ? 'selected' : '' }}>Gynecology</option>
                            <option value="Ophthalmology" {{ old('specialization', $doctor->specialization) == 'Ophthalmology' ? 'selected' : '' }}>Ophthalmology</option>
                            <option value="ENT (Ear, Nose, and Throat)" {{ old('specialization', $doctor->specialization) == 'ENT (Ear, Nose, and Throat)' ? 'selected' : '' }}>ENT (Ear, Nose, and Throat)</option>
                            <option value="Endocrinology" {{ old('specialization', $doctor->specialization) == 'Endocrinology' ? 'selected' : '' }}>Endocrinology</option>
                            <option value="Rheumatology" {{ old('specialization', $doctor->specialization) == 'Rheumatology' ? 'selected' : '' }}>Rheumatology</option>
                            <option value="Oncology" {{ old('specialization', $doctor->specialization) == 'Oncology' ? 'selected' : '' }}>Oncology</option>
                            <option value="Infectious Disease" {{ old('specialization', $doctor->specialization) == 'Infectious Disease' ? 'selected' : '' }}>Infectious Disease</option>
                            <option value="Nephrology" {{ old('specialization', $doctor->specialization) == 'Nephrology' ? 'selected' : '' }}>Nephrology</option>
                            <option value="General Medicine" {{ old('specialization', $doctor->specialization) == 'General Medicine' ? 'selected' : '' }}>General Medicine</option>
                        </select>
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_number"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">License Number</label>
                        <input type="text" id="license_number" name="license_number"
                            value="{{ old('license_number', $doctor->license_number) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required>
                        @error('license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="experience_years"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Years of
                            Experience</label>
                        <input type="number" id="experience_years" name="experience_years"
                            value="{{ old('experience_years', $doctor->experience_years) }}" min="0"
                            max="50"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required>
                        @error('experience_years')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="consultation_fee"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Consultation Fee
                            ($)</label>
                        <input type="number" id="consultation_fee" name="consultation_fee"
                            value="{{ old('consultation_fee', $doctor->consultation_fee) }}" min="0"
                            step="0.01"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            required>
                        @error('consultation_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="qualifications"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Qualifications</label>
                        <textarea id="qualifications" name="qualifications" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            placeholder="e.g., MBBS, MD (Cardiology), FRCS" required>{{ old('qualifications', $doctor->qualifications) }}</textarea>
                        @error('qualifications')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="bio"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bio</label>
                        <textarea id="bio" name="bio" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                            placeholder="Brief professional biography">{{ old('bio', $doctor->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="profile_image"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Image</label>
                        @if ($doctor->profile_image)
                            <div class="mb-4">
                                <img src="{{ Storage::url($doctor->profile_image) }}" alt="Current profile image"
                                    class="h-24 w-24 rounded-lg object-cover border border-gray-300">
                                <p class="text-sm text-gray-500 mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" id="profile_image" name="profile_image" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <p class="text-sm text-gray-500 mt-1">Upload new image to replace current one (JPEG, PNG, GIF, max
                            2MB)</p>
                        @error('profile_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_available" name="is_available" value="1"
                                {{ old('is_available', $doctor->is_available) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                            <label for="is_available" class="ml-2 block text-sm text-gray-900 dark:text-white">
                                Doctor is available for appointments
                            </label>
                        </div>
                    </div>
                </div>
            </div> <!-- Services Assignment -->
            @if (count($services) > 0)
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-briefcase-medical mr-3 text-purple-600 dark:text-purple-400"></i>
                                    Services Assignment
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update the services this doctor
                                    can provide</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button type="button" id="select-all-services"
                                    class="text-xs px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition-colors duration-200 font-medium">
                                    <i class="fas fa-check-double mr-1"></i>Select All
                                </button>
                                <button type="button" id="clear-all-services"
                                    class="text-xs px-3 py-1.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200 font-medium">
                                    <i class="fas fa-times mr-1"></i>Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- Services Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="services-grid">
                            @foreach ($services as $service)
                                @php
                                    $isSelected = in_array(
                                        $service->id,
                                        old('services', $doctor->services->pluck('id')->toArray()),
                                    );
                                @endphp
                                <div
                                    class="service-card group relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-600 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer {{ $isSelected ? 'ring-2 ring-purple-500 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900 dark:to-pink-900' : '' }}">
                                    <div class="p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex items-center space-x-3">
                                                {{-- <div
                                                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900 dark:to-pink-900 flex items-center justify-center">
                                                    <i
                                                        class="fas fa-stethoscope text-purple-600 dark:text-purple-400 text-sm"></i>
                                                </div> --}}
                                                <div class="flex-1">
                                                    <h4
                                                        class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                                        {{ $service->name }}
                                                    </h4>
                                                    @if ($service->category)
                                                        <span
                                                            class="inline-block px-2 py-1 mt-1 text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full">
                                                            {{ $service->category }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="service-checkbox-container">
                                                <input hidden type="checkbox" name="services[]"
                                                    id="service_{{ $service->id }}" value="{{ $service->id }}"
                                                    {{ $isSelected ? 'checked' : '' }}
                                                    class="service-checkbox h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded transition-all duration-200">
                                                <label for="service_{{ $service->id }}" class="sr-only">Select
                                                    {{ $service->name }}</label>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            @if ($service->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                                    {{ $service->description }}</p>
                                            @endif

                                            <div
                                                class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-600">
                                                @if ($service->price)
                                                    <div class="flex items-center space-x-1">
                                                        <i
                                                            class="fas fa-dollar-sign text-xs text-green-600 dark:text-green-400"></i>
                                                        <span
                                                            class="service-price-span text-sm font-bold text-green-600 dark:text-green-400">${{ number_format($service->price, 2) }}</span>
                                                    </div>
                                                @endif

                                                @if ($service->duration)
                                                    <div class="flex items-center space-x-1">
                                                        <i
                                                            class="fas fa-clock text-xs text-blue-600 dark:text-blue-400"></i>
                                                        <span
                                                            class="text-xs text-gray-600 dark:text-gray-400">{{ $service->duration }}
                                                            min</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selection Indicator -->
                                    <div
                                        class="absolute top-2 right-2 w-6 h-6 rounded-full {{ $isSelected ? 'bg-green-500 border-green-500' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600' }} border-2 flex items-center justify-center transition-all duration-200 service-indicator">
                                        <i
                                            class="fas fa-check text-xs text-green-600 dark:text-green-400 {{ $isSelected ? 'opacity-100' : 'opacity-0' }} service-check-icon transition-opacity duration-200"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Services Summary -->
                        <div
                            class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600 rounded-xl border border-purple-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                                        <i class="fas fa-chart-pie mr-2 text-purple-600 dark:text-purple-400"></i>
                                        Services Summary
                                    </h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Selected services: <span
                                            id="selected-services-count"
                                            class="font-medium text-purple-600 dark:text-purple-400">{{ $doctor->services->count() }}</span>
                                        of {{ count($services) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Total potential revenue:</p>
                                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400" id="total-revenue">
                                        ${{ number_format($doctor->services->sum('price'), 2) }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($errors->has('services'))
                            <p class="mt-4 text-sm text-red-600 dark:text-red-400">{{ $errors->first('services') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Work Schedule -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-calendar-alt mr-3 text-blue-600 dark:text-blue-400"></i>
                                Work Schedule
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update doctor's availability for
                                appointments</p>
                            @if ($errors->hasAny(['schedules']))
                                <div
                                    class="mt-2 p-2 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg">
                                    <p class="text-xs text-red-700 dark:text-red-300">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Please fix schedule validation errors below.
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="button" id="quick-preset-weekdays"
                                class="text-xs px-3 py-1.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors duration-200 font-medium">
                                <i class="fas fa-business-time mr-1"></i>Weekdays
                            </button>
                            <button type="button" id="quick-preset-all"
                                class="text-xs px-3 py-1.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-800 transition-colors duration-200 font-medium">
                                <i class="fas fa-clock mr-1"></i>All Days
                            </button>
                            <button type="button" id="clear-all"
                                class="text-xs px-3 py-1.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors duration-200 font-medium">
                                <i class="fas fa-times mr-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6" id="schedule-container">
                    <div class="space-y-3">
                        @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            @php
                                $schedule = $doctor->schedules->firstWhere('day_of_week', $day);
                                $dayColors = [
                                    'monday' => 'blue',
                                    'tuesday' => 'indigo',
                                    'wednesday' => 'purple',
                                    'thursday' => 'pink',
                                    'friday' => 'red',
                                    'saturday' => 'orange',
                                    'sunday' => 'yellow',
                                ];
                                $color = $dayColors[$day];
                            @endphp
                            <div
                                class="schedule-day-container group hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">
                                <div
                                    class="flex items-center p-4 bg-gradient-to-r from-{{ $color }}-50 to-{{ $color }}-100 dark:from-gray-700 dark:to-gray-600">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <!-- Day Name -->
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-900 flex items-center justify-center">
                                                <i
                                                    class="fas fa-calendar-day text-{{ $color }}-600 dark:text-{{ $color }}-400 text-sm"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-base font-semibold text-gray-900 dark:text-white capitalize">{{ $day }}</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ date('M j', strtotime('next ' . $day)) }}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Availability Toggle -->
                                        <div class="flex items-center ml-auto">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="hidden" name="schedules[{{ $day }}][is_available]"
                                                    value="0">
                                                <input type="checkbox"
                                                    name="schedules[{{ $day }}][is_available]"
                                                    id="schedule_{{ $day }}_available" value="1"
                                                    class="sr-only schedule-checkbox" data-day="{{ $day }}"
                                                    {{ old('schedules.' . $day . '.is_available', $schedule && $schedule->is_available ? '1' : '0') == '1' ? 'checked' : '' }}>
                                                @php
                                                    $isAvailable =
                                                        old(
                                                            'schedules.' . $day . '.is_available',
                                                            $schedule && $schedule->is_available ? '1' : '0',
                                                        ) == '1';
                                                @endphp
                                                <div
                                                    class="relative w-14 h-7 bg-gray-200 dark:bg-gray-600 rounded-full transition-colors duration-300 ease-in-out toggle-switch {{ $isAvailable ? 'bg-green-500' : '' }}">
                                                    <div class="absolute top-0.5 left-[4px] bg-white border border-gray-300 rounded-full h-6 w-6 transition-transform duration-300 ease-in-out shadow-lg toggle-thumb"
                                                        style="transform: translateX({{ $isAvailable ? '28px' : '4px' }})">
                                                        <i
                                                            class="fas fa-check text-xs text-green-600 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 {{ $isAvailable ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-300 check-icon"></i>
                                                    </div>
                                                </div>
                                                <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    <span
                                                        class="availability-text {{ $isAvailable ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">{{ $isAvailable ? 'Available' : 'Unavailable' }}</span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    @error('schedules.' . $day . '.is_available')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Time Selection Panel -->
                                <div class="schedule-times bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700"
                                    style="display: {{ $isAvailable ? 'block' : 'none' }};">
                                    <input type="hidden" name="schedules[{{ $day }}][day_of_week]"
                                        value="{{ $day }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-3">
                                            <label
                                                class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                                Start Time
                                            </label>
                                            <div class="relative">
                                                <input type="time" name="schedules[{{ $day }}][start_time]"
                                                    value="{{ old('schedules.' . $day . '.start_time', $schedule ? substr($schedule->start_time, 11, 5) : '09:00') }}"
                                                    class="block w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ $color }}-500 focus:border-{{ $color }}-500 dark:bg-gray-700 dark:text-white transition-all duration-200 text-sm font-medium {{ $errors->has('schedules.' . $day . '.start_time') ? 'border-red-500 focus:ring-red-500' : '' }}">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-sun text-yellow-500 text-sm"></i>
                                                </div>
                                            </div>
                                            @error('schedules.' . $day . '.start_time')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="space-y-3">
                                            <label
                                                class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <i class="fas fa-clock mr-2 text-red-500"></i>
                                                End Time
                                            </label>
                                            <div class="relative">
                                                <input type="time" name="schedules[{{ $day }}][end_time]"
                                                    value="{{ old('schedules.' . $day . '.end_time', $schedule ? substr($schedule->end_time, 11, 5) : '17:00') }}"
                                                    class="block w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-{{ $color }}-500 focus:border-{{ $color }}-500 dark:bg-gray-700 dark:text-white transition-all duration-200 text-sm font-medium {{ $errors->has('schedules.' . $day . '.end_time') ? 'border-red-500 focus:ring-red-500' : '' }}">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-moon text-blue-500 text-sm"></i>
                                                </div>
                                            </div>
                                            @error('schedules.' . $day . '.end_time')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Quick Time Presets -->
                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Quick presets:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button"
                                                class="time-preset text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                                data-start="09:00" data-end="17:00">
                                                9 AM - 5 PM
                                            </button>
                                            <button type="button"
                                                class="time-preset text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                                data-start="08:00" data-end="16:00">
                                                8 AM - 4 PM
                                            </button>
                                            <button type="button"
                                                class="time-preset text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                                data-start="10:00" data-end="18:00">
                                                10 AM - 6 PM
                                            </button>
                                            <button type="button"
                                                class="time-preset text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                                data-start="14:00" data-end="22:00">
                                                2 PM - 10 PM
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Schedule Summary -->
                    <div
                        class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-xl border border-blue-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-chart-bar mr-2 text-blue-600 dark:text-blue-400"></i>
                                    Schedule Summary
                                </h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total available days: <span
                                        id="available-days-count"
                                        class="font-medium text-blue-600 dark:text-blue-400">0</span></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600 dark:text-gray-400">Total hours per week:</p>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400" id="total-hours">0 hrs</p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->hasAny(['schedules.*']))
                        <div
                            class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <h4 class="text-sm font-semibold text-red-800 dark:text-red-200 flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Schedule Validation Errors
                            </h4>
                            <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    @if (str_contains($error, 'schedule'))
                                        <li>{{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.doctors.index') }}"
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Update Doctor
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle schedule checkbox changes with improved toggle animations
            const scheduleCheckboxes = document.querySelectorAll('.schedule-checkbox');
            const availableDaysCount = document.getElementById('available-days-count');
            const totalHoursElement = document.getElementById('total-hours');

            // Toggle switch animation
            scheduleCheckboxes.forEach(checkbox => {
                const container = checkbox.closest('.schedule-day-container');
                const toggleSwitch = container.querySelector('.toggle-switch');
                const toggleThumb = container.querySelector('.toggle-thumb');
                const checkIcon = container.querySelector('.check-icon');
                const availabilityText = container.querySelector('.availability-text');

                checkbox.addEventListener('change', function() {
                    const timesContainer = container.querySelector('.schedule-times');

                    if (this.checked) {
                        // Toggle switch animation
                        toggleSwitch.classList.add('bg-green-500');
                        toggleSwitch.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                        toggleThumb.style.transform = 'translateX(28px)';
                        checkIcon.classList.remove('opacity-0');
                        checkIcon.classList.add('opacity-100');

                        // Show times container
                        timesContainer.style.display = 'block';
                        availabilityText.textContent = 'Available';
                        availabilityText.classList.add('text-green-600', 'dark:text-green-400');
                        availabilityText.classList.remove('text-gray-600', 'dark:text-gray-400');

                        // Set default times if not already set
                        const startTime = timesContainer.querySelector(
                            'input[type="time"]:first-of-type');
                        const endTime = timesContainer.querySelector(
                            'input[type="time"]:last-of-type');
                        if (!startTime.value) startTime.value = '09:00';
                        if (!endTime.value) endTime.value = '17:00';

                        // Add smooth animation
                        timesContainer.style.opacity = '0';
                        timesContainer.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            timesContainer.style.transition = 'all 0.3s ease';
                            timesContainer.style.opacity = '1';
                            timesContainer.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        // Toggle switch animation
                        toggleSwitch.classList.remove('bg-green-500');
                        toggleSwitch.classList.add('bg-gray-200', 'dark:bg-gray-600');
                        toggleThumb.style.transform = 'translateX(4px)';
                        checkIcon.classList.add('opacity-0');
                        checkIcon.classList.remove('opacity-100');

                        // Hide times container
                        timesContainer.style.display = 'none';
                        availabilityText.textContent = 'Unavailable';
                        availabilityText.classList.remove('text-green-600', 'dark:text-green-400');
                        availabilityText.classList.add('text-gray-600', 'dark:text-gray-400');
                    }

                    updateScheduleSummary();
                });
            });

            // Schedule quick preset buttons
            document.getElementById('quick-preset-weekdays').addEventListener('click', function() {
                const weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                scheduleCheckboxes.forEach(checkbox => {
                    const day = checkbox.dataset.day;
                    if (weekdays.includes(day)) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            document.getElementById('quick-preset-all').addEventListener('click', function() {
                scheduleCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });

            document.getElementById('clear-all').addEventListener('click', function() {
                scheduleCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                });
            });

            // Time preset buttons
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('time-preset')) {
                    const container = e.target.closest('.schedule-times');
                    const startTime = container.querySelector('input[type="time"]:first-of-type');
                    const endTime = container.querySelectorAll('input[type="time"]')[1];
                    const dayContainer = container.closest('.schedule-day-container');
                    const checkbox = dayContainer.querySelector('.schedule-checkbox');
                    if (!checkbox.checked) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                    startTime.value = e.target.dataset.start;
                    endTime.value = e.target.dataset.end;
                    updateScheduleSummary();
                }
            });

            // Update summary when time inputs change
            document.addEventListener('change', function(e) {
                if (e.target.type === 'time') {
                    updateScheduleSummary();
                }
            });

            function updateScheduleSummary() {
                let availableDays = 0;
                let totalHours = 0;

                scheduleCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        availableDays++;

                        const container = checkbox.closest('.schedule-day-container');
                        const startTime = container.querySelector('input[type="time"]:first-of-type');
                        const endTime = container.querySelectorAll('input[type="time"]')[1];

                        if (startTime.value && endTime.value) {
                            const start = new Date('2000-01-01 ' + startTime.value);
                            const end = new Date('2000-01-01 ' + endTime.value);
                            const hours = (end - start) / (1000 * 60 * 60);
                            if (hours > 0) {
                                totalHours += hours;
                            }
                        }
                    }
                });

                if (availableDaysCount) availableDaysCount.textContent = availableDays;
                if (totalHoursElement) totalHoursElement.textContent = totalHours.toFixed(1) + ' hrs';
            }

            // Services functionality
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const serviceCards = document.querySelectorAll('.service-card');
            const selectedServicesCount = document.getElementById('selected-services-count');
            const totalRevenueElement = document.getElementById('total-revenue');

            // Service card click handling
            serviceCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('.service-checkbox');
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Service checkbox changes
            serviceCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const card = this.closest('.service-card');
                    const indicator = card.querySelector('.service-indicator');
                    const checkIcon = card.querySelector('.service-check-icon');

                    if (this.checked) {
                        card.classList.add('ring-2', 'ring-purple-500', 'bg-gradient-to-br',
                            'from-purple-50', 'to-pink-50', 'dark:from-purple-900',
                            'dark:to-pink-900');
                        indicator.classList.add('bg-green-500', 'border-green-500');
                        indicator.classList.remove('bg-white', 'dark:bg-gray-800',
                            'border-gray-300', 'dark:border-gray-600');
                        checkIcon.classList.remove('opacity-0');
                        checkIcon.classList.add('opacity-100');
                    } else {
                        card.classList.remove('ring-2', 'ring-purple-500', 'bg-gradient-to-br',
                            'from-purple-50', 'to-pink-50', 'dark:from-purple-900',
                            'dark:to-pink-900');
                        indicator.classList.remove('bg-green-500', 'border-green-500');
                        indicator.classList.add('bg-white', 'dark:bg-gray-800', 'border-gray-300',
                            'dark:border-gray-600');
                        checkIcon.classList.add('opacity-0');
                        checkIcon.classList.remove('opacity-100');
                    }

                    updateServicesSummary();
                });
            });

            // Service quick action buttons
            if (document.getElementById('select-all-services')) {
                document.getElementById('select-all-services').addEventListener('click', function() {
                    serviceCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    });
                });
            }

            if (document.getElementById('clear-all-services')) {
                document.getElementById('clear-all-services').addEventListener('click', function() {
                    serviceCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    });
                });
            }

            function updateServicesSummary() {
                let selectedCount = 0;
                let totalRevenue = 0;

                serviceCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedCount++;

                        // Extract price from the card (if available)
                        const card = checkbox.closest('.service-card');
                        const priceElement = card.querySelector('.service-price-span');
                        if (priceElement) {
                            const priceText = priceElement.textContent.replace('$', '').replace(',', '');
                            const price = parseFloat(priceText);
                            if (!isNaN(price)) {
                                totalRevenue += price;
                            }
                        }
                    }
                });

                if (selectedServicesCount) selectedServicesCount.textContent = selectedCount;
                if (totalRevenueElement) totalRevenueElement.textContent = '$' + totalRevenue.toFixed(2);
            }

            // Initialize summaries
            updateScheduleSummary();
            updateServicesSummary();

            // Initialize existing schedule data on page load
            scheduleCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    // Trigger change event to properly initialize UI for existing schedules
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

        });
    </script>
@endsection
