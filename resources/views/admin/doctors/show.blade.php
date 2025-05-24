@extends('layouts.app')

@section('title', 'Doctor Details - Medi App')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Doctor Details</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">View complete doctor profile and information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.doctors.edit', $doctor) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-edit"></i>
                Edit Doctor
            </a>
            <a href="{{ route('admin.doctors.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    @if($doctor->profile_image)
                        <img src="{{ Storage::url($doctor->profile_image) }}" 
                             alt="{{ $doctor->user->name }}" 
                             class="h-32 w-32 rounded-full object-cover mx-auto border-4 border-blue-100 dark:border-blue-900 shadow-lg">
                    @else
                        <div class="h-32 w-32 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-white font-bold text-4xl">{{ substr($doctor->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $doctor->user->name }}</h2>
                    <p class="text-lg text-blue-600 dark:text-blue-400 font-medium">{{ $doctor->specialization }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">License: {{ $doctor->license_number }}</p>
                    
                    <div class="mt-4">
                        @if($doctor->is_available)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <i class="fas fa-times-circle mr-1"></i>
                                Unavailable
                            </span>
                        @endif
                    </div>
                    
                    <div class="mt-6 text-center">
                        <div class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($doctor->consultation_fee, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Consultation Fee</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Experience</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $doctor->experience_years }} year{{ $doctor->experience_years != 1 ? 's' : '' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Services</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $doctor->services->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Gender</span>
                            <span class="font-semibold text-gray-900 dark:text-white capitalize">{{ $doctor->user->gender ?? 'Not specified' }}</span>
                        </div>
                        @if($doctor->user->date_of_birth)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Age</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $doctor->user->date_of_birth->age }} years</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contact Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white">{{ $doctor->user->email }}</span>
                            </div>
                        </div>
                        
                        @if($doctor->user->phone_number)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone</label>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-green-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white">{{ $doctor->user->phone_number }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($doctor->user->address)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</label>
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2 mt-1"></i>
                                <span class="text-gray-900 dark:text-white">{{ $doctor->user->address }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Professional Information</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Qualifications</label>
                            <p class="text-gray-900 dark:text-white">{{ $doctor->qualifications }}</p>
                        </div>
                        
                        @if($doctor->bio)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Biography</label>
                            <p class="text-gray-900 dark:text-white leading-relaxed">{{ $doctor->bio }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Services -->
            @if($doctor->services->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Services Provided</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($doctor->services as $service)
                            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100">{{ $service->name }}</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300 mb-2">{{ $service->category }}</p>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-blue-600 dark:text-blue-400">${{ number_format($service->price, 2) }}</span>
                                    <span class="text-blue-600 dark:text-blue-400">{{ $service->duration_minutes }} min</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Work Schedule -->
            @if($doctor->schedules->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Work Schedule</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            @php
                                $schedule = $doctor->schedules->firstWhere('day_of_week', $day);
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg {{ $schedule && $schedule->is_available ? 'bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-700' : 'bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600' }}">
                                <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $day }}</span>
                                @if($schedule && $schedule->is_available)
                                    <span class="text-green-700 dark:text-green-300 text-sm">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Not available</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Account Created</label>
                            <p class="text-gray-900 dark:text-white">{{ $doctor->user->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Last Updated</label>
                            <p class="text-gray-900 dark:text-white">{{ $doctor->updated_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Account Status</label>
                            @if($doctor->user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">User Type</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas fa-user-md mr-1"></i>
                                {{ ucfirst($doctor->user->user_type) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
