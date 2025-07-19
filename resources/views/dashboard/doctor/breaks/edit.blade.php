@extends('layouts.doctor')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Break</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Update your break interval</p>
                </div>
                <a href="{{ route('doctor.breaks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Breaks
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form id="break-edit-form" action="{{ route('doctor.breaks.update', $break) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Break Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Break Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $break->title) }}" required 
                           class="w-full px-3 py-2 border @error('title') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('title') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $break->start_time->format('H:i')) }}" required 
                               class="w-full px-3 py-2 border @error('start_time') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('start_time') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $break->end_time->format('H:i')) }}" required 
                               class="w-full px-3 py-2 border @error('end_time') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('end_time') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Day of Week -->
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Day of Week <span class="text-red-500">*</span>
                    </label>
                    <select id="day_of_week" name="day_of_week" required 
                            class="w-full px-3 py-2 border @error('day_of_week') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('day_of_week') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        @endphp
                        @foreach($days as $day)
                            <option value="{{ $day }}" @if(old('day_of_week', $break->day_of_week) === $day) selected @endif>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Break Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Break Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required 
                            class="w-full px-3 py-2 border @error('type') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('type') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white">
                        <option value="lunch" @if(old('type', $break->type) === 'lunch') selected @endif>Lunch</option>
                        <option value="personal" @if(old('type', $break->type) === 'personal') selected @endif>Personal Time</option>
                        <option value="meeting" @if(old('type', $break->type) === 'meeting') selected @endif>Meeting</option>
                        <option value="other" @if(old('type', $break->type) === 'other') selected @endif>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-3 py-2 border @error('notes') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror rounded-md shadow-sm focus:outline-none focus:ring-2 @error('notes') focus:ring-red-500 focus:border-red-500 @else focus:ring-orange-500 focus:border-orange-500 @enderror dark:bg-gray-700 dark:text-white"
                              placeholder="Optional notes about this break...">{{ old('notes', $break->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                            class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md transition-colors duration-200 flex items-center">
                        <span id="submit-text">Update Break</span>
                        <div id="submit-spinner" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <!-- General Error Message -->
        @if($errors->any())
            <div class="mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There were {{ $errors->count() }} error(s) with your submission
                        </h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('break-edit-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');

    // Form submission handler
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Updating...';
        submitSpinner.classList.remove('hidden');

        // Form will submit normally and redirect with success message
    });

    // Clear error states on input
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove error classes when user starts typing
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300', 'dark:border-gray-600');
            
            // Hide error message
            const errorMsg = this.parentNode.querySelector('.text-red-500');
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
