@extends('layouts.app')

@section('title', 'Edit Appointment Configuration - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Appointment Configuration</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Configure appointment system settings</p>
            </div>
            <a href="{{ route('admin.appointment-config.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Configuration
            </a>
        </div>
    </div>

    <form action="{{ route('admin.appointment-config.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Booking Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Booking Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_booking_days_ahead" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Booking Days Ahead</label>
                        <input type="number" name="max_booking_days_ahead" id="max_booking_days_ahead" 
                               value="{{ old('max_booking_days_ahead', $config->max_booking_days_ahead) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="1" max="365" required>
                        @error('max_booking_days_ahead')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="min_booking_hours_ahead" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Min Booking Hours Ahead</label>
                        <input type="number" name="min_booking_hours_ahead" id="min_booking_hours_ahead" 
                               value="{{ old('min_booking_hours_ahead', $config->min_booking_hours_ahead) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="168" required>
                        @error('min_booking_hours_ahead')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="default_slot_duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Slot Duration (minutes)</label>
                        <select name="default_slot_duration" id="default_slot_duration" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach([15, 30, 45, 60, 90, 120] as $duration)
                                <option value="{{ $duration }}" {{ old('default_slot_duration', $config->default_slot_duration) == $duration ? 'selected' : '' }}>
                                    {{ $duration }} minutes
                                </option>
                            @endforeach
                        </select>
                        @error('default_slot_duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                        <select name="timezone" id="timezone" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @foreach($timezones as $value => $label)
                                <option value="{{ $value }}" {{ old('timezone', $config->timezone) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Buffer Times -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-clock mr-2 text-green-600"></i>
                    Buffer Times
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="buffer_time_before" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buffer Time Before (minutes)</label>
                        <input type="number" name="buffer_time_before" id="buffer_time_before" 
                               value="{{ old('buffer_time_before', $config->buffer_time_before) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="120" required>
                        @error('buffer_time_before')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="buffer_time_after" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buffer Time After (minutes)</label>
                        <input type="number" name="buffer_time_after" id="buffer_time_after" 
                               value="{{ old('buffer_time_after', $config->buffer_time_after) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="120" required>
                        @error('buffer_time_after')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Operating Hours -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-business-time mr-2 text-purple-600"></i>
                    Default Operating Hours
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="default_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                        <input type="time" name="default_start_time" id="default_start_time" 
                               value="{{ old('default_start_time', \Carbon\Carbon::parse($config->default_start_time)->format('H:i')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('default_start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="default_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                        <input type="time" name="default_end_time" id="default_end_time" 
                               value="{{ old('default_end_time', \Carbon\Carbon::parse($config->default_end_time)->format('H:i')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('default_end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Limits -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-users mr-2 text-orange-600"></i>
                    Appointment Limits
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="max_appointments_per_patient_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Appointments per Patient per Day</label>
                        <input type="number" name="max_appointments_per_patient_per_day" id="max_appointments_per_patient_per_day" 
                               value="{{ old('max_appointments_per_patient_per_day', $config->max_appointments_per_patient_per_day) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="1" max="20" required>
                        @error('max_appointments_per_patient_per_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="max_appointments_per_doctor_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Appointments per Doctor per Day</label>
                        <input type="number" name="max_appointments_per_doctor_per_day" id="max_appointments_per_doctor_per_day" 
                               value="{{ old('max_appointments_per_doctor_per_day', $config->max_appointments_per_doctor_per_day) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="1" max="50" required>
                        @error('max_appointments_per_doctor_per_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-percentage mr-2 text-yellow-600"></i>
                    Tax Settings
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="tax_enabled" id="tax_enabled" value="1" 
                               {{ old('tax_enabled', $config->tax_enabled) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                        <label for="tax_enabled" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Enable Tax Calculation</label>
                    </div>
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                        <input type="number" name="tax_rate" id="tax_rate" step="0.0001" 
                               value="{{ old('tax_rate', $config->tax_rate * 100) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="99.9999" required>
                        @error('tax_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-credit-card mr-2 text-indigo-600"></i>
                    Payment Settings
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Accepted Payment Methods</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($paymentMethods as $method => $label)
                                <div class="flex items-center">
                                    <input type="checkbox" name="accepted_payment_methods[]" id="payment_{{ $method }}" value="{{ $method }}"
                                           {{ in_array($method, old('accepted_payment_methods', $config->accepted_payment_methods ?? [])) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                                    <label for="payment_{{ $method }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('accepted_payment_methods')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="require_payment_on_booking" id="require_payment_on_booking" value="1" 
                               {{ old('require_payment_on_booking', $config->require_payment_on_booking) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                        <label for="require_payment_on_booking" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Require Payment on Booking</label>
                    </div>
                    <div>
                        <label for="booking_deposit_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Booking Deposit Percentage (%)</label>
                        <input type="number" name="booking_deposit_percentage" id="booking_deposit_percentage" step="0.01"
                               value="{{ old('booking_deposit_percentage', $config->booking_deposit_percentage) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="100" required>
                        @error('booking_deposit_percentage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancellation & Rescheduling -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-calendar-times mr-2 text-red-600"></i>
                    Cancellation & Rescheduling
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="allow_cancellation" id="allow_cancellation" value="1" 
                                   {{ old('allow_cancellation', $config->allow_cancellation) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="allow_cancellation" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Allow Cancellation</label>
                        </div>
                        <div>
                            <label for="cancellation_hours_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cancellation Hours Limit</label>
                            <input type="number" name="cancellation_hours_limit" id="cancellation_hours_limit" 
                                   value="{{ old('cancellation_hours_limit', $config->cancellation_hours_limit) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   min="0" max="168" required>
                            @error('cancellation_hours_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="allow_rescheduling" id="allow_rescheduling" value="1" 
                                   {{ old('allow_rescheduling', $config->allow_rescheduling) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="allow_rescheduling" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Allow Rescheduling</label>
                        </div>
                        <div>
                            <label for="reschedule_hours_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rescheduling Hours Limit</label>
                            <input type="number" name="reschedule_hours_limit" id="reschedule_hours_limit" 
                                   value="{{ old('reschedule_hours_limit', $config->reschedule_hours_limit) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   min="0" max="168" required>
                            @error('reschedule_hours_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval & Notification Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-check-circle mr-2 text-teal-600"></i>
                    Approval & Notification Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="auto_approve_appointments" id="auto_approve_appointments" value="1" 
                                   {{ old('auto_approve_appointments', $config->auto_approve_appointments) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="auto_approve_appointments" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Auto Approve Appointments</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="require_admin_approval" id="require_admin_approval" value="1" 
                                   {{ old('require_admin_approval', $config->require_admin_approval) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="require_admin_approval" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Require Admin Approval</label>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="send_confirmation_email" id="send_confirmation_email" value="1" 
                                   {{ old('send_confirmation_email', $config->send_confirmation_email) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="send_confirmation_email" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Send Confirmation Email</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="send_reminder_email" id="send_reminder_email" value="1" 
                                   {{ old('send_reminder_email', $config->send_reminder_email) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="send_reminder_email" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Send Reminder Email</label>
                        </div>
                        <div>
                            <label for="reminder_hours_before" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reminder Hours Before</label>
                            <input type="number" name="reminder_hours_before" id="reminder_hours_before" 
                                   value="{{ old('reminder_hours_before', $config->reminder_hours_before) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   min="1" max="168" required>
                            @error('reminder_hours_before')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Settings -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Emergency Settings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_emergency_bookings" id="allow_emergency_bookings" value="1" 
                               {{ old('allow_emergency_bookings', $config->allow_emergency_bookings) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                        <label for="allow_emergency_bookings" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Allow Emergency Bookings</label>
                    </div>
                    <div>
                        <label for="emergency_booking_hours_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Booking Hours Limit</label>
                        <input type="number" name="emergency_booking_hours_limit" id="emergency_booking_hours_limit" 
                               value="{{ old('emergency_booking_hours_limit', $config->emergency_booking_hours_limit) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               min="0" max="24" required>
                        @error('emergency_booking_hours_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.appointment-config.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>
                Save Configuration
            </button>
        </div>
    </form>
</div>
@endsection
