@extends('layouts.app')

@section('title', 'Edit Service - Medi App')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Service</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Update service information and settings</p>
    </div>

    <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Service Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Update the details of the medical service</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $service->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                           placeholder="e.g., General Consultation, Blood Test, X-Ray"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                        <option value="">Select Category</option>
                        <option value="consultation" {{ old('category', $service->category) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        <option value="diagnostic" {{ old('category', $service->category) == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                        <option value="laboratory" {{ old('category', $service->category) == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="radiology" {{ old('category', $service->category) == 'radiology' ? 'selected' : '' }}>Radiology</option>
                        <option value="surgery" {{ old('category', $service->category) == 'surgery' ? 'selected' : '' }}>Surgery</option>
                        <option value="therapy" {{ old('category', $service->category) == 'therapy' ? 'selected' : '' }}>Therapy</option>
                        <option value="emergency" {{ old('category', $service->category) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="specialty" {{ old('category', $service->category) == 'specialty' ? 'selected' : '' }}>Specialty</option>
                        <option value="other" {{ old('category', $service->category) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price ($)</label>
                    <input type="number" 
                           id="price" 
                           name="price" 
                           value="{{ old('price', $service->price) }}"
                           min="0" 
                           step="0.01" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                           placeholder="0.00"
                           required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (minutes)</label>
                    <input type="number" 
                           id="duration_minutes" 
                           name="duration_minutes" 
                           value="{{ old('duration_minutes', $service->duration_minutes) }}"
                           min="1" 
                           max="480" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" 
                           placeholder="30"
                           required>
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                              placeholder="Detailed description of the service, what it includes, any preparations needed, etc."
                              required>{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                            Service is active and available for booking
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Doctor Assignments -->
        @if($service->doctors->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assigned Doctors</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Doctors currently providing this service</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($service->doctors as $doctor)
                        <div class="flex items-center space-x-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-700">
                            @if($doctor->profile_image)
                                <img src="{{ Storage::url($doctor->profile_image) }}" 
                                     alt="{{ $doctor->user->name }}" 
                                     class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">{{ substr($doctor->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-900 dark:text-blue-100">{{ $doctor->user->name }}</h4>
                                <p class="text-sm text-blue-700 dark:text-blue-300">{{ $doctor->specialization }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">${{ number_format($doctor->consultation_fee, 2) }} consultation fee</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.services.assign-doctors', $service) }}" 
                       class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-user-md"></i>
                        Manage Doctor Assignments
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Service Statistics -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Statistics</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Current service information and status</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $service->doctors->count() }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">Assigned Doctors</div>
                    </div>
                    
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $service->created_at->format('M j, Y') }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">Created Date</div>
                    </div>
                    
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $service->updated_at->format('M j, Y') }}</div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">Last Updated</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.services.index') }}" 
               class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl font-semibold hover:from-purple-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-save mr-2"></i>
                Update Service
            </button>
        </div>
    </form>
</div>
@endsection
