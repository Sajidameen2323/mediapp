@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Request Holiday</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Submit a holiday request for approval</p>
                </div>
                <a href="{{ route('doctor.holidays.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Holidays
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">            <form id="holiday-form" class="p-6 space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Holiday Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="e.g., Annual Leave, Medical Leave, Conference Attendance">
                    <div class="text-red-500 text-sm mt-1 hidden" id="title-error"></div>
                </div>
                
                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="start_date" name="start_date" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               min="{{ date('Y-m-d') }}">
                        <div class="text-red-500 text-sm mt-1 hidden" id="start_date-error"></div>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="end_date" name="end_date" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               min="{{ date('Y-m-d') }}">
                        <div class="text-red-500 text-sm mt-1 hidden" id="end_date-error"></div>
                    </div>
                </div>

                <!-- Duration Display -->
                <div id="duration-display" class="hidden">
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-3">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day text-blue-600 dark:text-blue-400 mr-2"></i>
                            <span class="text-blue-800 dark:text-blue-200">
                                Duration: <span id="duration-text" class="font-semibold"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <select id="reason" name="reason" required 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select a reason</option>
                        <option value="Personal Leave">Personal Leave</option>
                        <option value="Medical Leave">Medical Leave</option>
                        <option value="Family Emergency">Family Emergency</option>
                        <option value="Conference/Training">Conference/Training</option>
                        <option value="Vacation">Vacation</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="reason-error"></div>
                </div>

                <!-- Custom Reason (shown when "Other" is selected) -->
                <div id="custom-reason-field" class="hidden">
                    <label for="custom_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Please specify the reason <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="custom_reason" name="custom_reason" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="Enter the reason for your holiday request...">
                    <div class="text-red-500 text-sm mt-1 hidden" id="custom_reason-error"></div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Notes
                    </label>
                    <textarea id="notes" name="notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Any additional information or context for your holiday request..."></textarea>
                    <div class="text-red-500 text-sm mt-1 hidden" id="notes-error"></div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Please provide as much detail as possible to help with the approval process.
                    </p>
                </div>

                <!-- Information Box -->
                <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                    <div class="flex">
                        <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3"></i>
                        <div class="text-yellow-800 dark:text-yellow-200">
                            <h4 class="font-medium mb-2">Important Information</h4>
                            <ul class="text-sm space-y-1">
                                <li>• Holiday requests require admin approval</li>
                                <li>• Please submit requests at least 48 hours in advance</li>
                                <li>• Emergency requests will be reviewed as soon as possible</li>
                                <li>• You will be notified via email once your request is reviewed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 flex items-center">
                        <span id="submit-text">Submit Request</span>
                        <div id="submit-spinner" class="hidden ml-2">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Success/Error Notification -->
<div id="notification" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg max-w-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="notification-message"></span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('holiday-form');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const reasonSelect = document.getElementById('reason');
    const customReasonField = document.getElementById('custom-reason-field');
    const customReasonInput = document.getElementById('custom_reason');
    const durationDisplay = document.getElementById('duration-display');
    const durationText = document.getElementById('duration-text');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');

    // Update end date minimum when start date changes
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
        updateDuration();
    });

    // Update duration when end date changes
    endDateInput.addEventListener('change', updateDuration);

    // Show/hide custom reason field
    reasonSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            customReasonField.classList.remove('hidden');
            customReasonInput.required = true;
        } else {
            customReasonField.classList.add('hidden');
            customReasonInput.required = false;
            customReasonInput.value = '';
        }
    });

    function updateDuration() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            durationText.textContent = diffDays + (diffDays === 1 ? ' day' : ' days');
            durationDisplay.classList.remove('hidden');
        } else {
            durationDisplay.classList.add('hidden');
        }
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        document.querySelectorAll('.text-red-500').forEach(error => {
            error.classList.add('hidden');
        });

        // If "Other" is selected, use custom reason
        if (reasonSelect.value === 'Other' && customReasonInput.value.trim()) {
            reasonSelect.value = customReasonInput.value.trim();
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Submitting...';
        submitSpinner.classList.remove('hidden');

        const formData = new FormData(form);
        
        fetch('{{ route("doctor.holidays.store") }}', {
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
                showNotification('Holiday request submitted successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '{{ route("doctor.holidays.index") }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to submit holiday request. Please try again.', 'error');
            
            // Reset button state
            submitBtn.disabled = false;
            submitText.textContent = 'Submit Request';
            submitSpinner.classList.add('hidden');
        });
    });

    function showNotification(message, type) {
        const notification = document.getElementById('notification');
        const messageEl = document.getElementById('notification-message');
        
        messageEl.textContent = message;
        
        if (type === 'error') {
            notification.querySelector('div').className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg max-w-sm';
            notification.querySelector('i').className = 'fas fa-exclamation-circle mr-2';
        } else {
            notification.querySelector('div').className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg max-w-sm';
            notification.querySelector('i').className = 'fas fa-check-circle mr-2';
        }
        
        notification.classList.remove('hidden');
        
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    }
});
</script>
@endsection
