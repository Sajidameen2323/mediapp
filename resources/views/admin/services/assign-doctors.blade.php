@extends('layouts.app')

@section('title', 'Assign Doctors to Service - Medi App')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Assign Doctors</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage doctor assignments for <strong>{{ $service->name }}</strong></p>
            </div>
            <a href="{{ route('admin.services.show', $service) }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-arrow-left"></i>
                Back to Service
            </a>
        </div>
    </div>

    <!-- Service Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $service->name }}</div>
                    <div class="text-sm text-purple-700 dark:text-purple-300">Service Name</div>
                </div>
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($service->price, 2) }}</div>
                    <div class="text-sm text-green-700 dark:text-green-300">Service Price</div>
                </div>
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $service->duration_minutes }} min</div>
                    <div class="text-sm text-blue-700 dark:text-blue-300">Duration</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Doctor Assignment Form -->
    <form action="{{ route('admin.services.update-doctor-assignments', $service) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Available Doctors</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Select doctors who can provide this service</p>
            </div>
            <div class="p-6">
                @if($doctors->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($doctors as $doctor)
                            <div class="group relative border-2 border-gray-200 dark:border-gray-600 rounded-xl p-6 hover:border-purple-400 dark:hover:border-purple-500 transition-all duration-300 hover:shadow-lg">
                                <div class="absolute top-4 right-4">
                                    <div class="relative">
                                        <input type="checkbox" 
                                               name="doctors[]" 
                                               id="doctor_{{ $doctor->id }}" 
                                               value="{{ $doctor->id }}"
                                               {{ in_array($doctor->id, $assignedDoctors) ? 'checked' : '' }}
                                               class="peer h-6 w-6 cursor-pointer appearance-none rounded-md border-2 border-gray-300 dark:border-gray-600 checked:bg-purple-600 checked:border-purple-600 transition-all duration-200">
                                        <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4 h-4 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity duration-200" 
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-5">
                                    <div class="flex-shrink-0">
                                        @if($doctor->profile_image)
                                            <img src="{{ Storage::url($doctor->profile_image) }}" 
                                                 alt="{{ $doctor->user->name }}" 
                                                 class="h-20 w-20 rounded-xl object-cover shadow-md group-hover:shadow-xl transition-shadow duration-300">
                                        @else
                                            <div class="h-20 w-20 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-md group-hover:shadow-xl transition-shadow duration-300">
                                                <span class="text-white font-bold text-2xl">{{ substr($doctor->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1">
                                        <label for="doctor_{{ $doctor->id }}" class="block text-xl font-bold text-gray-900 dark:text-white cursor-pointer group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                            {{ $doctor->user->name }}
                                        </label>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $doctor->specialization }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $doctor->experience_years }} years exp.</span>
                                        </div>
                                        <div class="mt-3 space-y-2">
                                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-id-card-alt"></i>
                                                <span>License: {{ $doctor->license_number }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-envelope"></i>
                                                <span>{{ $doctor->user->email }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center gap-3">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fas fa-dollar-sign mr-1.5"></i>
                                                ${{ number_format($doctor->consultation_fee, 2) }}
                                            </span>
                                            @if($doctor->is_available)
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                    <i class="fas fa-check-circle mr-1.5"></i>
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <i class="fas fa-times-circle mr-1.5"></i>
                                                    Unavailable
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600 mb-4">
                            <i class="fas fa-user-md text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No doctors available</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">There are no registered doctors in the system yet.</p>
                        <a href="{{ route('admin.doctors.create') }}" 
                           class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-plus"></i>
                            Add New Doctor
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($doctors->count() > 0)
            <!-- Form Actions -->
            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Currently {{ count($assignedDoctors) }} doctor{{ count($assignedDoctors) != 1 ? 's' : '' }} assigned to this service
                </div>
                <div class="space-x-4">
                    <a href="{{ route('admin.services.show', $service) }}" 
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Update Assignments
                    </button>
                </div>
            </div>
        @endif
    </form>

    @if(count($assignedDoctors) > 0)
        <!-- Currently Assigned Doctors -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Currently Assigned Doctors</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Doctors who are currently providing this service</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($service->doctors as $assignedDoctor)
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 border border-green-200 dark:border-green-700">
                            <div class="flex items-center space-x-3">
                                @if($assignedDoctor->profile_image)
                                    <img src="{{ Storage::url($assignedDoctor->profile_image) }}" 
                                         alt="{{ $assignedDoctor->user->name }}" 
                                         class="h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ substr($assignedDoctor->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-semibold text-green-900 dark:text-green-100">{{ $assignedDoctor->user->name }}</h4>
                                    <p class="text-sm text-green-700 dark:text-green-300">{{ $assignedDoctor->specialization }}</p>
                                    <p class="text-xs text-green-600 dark:text-green-400">${{ number_format($assignedDoctor->consultation_fee, 2) }} consultation fee</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add visual feedback when checkboxes are toggled
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="doctors[]"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.group');
            if (this.checked) {
                card.classList.add('border-purple-400', 'dark:border-purple-500');
                card.classList.remove('border-gray-200', 'dark:border-gray-600');
            } else {
                card.classList.remove('border-purple-400', 'dark:border-purple-500');
                card.classList.add('border-gray-200', 'dark:border-gray-600');
            }
        });
        
        // Apply initial state
        if (checkbox.checked) {
            const card = checkbox.closest('.group');
            card.classList.add('border-purple-400', 'dark:border-purple-500');
            card.classList.remove('border-gray-200', 'dark:border-gray-600');
        }
    });
});
</script>
@endsection
