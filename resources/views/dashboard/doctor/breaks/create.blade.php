@extends('layouts.doctor')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Break</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Schedule a break in your availability</p>
                </div>
                <a href="{{ route('doctor.breaks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Breaks
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">            <form id="break-form" class="p-6 space-y-6">
                @csrf
                
                <!-- Break Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Break Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                           placeholder="e.g., Lunch Break, Personal Time">
                    <div class="text-red-500 text-sm mt-1 hidden" id="title-error"></div>
                </div>

                <!-- Time Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="start_time" name="start_time" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white">
                        <div class="text-red-500 text-sm mt-1 hidden" id="start_time-error"></div>
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="end_time" name="end_time" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white">
                        <div class="text-red-500 text-sm mt-1 hidden" id="end_time-error"></div>
                    </div>
                </div>

                <!-- Day Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Select Day(s) <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-7 gap-2">
                        @php
                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            $dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        @endphp
                        @foreach($days as $i => $day)
                            <label class="flex flex-col items-center p-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-orange-50 dark:hover:bg-orange-900 cursor-pointer">
                                <input type="checkbox" name="selected_days[]" value="{{ $day }}" class="mb-1" @if(isset($selectedDay) && $selectedDay === $day) checked @endif>
                                <span class="text-xs text-gray-700 dark:text-gray-300">{{ $dayNames[$i] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="text-red-500 text-sm mt-1 hidden" id="selected_days-error"></div>
                </div>

                <!-- Break Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Break Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select break type</option>
                        <option value="lunch">Lunch</option>
                        <option value="personal">Personal Time</option>
                        <option value="meeting">Meeting</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Optional notes about this break..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                            class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-md transition-colors duration-200 flex items-center">
                        <span id="submit-text">Add Break</span>
                        <div id="submit-spinner" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('break-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        document.querySelectorAll('.text-red-500').forEach(error => {
            error.classList.add('hidden');
        });

        // Validate that at least one day is selected
        const selectedDays = document.querySelectorAll('input[name="selected_days[]"]:checked');
        if (selectedDays.length === 0) {
            document.getElementById('selected_days-error').textContent = 'Please select at least one day.';
            document.getElementById('selected_days-error').classList.remove('hidden');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Adding...';
        submitSpinner.classList.remove('hidden');

        const formData = new FormData(form);
        
        fetch('{{ route("doctor.breaks.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Break created successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("doctor.breaks.index") }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to create break. Please try again.', 'error');
            
            // Reset button state
            submitBtn.disabled = false;
            submitText.textContent = 'Add Break';
            submitSpinner.classList.add('hidden');
        });
    });

    function showNotification(message, type) {
        // Implement notification logic or use your preferred notification system
        alert(message);
    }
});
</script>
@endsection
