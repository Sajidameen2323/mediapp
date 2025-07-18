@extends('layouts.patient')

@section('title', 'Create Health Profile - Medi App')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <a href="{{ route('patient.health-profile.index') }}"
                    class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200 mr-4">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Health Profile
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Health Profile</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Fill out your comprehensive health information to help
                healthcare providers better assist you</p>
        </div>

        <form action="{{ route('patient.health-profile.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Basic Health Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-heart mr-3 text-red-600 dark:text-red-400"></i>
                        Basic Health Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your basic physical measurements and blood type
                    </p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="blood_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blood Type</label>
                            <select id="blood_type" name="blood_type"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select Blood Type</option>
                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('blood_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="height"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
                            <input type="number" id="height" name="height" value="{{ old('height') }}" min="30"
                                max="300" step="0.1"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="e.g. 175">
                            @error('height')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" value="{{ old('weight') }}" min="2"
                                max="300" step="0.1"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="e.g. 70">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-notes-medical mr-3 text-green-600 dark:text-green-400"></i>
                        Medical Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your medical history and current conditions</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="allergies"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Allergies</label>
                        <textarea id="allergies" name="allergies" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Separate each allergy with a semicolon (;) - Example: Peanuts; Shellfish; Penicillin; Pollen">{{ old('allergies') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Use semicolon (;) to separate multiple allergies for better organization
                        </p>
                        @error('allergies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="medications"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                Medications</label>
                            <button type="button" 
                                id="populate-medications-btn"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-pills mr-1.5"></i>
                                Load from Prescriptions
                            </button>
                        </div>
                        <textarea id="medications" name="medications" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Separate each medication with a semicolon (;) - Example: Lisinopril 10mg daily; Metformin 500mg twice daily; Vitamin D3 1000IU">{{ old('medications') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Use semicolon (;) to separate multiple medications with dosages
                        </p>
                        @error('medications')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="medical_conditions"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medical
                            Conditions</label>
                        <textarea id="medical_conditions" name="medical_conditions" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Separate each condition with a semicolon (;) - Example: Type 2 Diabetes; Hypertension; Asthma; Previous appendectomy (2019)">{{ old('medical_conditions') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Use semicolon (;) to separate multiple conditions, surgeries, or medical history
                        </p>
                        @error('medical_conditions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="family_history"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Family Medical
                            History</label>
                        <textarea id="family_history" name="family_history" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Any relevant family medical history (diabetes, heart disease, cancer, etc.)">{{ old('family_history') }}</textarea>
                        @error('family_history')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-red-600 dark:text-red-400"></i>
                        Emergency Contact Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Someone to contact in case of medical
                        emergency</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="emergency_contact_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact
                                Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Full name">
                            @error('emergency_contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_phone"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact
                                Phone</label>
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Phone number">
                            @error('emergency_contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="emergency_contact_relationship"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship</label>
                            <input type="text" id="emergency_contact_relationship"
                                name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="e.g. Spouse, Parent, Sibling">
                            @error('emergency_contact_relationship')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insurance Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3 text-blue-600 dark:text-blue-400"></i>
                        Insurance Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your health insurance details</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="insurance_provider"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insurance
                                Provider</label>
                            <input type="text" id="insurance_provider" name="insurance_provider"
                                value="{{ old('insurance_provider') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Insurance company name">
                            @error('insurance_provider')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="insurance_policy_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Policy
                                Number</label>
                            <input type="text" id="insurance_policy_number" name="insurance_policy_number"
                                value="{{ old('insurance_policy_number') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Policy or member ID">
                            @error('insurance_policy_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lifestyle Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-running mr-3 text-orange-600 dark:text-orange-400"></i>
                        Lifestyle Information
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your lifestyle habits and preferences</p>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="exercise_frequency"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exercise
                            Frequency</label>
                        <select id="exercise_frequency" name="exercise_frequency"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select exercise frequency</option>
                            <option value="sedentary" {{ old('exercise_frequency') == 'sedentary' ? 'selected' : '' }}>
                                Never</option>
                            <option value="light" {{ old('exercise_frequency') == 'light' ? 'selected' : '' }}>Rarely
                                (less than once a week)</option>
                            <option value="moderate" {{ old('exercise_frequency') == 'moderate' ? 'selected' : '' }}>
                                Weekly (1-3 times per week)</option>
                            <option value="active" {{ old('exercise_frequency') == 'active' ? 'selected' : '' }}>Daily or
                                almost daily</option>
                        </select>
                        @error('exercise_frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Smoking
                                Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="is_smoker" value="0"
                                        {{ old('is_smoker', '0') == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">Non-smoker</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_smoker" value="1"
                                        {{ old('is_smoker') == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">Smoker</span>
                                </label>
                            </div>
                            @error('is_smoker')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Alcohol
                                Consumption</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="is_alcohol_consumer" value="0"
                                        {{ old('is_alcohol_consumer', '0') == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">Does not consume
                                        alcohol</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="is_alcohol_consumer" value="1"
                                        {{ old('is_alcohol_consumer') == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600">
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">Consumes alcohol</span>
                                </label>
                            </div>
                            @error('is_alcohol_consumer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="dietary_restrictions"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dietary
                            Restrictions</label>
                        <textarea id="dietary_restrictions" name="dietary_restrictions" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Separate each restriction with a semicolon (;) - Example: Vegetarian; Gluten-free; Lactose intolerant; No nuts; Low sodium">{{ old('dietary_restrictions') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Use semicolon (;) to separate multiple dietary restrictions or preferences
                        </p>
                        @error('dietary_restrictions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lifestyle_notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lifestyle Notes</label>
                        <textarea id="lifestyle_notes" name="lifestyle_notes" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Any additional lifestyle information that might be relevant to your health">{{ old('lifestyle_notes') }}</textarea>
                        @error('lifestyle_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-sticky-note mr-3 text-purple-600 dark:text-purple-400"></i>
                        Additional Notes
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Any other information you'd like healthcare
                        providers to know</p>
                </div>
                <div class="p-6">
                    <div>
                        <label for="additional_notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Health
                            Information</label>
                        <textarea id="additional_notes" name="additional_notes" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            placeholder="Any other relevant health information, concerns, or notes">{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('patient.health-profile.index') }}"
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gray-900 dark:bg-gray-800 text-white rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>
                    Create Health Profile
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const populateMedicationsBtn = document.getElementById('populate-medications-btn');
    const medicationsTextarea = document.getElementById('medications');
    
    if (populateMedicationsBtn && medicationsTextarea) {
        populateMedicationsBtn.addEventListener('click', function() {
            // Disable button and show loading state
            populateMedicationsBtn.disabled = true;
            const originalText = populateMedicationsBtn.innerHTML;
            populateMedicationsBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i>Loading...';
            
            // Make AJAX request to get active medications
            fetch('{{ route('patient.health-profile.medications.active') }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.medications.length > 0) {
                    // If there's existing content, ask user if they want to replace or append
                    const existingContent = medicationsTextarea.value.trim();
                    let shouldProceed = true;
                    let shouldReplace = false;
                    
                    if (existingContent) {
                        const action = confirm(
                            'You already have medications listed. Do you want to:\n\n' +
                            'OK - Replace existing medications with active prescriptions\n' +
                            'Cancel - Add active prescriptions to existing medications'
                        );
                        shouldReplace = action;
                    }
                    
                    if (shouldProceed) {
                        const medicationsText = data.medications.join('; ');
                        
                        if (shouldReplace || !existingContent) {
                            medicationsTextarea.value = medicationsText;
                        } else {
                            // Append new medications
                            medicationsTextarea.value = existingContent + '; ' + medicationsText;
                        }
                        
                        // Show success message
                        showNotification('success', `Successfully loaded ${data.count} active medication(s) from your prescriptions.`);
                    }
                } else if (data.success && data.medications.length === 0) {
                    showNotification('info', 'No active medications found in your current prescriptions.');
                } else {
                    showNotification('error', 'Failed to load medications. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error fetching medications:', error);
                showNotification('error', 'An error occurred while loading medications. Please try again.');
            })
            .finally(() => {
                // Re-enable button and restore text
                populateMedicationsBtn.disabled = false;
                populateMedicationsBtn.innerHTML = originalText;
            });
        });
    }
    
    function showNotification(type, message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${getNotificationClasses(type)}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${getNotificationIcon(type)} mr-3"></i>
                <span class="text-sm font-medium">${message}</span>
                <button type="button" class="ml-4 text-current opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }
    
    function getNotificationClasses(type) {
        switch(type) {
            case 'success':
                return 'bg-green-50 border border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200';
            case 'error':
                return 'bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200';
            case 'info':
                return 'bg-blue-50 border border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-200';
            default:
                return 'bg-gray-50 border border-gray-200 text-gray-800 dark:bg-gray-900/20 dark:border-gray-800 dark:text-gray-200';
        }
    }
    
    function getNotificationIcon(type) {
        switch(type) {
            case 'success':
                return 'fa-check-circle';
            case 'error':
                return 'fa-exclamation-circle';
            case 'info':
                return 'fa-info-circle';
            default:
                return 'fa-bell';
        }
    }
});
</script>
@endpush
