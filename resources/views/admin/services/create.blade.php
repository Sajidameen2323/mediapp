@extends('layouts.app')

@section('title', 'Add New Service - Medi App')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Service</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Create a new medical service for the platform</p>
    </div>

    <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Service Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Enter the details of the medical service</p>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Service Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
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
                        <option value="consultation" {{ old('category') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        <option value="diagnostic" {{ old('category') == 'diagnostic' ? 'selected' : '' }}>Diagnostic</option>
                        <option value="laboratory" {{ old('category') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="radiology" {{ old('category') == 'radiology' ? 'selected' : '' }}>Radiology</option>
                        <option value="surgery" {{ old('category') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                        <option value="therapy" {{ old('category') == 'therapy' ? 'selected' : '' }}>Therapy</option>
                        <option value="emergency" {{ old('category') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        <option value="specialty" {{ old('category') == 'specialty' ? 'selected' : '' }}>Specialty</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
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
                           value="{{ old('price') }}"
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
                           value="{{ old('duration_minutes') }}"
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
                              required>{{ old('description') }}</textarea>
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
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-white">
                            Service is active and available for booking
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Features -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Service Features</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Additional information about the service</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Appointment Required</h4>
                            <p class="text-xs text-blue-700 dark:text-blue-300">Patients must book in advance</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-green-50 dark:bg-green-900/30 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Medical Coverage</h4>
                            <p class="text-xs text-green-700 dark:text-green-300">May be covered by insurance</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-md text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Professional Service</h4>
                            <p class="text-xs text-purple-700 dark:text-purple-300">Provided by qualified doctors</p>
                        </div>
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
                Create Service
            </button>
        </div>
    </form>
</div>
@endsection
