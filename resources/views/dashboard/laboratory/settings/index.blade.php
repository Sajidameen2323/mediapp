@extends('layouts.laboratory')

@section('title', 'Laboratory Settings')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laboratory Settings</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Configure your laboratory's working hours, services, and availability.</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('laboratory.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your laboratory's basic details.</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Laboratory Name (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Laboratory Name
                    </label>
                    <input type="text" 
                           value="{{ $laboratory->name }}" 
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-400 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Contact administrator to change laboratory name.</p>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="contact_person_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Person Name
                        </label>
                        <input type="text" 
                               id="contact_person_name" 
                               name="contact_person_name" 
                               value="{{ old('contact_person_name', $laboratory->contact_person_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('contact_person_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_person_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Person Phone
                        </label>
                        <input type="text" 
                               id="contact_person_phone" 
                               name="contact_person_phone" 
                               value="{{ old('contact_person_phone', $laboratory->contact_person_phone) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('contact_person_phone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Working Hours -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Working Hours</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Set your laboratory's operating hours and working days.</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Opening and Closing Times -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="opening_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-clock mr-2 text-green-600 dark:text-green-400"></i>
                            Opening Time *
                        </label>
                        <input type="time" 
                               id="opening_time" 
                               name="opening_time" 
                               value="{{ old('opening_time', $laboratory->opening_time ? $laboratory->opening_time->format('H:i') : '08:00') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('opening_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="closing_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-clock mr-2 text-red-600 dark:text-red-400"></i>
                            Closing Time *
                        </label>
                        <input type="time" 
                               id="closing_time" 
                               name="closing_time" 
                               value="{{ old('closing_time', $laboratory->closing_time ? $laboratory->closing_time->format('H:i') : '17:00') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('closing_time')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Working Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-calendar-week mr-2 text-blue-600 dark:text-blue-400"></i>
                        Working Days *
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                        @php
                            $days = [
                                'monday' => 'Mon',
                                'tuesday' => 'Tue', 
                                'wednesday' => 'Wed',
                                'thursday' => 'Thu',
                                'friday' => 'Fri',
                                'saturday' => 'Sat',
                                'sunday' => 'Sun'
                            ];
                            $selectedDays = old('working_days', $laboratory->working_days ?? []);
                        @endphp
                        @foreach($days as $value => $label)
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <input type="checkbox" 
                                       name="working_days[]" 
                                       value="{{ $value }}"
                                       {{ in_array($value, $selectedDays) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('working_days')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Services & Fees -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Services & Fees</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure your laboratory's services and pricing.</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Consultation Fee -->
                <div>
                    <label for="consultation_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-dollar-sign mr-2 text-green-600 dark:text-green-400"></i>
                        Consultation Fee ($)
                    </label>
                    <input type="number" 
                           id="consultation_fee" 
                           name="consultation_fee" 
                           step="0.01"
                           min="0"
                           value="{{ old('consultation_fee', $laboratory->consultation_fee) }}"
                           placeholder="0.00"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('consultation_fee')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Services Offered -->
                <div>
                    <label for="services_offered" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-flask mr-2 text-purple-600 dark:text-purple-400"></i>
                        Services Offered
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @php
                            $services = [
                                'Blood Tests', 'Urine Tests', 'X-Ray', 'CT Scan', 'MRI', 'Ultrasound',
                                'ECG', 'EEG', 'Endoscopy', 'Pathology', 'Microbiology', 'Biochemistry',
                                'Hematology', 'Immunology', 'Molecular Diagnostics', 'Histopathology'
                            ];
                            $currentServices = old('services_offered', 
                                is_string($laboratory->services_offered) ? 
                                    explode(',', $laboratory->services_offered) : 
                                    ($laboratory->services_offered ?? [])
                            );
                            // Clean up array - remove empty values and trim
                            $currentServices = array_map('trim', array_filter($currentServices));
                        @endphp
                        @foreach($services as $service)
                            <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                <input type="checkbox" name="services_offered[]" value="{{ $service }}"
                                       {{ in_array($service, $currentServices) ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 dark:border-gray-600 rounded">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $service }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('services_offered')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Equipment Details -->
                <div>
                    <label for="equipment_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-microscope mr-2 text-blue-600 dark:text-blue-400"></i>
                        Equipment & Technology
                    </label>
                    <textarea id="equipment_details" 
                              name="equipment_details" 
                              rows="4"
                              placeholder="Describe your laboratory equipment and technology..."
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('equipment_details', $laboratory->equipment_details) }}</textarea>
                    @error('equipment_details')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Home Service -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Home Service</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Configure home collection service options.</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Home Service Available -->
                <div class="flex items-center">
                    <input type="hidden" name="home_service_available" value="0">
                    <input type="checkbox" 
                           id="home_service_available" 
                           name="home_service_available" 
                           value="1"
                           {{ old('home_service_available', $laboratory->home_service_available) ? 'checked' : '' }}
                           onchange="toggleHomeServiceFee()"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                    <label for="home_service_available" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fas fa-home mr-2 text-blue-600 dark:text-blue-400"></i>
                        Offer Home Collection Service
                    </label>
                </div>

                <!-- Home Service Fee -->
                <div id="home_service_fee_container" class="{{ old('home_service_available', $laboratory->home_service_available) ? '' : 'opacity-50' }}">
                    <label for="home_service_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-truck mr-2 text-green-600 dark:text-green-400"></i>
                        Home Service Fee ($)
                    </label>
                    <input type="number" 
                           id="home_service_fee" 
                           name="home_service_fee" 
                           step="0.01"
                           min="0"
                           value="{{ old('home_service_fee', $laboratory->home_service_fee) }}"
                           placeholder="0.00"
                           {{ old('home_service_available', $laboratory->home_service_available) ? '' : 'disabled' }}
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 dark:disabled:bg-gray-600 disabled:text-gray-500">
                    @error('home_service_fee')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6">
            <a href="{{ route('laboratory.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>

            <div class="flex space-x-3">
                <button type="button" 
                        onclick="resetForm()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <i class="fas fa-undo mr-2"></i>
                    Reset
                </button>

                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleHomeServiceFee() {
    const checkbox = document.getElementById('home_service_available');
    const container = document.getElementById('home_service_fee_container');
    const feeInput = document.getElementById('home_service_fee');
    
    if (checkbox.checked) {
        container.classList.remove('opacity-50');
        feeInput.disabled = false;
    } else {
        container.classList.add('opacity-50');
        feeInput.disabled = true;
        feeInput.value = '';
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will restore the original values.')) {
        window.location.reload();
    }
}

// Initialize home service fee toggle on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleHomeServiceFee();
});
</script>
@endsection
