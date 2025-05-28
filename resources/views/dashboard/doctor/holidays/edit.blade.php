@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Holiday Request</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Update your holiday request details</p>
                </div>
                <a href="{{ route('doctor.dashboard.holidays') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Holidays
                </a>
            </div>
        </div>

        <!-- Status Warning -->
        @if($holiday->status !== 'pending')
            <div class="mb-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3"></i>
                    <div class="text-yellow-800 dark:text-yellow-200">
                        <h4 class="font-medium">Cannot Edit This Request</h4>
                        <p class="text-sm mt-1">This holiday request has already been {{ $holiday->status }} and cannot be modified.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <form id="holiday-form" class="p-6 space-y-6" {{ $holiday->status !== 'pending' ? 'style=pointer-events:none;opacity:0.6;' : '' }}>
                @csrf
                @method('PUT')
                
                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="start_date" name="start_date" required 
                               value="{{ $holiday->start_date->format('Y-m-d') }}"
                               {{ $holiday->status !== 'pending' ? 'disabled' : '' }}
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <div class="text-red-500 text-sm mt-1 hidden" id="start_date-error"></div>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="end_date" name="end_date" required 
                               value="{{ $holiday->end_date->format('Y-m-d') }}"
                               {{ $holiday->status !== 'pending' ? 'disabled' : '' }}
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <div class="text-red-500 text-sm mt-1 hidden" id="end_date-error"></div>
                    </div>
                </div>

                <!-- Duration Display -->
                <div id="duration-display">
                    <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-3">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day text-blue-600 dark:text-blue-400 mr-2"></i>
                            <span class="text-blue-800 dark:text-blue-200">
                                Duration: <span id="duration-text" class="font-semibold"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Current Status</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Request submitted on {{ $holiday->created_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $holiday->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' : ($holiday->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300') }}">
                            {{ ucfirst($holiday->status) }}
                        </span>
                    </div>
                    @if($holiday->admin_notes)
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <h5 class="font-medium text-gray-900 dark:text-white text-sm">Admin Notes:</h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $holiday->admin_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <select id="reason" name="reason" required 
                            {{ $holiday->status !== 'pending' ? 'disabled' : '' }}
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select a reason</option>
                        <option value="Personal Leave" {{ $holiday->reason === 'Personal Leave' ? 'selected' : '' }}>Personal Leave</option>
                        <option value="Medical Leave" {{ $holiday->reason === 'Medical Leave' ? 'selected' : '' }}>Medical Leave</option>
                        <option value="Family Emergency" {{ $holiday->reason === 'Family Emergency' ? 'selected' : '' }}>Family Emergency</option>
                        <option value="Conference/Training" {{ $holiday->reason === 'Conference/Training' ? 'selected' : '' }}>Conference/Training</option>
                        <option value="Vacation" {{ $holiday->reason === 'Vacation' ? 'selected' : '' }}>Vacation</option>
                        <option value="Other" {{ !in_array($holiday->reason, ['Personal Leave', 'Medical Leave', 'Family Emergency', 'Conference/Training', 'Vacation']) ? 'selected' : '' }}>Other</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="reason-error"></div>
                </div>

                <!-- Custom Reason (shown when "Other" is selected) -->
                <div id="custom-reason-field" class="{{ !in_array($holiday->reason, ['Personal Leave', 'Medical Leave', 'Family Emergency', 'Conference/Training', 'Vacation']) ? '' : 'hidden' }}">
                    <label for="custom_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Please specify the reason <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="custom_reason" name="custom_reason" 
                           value="{{ !in_array($holiday->reason, ['Personal Leave', 'Medical Leave', 'Family Emergency', 'Conference/Training', 'Vacation']) ? $holiday->reason : '' }}"
                           {{ $holiday->status !== 'pending' ? 'disabled' : '' }}
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
                              {{ $holiday->status !== 'pending' ? 'disabled' : '' }}
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Any additional information or context for your holiday request...">{{ $holiday->notes }}</textarea>
                    <div class="text-red-500 text-sm mt-1 hidden" id="notes-error"></div>
                </div>

                <!-- Submit Button -->
                @if($holiday->status === 'pending')
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="window.history.back()" 
                                class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="submit-btn"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 flex items-center">
                            <span id="submit-text">Update Request</span>
                            <div id="submit-spinner" class="hidden ml-2">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                    </div>
                @endif
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
    const durationText = document.getElementById('duration-text');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');

    // Calculate initial duration
    updateDuration();

    // Update end date minimum when start date changes
    if (startDateInput && !startDateInput.disabled) {
        startDateInput.addEventListener('change', function() {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
            updateDuration();
        });
    }

    // Update duration when end date changes
    if (endDateInput && !endDateInput.disabled) {
        endDateInput.addEventListener('change', updateDuration);
    }

    // Show/hide custom reason field
    if (reasonSelect && !reasonSelect.disabled) {
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
    }

    function updateDuration() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            durationText.textContent = diffDays + (diffDays === 1 ? ' day' : ' days');
        }
    }

    // Form submission (only if status is pending)
    if (form && {{ $holiday->status === 'pending' ? 'true' : 'false' }}) {
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
            submitText.textContent = 'Updating...';
            submitSpinner.classList.remove('hidden');

            const formData = new FormData(form);
            
            fetch('{{ route("doctor.holidays.update", $holiday) }}', {
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
                    showNotification('Holiday request updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("doctor.dashboard.holidays") }}';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update holiday request. Please try again.', 'error');
                
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Update Request';
                submitSpinner.classList.add('hidden');
            });
        });
    }

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
