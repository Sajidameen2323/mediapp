@extends('layouts.app')

@section('title', 'Service Details - Medi App')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Service Details</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">View complete service information and assignments</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.services.edit', $service) }}" 
               class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-edit"></i>
                Edit Service
            </a>
            <a href="{{ route('admin.services.assign-doctors', $service) }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-user-md"></i>
                Assign Doctors
            </a>
            <a href="{{ route('admin.services.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Service Overview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="p-6 text-center">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-briefcase-medical text-white text-2xl"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $service->name }}</h2>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-4">
                        {{ ucfirst($service->category) }}
                    </span>
                    
                    <div class="mb-4">
                        @if($service->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                <i class="fas fa-times-circle mr-1"></i>
                                Inactive
                            </span>
                        @endif
                    </div>
                    
                    <div class="text-center space-y-3">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($service->price, 2) }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Service Price</div>
                        </div>
                        
                        <div>
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $service->duration_minutes }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Minutes Duration</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Assigned Doctors</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $service->doctors->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Service Category</span>
                            <span class="font-semibold text-gray-900 dark:text-white capitalize">{{ $service->category }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created Date</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $service->created_at->format('M j, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $service->updated_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Description -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Description</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900 dark:text-white leading-relaxed">{{ $service->description }}</p>
                </div>
            </div>

            <!-- Service Features -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Features</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Appointment Based</h4>
                                <p class="text-xs text-blue-700 dark:text-blue-300">Patients can book this service online</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-green-900 dark:text-green-100">{{ $service->duration_minutes }} Minutes</h4>
                                <p class="text-xs text-green-700 dark:text-green-300">Estimated service duration</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-md text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Professional Care</h4>
                                <p class="text-xs text-purple-700 dark:text-purple-300">Provided by qualified medical professionals</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-900 dark:text-yellow-100">Quality Assured</h4>
                                <p class="text-xs text-yellow-700 dark:text-yellow-300">Follows medical standards and protocols</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assigned Doctors -->
            @if($service->doctors->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Doctors</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Medical professionals providing this service</p>
                    </div>
                    <a href="{{ route('admin.services.assign-doctors', $service) }}" 
                       class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 text-sm font-medium">
                        <i class="fas fa-edit"></i>
                        Manage
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($service->doctors as $doctor)
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-xl p-4 border border-blue-200 dark:border-blue-700">
                                <div class="flex items-center space-x-4">
                                    @if($doctor->profile_image)
                                        <img src="{{ Storage::url($doctor->profile_image) }}" 
                                             alt="{{ $doctor->user->name }}" 
                                             class="h-16 w-16 rounded-full object-cover border-2 border-blue-200 dark:border-blue-600">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                            <span class="text-white font-bold text-xl">{{ substr($doctor->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-blue-900 dark:text-blue-100">{{ $doctor->user->name }}</h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">{{ $doctor->specialization }}</p>
                                        <p class="text-xs text-blue-600 dark:text-blue-400">{{ $doctor->experience_years }} years experience</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">${{ number_format($doctor->consultation_fee, 2) }}</span>
                                            @if($doctor->is_available)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Unavailable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 flex space-x-2">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" 
                                       class="flex-1 text-center bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                        View Profile
                                    </a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                                       class="flex-1 text-center bg-gray-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <!-- No Doctors Assigned -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="p-12 text-center">
                    <div class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600 mb-4">
                        <i class="fas fa-user-md text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Doctors Assigned</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">This service doesn't have any doctors assigned yet. Assign doctors to make this service available for booking.</p>
                    <a href="{{ route('admin.services.assign-doctors', $service) }}" 
                       class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray:800 text-white px-6 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-user-md"></i>
                        Assign Doctors
                    </a>
                </div>
            </div>
            @endif

            <!-- Service Timeline -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Timeline</h3>
                </div>
                <div class="p-6 mb-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-plus text-white text-sm"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900 dark:text-white">Service created</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $service->created_at->format('M j, Y \a\t g:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <i class="fas fa-edit text-white text-sm"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900 dark:text-white">Last updated</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                {{ $service->updated_at->format('M j, Y \a\t g:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
