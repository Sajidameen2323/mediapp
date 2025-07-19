@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pharmacy->pharmacy_name }}</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">Pharmacy Details and Information</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('admin.pharmacies.edit', $pharmacy) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-800 text-white font-medium rounded-lg transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Pharmacy
                        </a>
                        <a href="{{ route('admin.pharmacies.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200 w-full sm:w-auto justify-center sm:justify-start">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div> <!-- Status and Quick Actions -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Status</h3>
                            <div class="mt-2" id="status-badge-wrapper">
                                @if ($pharmacy->is_available)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Available
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Unavailable
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="button" id="toggleAvailabilityBtn"
                                data-toggle-url="{{ route('admin.pharmacies.toggle-availability', $pharmacy) }}"
                                class="px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                                       {{ $pharmacy->is_available
                                           ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800'
                                           : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800' }}">
                                {{ $pharmacy->is_available ? 'Disable' : 'Enable' }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Statistics</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Total Orders:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $pharmacy->orders_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Member Since:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ $pharmacy->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Quick Contact</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-purple-600 dark:text-purple-400 w-5"></i>
                            <a href="tel:{{ $pharmacy->user->phone_number }}"
                                class="ml-2 text-purple-600 dark:text-purple-400 hover:underline">
                                {{ $pharmacy->user->phone_number }}
                            </a>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-purple-600 dark:text-purple-400 w-5"></i>
                            <a href="mailto:{{ $pharmacy->user->email }}"
                                class="ml-2 text-purple-600 dark:text-purple-400 hover:underline">
                                {{ $pharmacy->user->email }}
                            </a>
                        </div>
                        @if ($pharmacy->emergency_contact)
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 w-5"></i>
                                <a href="tel:{{ $pharmacy->emergency_contact }}"
                                    class="ml-2 text-red-600 dark:text-red-400 hover:underline">
                                    {{ $pharmacy->emergency_contact }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div> <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
                        <h2 class="text-xl font-semibold text-purple-800 dark:text-purple-200">
                            <i class="fas fa-info-circle mr-2"></i>
                            Basic Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pharmacy Name</dt>
                                <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $pharmacy->pharmacy_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">License Number</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->license_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Owner Name</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->user->name }}</dd>
                            </div>
                            @if ($pharmacy->description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->description }}</dd>
                                </div>
                            @endif
                            @if ($pharmacy->website)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $pharmacy->website }}" target="_blank"
                                            class="text-purple-600 dark:text-purple-400 hover:underline">
                                            {{ $pharmacy->website }}
                                        </a>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
                        <h2 class="text-xl font-semibold text-purple-800 dark:text-purple-200">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Location
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->user->address }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">City</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->city }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">State/Province</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->state }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Postal Code</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->postal_code }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Country</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->country }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div> <!-- Working Hours and Services -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Working Hours -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
                        <h2 class="text-xl font-semibold text-purple-800 dark:text-purple-200">
                            <i class="fas fa-clock mr-2"></i>
                            Working Hours
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Operating Hours</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($pharmacy->opening_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($pharmacy->closing_time)->format('g:i A') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Working Days</dt>
                                <dd class="mt-1">
                                    @php
                                        $workingDays = is_array($pharmacy->working_days) ? $pharmacy->working_days : [];
                                    @endphp
                                    @if (!empty($workingDays))
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($workingDays as $day)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                                    {{ $day }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Not specified</span>
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
                        <h2 class="text-xl font-semibold text-purple-800 dark:text-purple-200">
                            <i class="fas fa-truck mr-2"></i>
                            Delivery Service
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Home Delivery</dt>
                                <dd class="mt-1">
                                    @if ($pharmacy->home_delivery_available)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Available
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <i class="fas fa-times mr-1"></i>
                                            Not Available
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @if ($pharmacy->home_delivery_available)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Fee</dt>
                                    <dd class="mt-1 text-gray-900 dark:text-white">
                                        @if ($pharmacy->home_delivery_fee > 0)
                                            ${{ number_format($pharmacy->home_delivery_fee, 2) }}
                                        @else
                                            <span class="text-green-600 dark:text-green-400 font-medium">Free
                                                Delivery</span>
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> <!-- Specializations and Services -->
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div
                        class="px-6 py-4 bg-purple-50 dark:bg-purple-900/30 border-b border-purple-100 dark:border-purple-800">
                        <h2 class="text-xl font-semibold text-purple-800 dark:text-purple-200">
                            <i class="fas fa-flask mr-2"></i>
                            Specializations & Services
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Specializations -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Specializations</h3>
                                @php
                                    if (is_string($pharmacy->specializations)) {
                                        $decoded_specializations = json_decode($pharmacy->specializations, true);
                                        // Check if JSON decoding was successful
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            // Update $pharmacy->specializations with the decoded value.
                                            // This allows the subsequent `is_array($pharmacy->specializations)` check
                                            // to work correctly if it was a JSON string representing an array.
                                            $pharmacy->specializations = $decoded_specializations;
                                        } else {
                                            // If JSON was invalid, set to an empty array to prevent errors
                                            // and allow the subsequent logic to treat it as "no specializations".
                                            $pharmacy->specializations = [];
                                        }
                                    }
                                @endphp
                                @php

                                    $specializations = is_array($pharmacy->specializations)
                                        ? $pharmacy->specializations
                                        : [];
                                @endphp
                                @if (!empty($specializations))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($specializations as $specialization)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                                {{ $specialization }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">No specializations specified</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Account Information -->
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            <i class="fas fa-user mr-2"></i>
                            Account Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $pharmacy->user->phone_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration Date</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">
                                    {{ $pharmacy->created_at->format('F j, Y g:i A') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">
                                    {{ $pharmacy->updated_at->format('F j, Y g:i A') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div> <!-- Danger Zone -->
            <div class="mt-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border-l-4 border-red-500 dark:border-red-400">
                    <div class="px-6 py-4 bg-red-50 dark:bg-red-900/30 border-b border-red-100 dark:border-red-800">
                        <h2 class="text-xl font-semibold text-red-800 dark:text-red-200">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Danger Zone
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            These actions are irreversible. Please be certain before proceeding.
                        </p>
                        <form action="{{ route('admin.pharmacies.destroy', $pharmacy) }}" method="POST" class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this pharmacy? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Pharmacy
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                // Auto remove after 3 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);
            }

            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.getElementById('toggleAvailabilityBtn');

                if (toggleButton) {
                    toggleButton.addEventListener('click', function() {
                        const button = this;
                        const url = button.dataset.toggleUrl;
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content');

                        const originalButtonText = button.textContent;
                        button.disabled = true;
                        button.textContent = 'Updating...';

                        fetch(url, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(errData => {
                                        throw new Error(errData.message ||
                                            `Error: ${response.status}`);
                                    }).catch(() => {
                                        throw new Error(
                                            `Error: ${response.status} ${response.statusText}`);
                                    });
                                }
                                return response.json();
                            }).then(data => {
                                if (data.success) {
                                    const isAvailable = data.is_available;

                                    // Update button text and style
                                    button.textContent = isAvailable ? 'Disable' : 'Enable';

                                    const enableClasses = ['bg-green-100', 'dark:bg-green-900',
                                        'text-green-700', 'dark:text-green-200',
                                        'hover:bg-green-200', 'dark:hover:bg-green-800'
                                    ];
                                    const disableClasses = ['bg-red-100', 'dark:bg-red-900', 'text-red-700',
                                        'dark:text-red-200',
                                        'hover:bg-red-200', 'dark:hover:bg-red-800'
                                    ];

                                    if (isAvailable) { // Pharmacy is now available, button shows "Disable"
                                        button.classList.remove(...enableClasses);
                                        button.classList.add(...disableClasses);
                                    } else { // Pharmacy is now unavailable, button shows "Enable"
                                        button.classList.remove(...disableClasses);
                                        button.classList.add(...enableClasses);
                                    }

                                    // Update status display
                                    // Ensure the div wrapping the status display has id="status-badge-wrapper".
                                    // e.g., <div class="mt-2" id="status-badge-wrapper"> ... status span ... </div>
                                    const statusWrapper = document.getElementById('status-badge-wrapper');
                                    if (statusWrapper) {
                                        if (isAvailable) {
                                            statusWrapper.innerHTML = `
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i> Available
                                </span>`;
                                        } else {
                                            statusWrapper.innerHTML = `
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                    <i class="fas fa-times-circle mr-1"></i> Unavailable
                                </span>`;
                                        }
                                    } else {
                                        console.warn(
                                            'Element with ID "status-badge-wrapper" not found. Status UI will not be updated dynamically. Please add id="status-badge-wrapper" to the div.mt-2 element containing the status.'
                                        );
                                    }

                                    // Show notification
                                    showNotification(data.message, 'success');

                                } else {
                                    showNotification(data.message ||
                                        'An error occurred while updating status.', 'error');
                                    button.textContent =
                                        originalButtonText; // Restore button text on failure
                                }
                            })
                            .catch(error => {
                                console.error('Error toggling availability:', error);
                                showNotification(error.message || 'An unexpected error occurred.', 'error');
                                button.textContent = originalButtonText; // Restore button text on error
                            })
                            .finally(() => {
                                button.disabled = false; // Re-enable button
                            });
                    });
                }
            });
        </script>
    @endpush
@endsection
