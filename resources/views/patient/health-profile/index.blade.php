@extends('layouts.app')

@section('title', 'Health Profile - Medi App')

@push('head')
<meta name="user-name" content="{{ auth()->user()->name }}">
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Health Profile</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your comprehensive health information</p>
        </div>        <div class="flex space-x-3">
            @if($healthProfile)
                <a href="{{ route('patient.health-profile.permissions.index') }}" 
                   class="inline-flex items-center gap-2 bg-blue-600 dark:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 dark:hover:bg-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-user-shield"></i>
                    Manage Access
                </a>
                <a href="{{ route('patient.health-profile.edit') }}" 
                   class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </a>
            @else
                <a href="{{ route('patient.health-profile.create') }}" 
                   class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    Create Profile
                </a>
            @endif
        </div>
    </div>    @if($healthProfile)
        <!-- Health Profile Content -->
        <div id="health-profile-content" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Basic Health Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Health Data -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-heart mr-3 text-red-600 dark:text-red-400"></i>
                            Basic Health Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">                            <div class="text-center">
                                <div class="bg-red-100 dark:bg-red-900 rounded-lg p-4">
                                    <i class="fas fa-tint text-2xl text-red-600 dark:text-red-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Blood Type</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white" data-field="blood-type">
                                        {{ $healthProfile->blood_type ?? 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-4">
                                    <i class="fas fa-ruler-vertical text-2xl text-blue-600 dark:text-blue-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Height</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white" data-field="height">
                                        {{ $healthProfile->height ? $healthProfile->height . ' cm' : 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 dark:bg-green-900 rounded-lg p-4">
                                    <i class="fas fa-weight text-2xl text-green-600 dark:text-green-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Weight</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white" data-field="weight">
                                        {{ $healthProfile->weight ? $healthProfile->weight . ' kg' : 'Not specified' }}
                                    </p>
                                </div>
                            </div>
                        </div>                        @if($healthProfile->height && $healthProfile->weight)
                            <div class="mt-6 text-center">
                                <div class="bg-purple-100 dark:bg-purple-900 rounded-lg p-4">
                                    <i class="fas fa-calculator text-2xl text-purple-600 dark:text-purple-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">BMI</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white" data-field="bmi">
                                        {{ $healthProfile->bmi }} - {{ $healthProfile->bmi_category }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-notes-medical mr-3 text-green-600 dark:text-green-400"></i>
                            Medical Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">                        @if($healthProfile->allergies)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Allergies</label>
                                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-3" data-field="allergies" data-value="{{ $healthProfile->allergies }}">
                                    @if(str_contains($healthProfile->allergies, ';'))
                                        <ul class="space-y-1">
                                            @foreach(array_filter(array_map('trim', explode(';', $healthProfile->allergies))) as $allergy)
                                                <li class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xs"></i>
                                                    {{ $allergy }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                            <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xs"></i>
                                            {{ $healthProfile->allergies }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif                        @if($healthProfile->medications)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Medications</label>
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3" data-field="medications" data-value="{{ $healthProfile->medications }}">
                                    @if(str_contains($healthProfile->medications, ';'))
                                        <ul class="space-y-1">
                                            @foreach(array_filter(array_map('trim', explode(';', $healthProfile->medications))) as $medication)
                                                <li class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-capsules text-blue-500 mr-2 text-xs"></i>
                                                    {{ $medication }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                            <i class="fas fa-capsules text-blue-500 mr-2 text-xs"></i>
                                            {{ $healthProfile->medications }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif                        @if($healthProfile->medical_conditions)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medical Conditions</label>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3" data-field="medical-conditions" data-value="{{ $healthProfile->medical_conditions }}">
                                    @if(str_contains($healthProfile->medical_conditions, ';'))
                                        <ul class="space-y-1">
                                            @foreach(array_filter(array_map('trim', explode(';', $healthProfile->medical_conditions))) as $condition)
                                                <li class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-notes-medical text-yellow-500 mr-2 text-xs"></i>
                                                    {{ $condition }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                            <i class="fas fa-notes-medical text-yellow-500 mr-2 text-xs"></i>
                                            {{ $healthProfile->medical_conditions }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif                        @if($healthProfile->family_history)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family History</label>
                                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3" data-field="family-history" data-value="{{ $healthProfile->family_history }}">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $healthProfile->family_history }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lifestyle Information -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-running mr-3 text-orange-600 dark:text-orange-400"></i>
                            Lifestyle Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exercise Frequency</label>
                                <div class="flex items-center">
                                    <i class="fas fa-dumbbell text-orange-600 dark:text-orange-400 mr-2"></i>
                                    <span class="text-sm text-gray-800 dark:text-gray-200 capitalize" data-field="exercise-frequency">
                                        {{ $healthProfile->exercise_frequency ?? 'Not specified' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Habits</label>
                                <div class="space-y-1">
                                    <div class="flex items-center">
                                        <i class="fas fa-smoking text-gray-600 dark:text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-800 dark:text-gray-200" data-field="smoking-status">
                                            Smoker: {{ $healthProfile->is_smoker ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-wine-glass-alt text-gray-600 dark:text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-800 dark:text-gray-200" data-field="alcohol-status">
                                            Alcohol Consumer: {{ $healthProfile->is_alcohol_consumer ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>                        @if($healthProfile->dietary_restrictions)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dietary Restrictions</label>
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3" data-field="dietary-restrictions" data-value="{{ $healthProfile->dietary_restrictions }}">
                                    @if(str_contains($healthProfile->dietary_restrictions, ';'))
                                        <ul class="space-y-1">
                                            @foreach(array_filter(array_map('trim', explode(';', $healthProfile->dietary_restrictions))) as $restriction)
                                                <li class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                                    <i class="fas fa-utensils text-green-500 mr-2 text-xs"></i>
                                                    {{ $restriction }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-800 dark:text-gray-200 flex items-center">
                                            <i class="fas fa-utensils text-green-500 mr-2 text-xs"></i>
                                            {{ $healthProfile->dietary_restrictions }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif                        @if($healthProfile->lifestyle_notes)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lifestyle Notes</label>
                                <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-lg p-3" data-field="lifestyle-notes" data-value="{{ $healthProfile->lifestyle_notes }}">
                                    <p class="text-sm text-gray-800 dark:text-gray-200">{{ $healthProfile->lifestyle_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Emergency Contact -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-red-600 dark:text-red-400"></i>
                            Emergency Contact
                        </h3>
                    </div>
                    <div class="p-6">                        @if($healthProfile->emergency_contact_name)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Name</p>
                                    <p class="font-medium text-gray-900 dark:text-white" data-field="emergency-contact-name">{{ $healthProfile->emergency_contact_name }}</p>
                                </div>
                                @if($healthProfile->emergency_contact_phone)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                                        <p class="font-medium text-gray-900 dark:text-white" data-field="emergency-contact-phone">{{ $healthProfile->emergency_contact_phone }}</p>
                                    </div>
                                @endif
                                @if($healthProfile->emergency_contact_relationship)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Relationship</p>
                                        <p class="font-medium text-gray-900 dark:text-white" data-field="emergency-contact-relationship">{{ $healthProfile->emergency_contact_relationship }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm" data-field="emergency-contact-empty">No emergency contact specified</p>
                        @endif
                    </div>
                </div>

                <!-- Insurance Information -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-shield-alt mr-3 text-blue-600 dark:text-blue-400"></i>
                            Insurance Information
                        </h3>
                    </div>
                    <div class="p-6">                        @if($healthProfile->insurance_provider)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Provider</p>
                                    <p class="font-medium text-gray-900 dark:text-white" data-field="insurance-provider">{{ $healthProfile->insurance_provider }}</p>
                                </div>
                                @if($healthProfile->insurance_policy_number)
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Policy Number</p>
                                        <p class="font-medium text-gray-900 dark:text-white" data-field="insurance-policy">{{ $healthProfile->insurance_policy_number }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm" data-field="insurance-empty">No insurance information specified</p>
                        @endif
                    </div>
                </div>                <!-- Additional Notes -->
                @if($healthProfile->additional_notes)
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-sticky-note mr-3 text-purple-600 dark:text-purple-400"></i>
                                Additional Notes
                            </h3>
                        </div>
                        <div class="p-6" data-field="additional-notes" data-value="{{ $healthProfile->additional_notes }}">
                            <p class="text-sm text-gray-800 dark:text-gray-200">{{ $healthProfile->additional_notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                    <div class="p-6 space-y-3">                        <button onclick="printHealthProfile()" 
                                class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium hover:from-gray-700 hover:to-gray-800 transition-all duration-200">
                            <i class="fas fa-print"></i>
                            Print Profile
                        </button>
                        <form action="{{ route('patient.health-profile.destroy') }}" method="POST" class="w-full" 
                              onsubmit="return confirm('Are you sure you want to delete your health profile? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium hover:from-red-700 hover:to-red-800 transition-all duration-200">
                                <i class="fas fa-trash"></i>
                                Delete Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No Health Profile -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600 mb-6">
                    <i class="fas fa-heart-broken text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Health Profile Found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8">Create your comprehensive health profile to help healthcare providers better understand your medical history and current health status.</p>
                <a href="{{ route('patient.health-profile.create') }}" 
                   class="inline-flex items-center gap-2 bg-gray-900 dark:bg-gray-800 text-white px-8 py-4 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    Create Health Profile
                </a>
            </div>
        </div>    @endif
</div>

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.css">
<style>
    /* Hide print header by default */
    .print-header {
        display: none;
    }
    
    /* Basic print preparations */
    @media print {
        .print-header {
            display: block !important;
        }
        
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
<script src="{{ asset('js/health-profile-print.js') }}"></script>
@endpush
@endsection
