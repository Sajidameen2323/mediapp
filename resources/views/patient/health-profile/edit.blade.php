@extends('layouts.app')

@section('title', 'Edit Health Profile - Medi App')

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
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Health Profile</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Update your health information to keep it current and accurate</p>
    </div>

    <form action="{{ route('patient.health-profile.update', $healthProfile) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Basic Health Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-heart mr-3 text-red-600 dark:text-red-400"></i>
                    Basic Health Information
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your basic physical measurements and blood type</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blood Type</label>
                        <select id="blood_type" name="blood_type" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Blood Type</option>
                            <option value="A+" {{ old('blood_type', $healthProfile->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type', $healthProfile->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type', $healthProfile->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type', $healthProfile->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type', $healthProfile->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type', $healthProfile->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type', $healthProfile->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type', $healthProfile->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Height (cm)</label>
                        <input type="number" id="height" name="height" step="0.1" min="50" max="300"
                               value="{{ old('height', $healthProfile->height) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Enter height in cm">
                        @error('height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" step="0.1" min="20" max="500"
                               value="{{ old('weight', $healthProfile->weight) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Enter weight in kg">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-pills mr-3 text-green-600 dark:text-green-400"></i>
                    Medical Information
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Allergies, medications, and medical conditions</p>
            </div>
            <div class="p-6 space-y-6">                <div>
                    <label for="allergies" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Allergies</label>
                    <textarea id="allergies" name="allergies" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Separate each allergy with a semicolon (;) - Example: Peanuts; Shellfish; Penicillin; Pollen">{{ old('allergies', $healthProfile->allergies) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use semicolon (;) to separate multiple allergies for better organization
                    </p>
                    @error('allergies')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                <div>
                    <label for="medications" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Medications</label>
                    <textarea id="medications" name="medications" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Separate each medication with a semicolon (;) - Example: Lisinopril 10mg daily; Metformin 500mg twice daily; Vitamin D3 1000IU">{{ old('medications', $healthProfile->medications) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use semicolon (;) to separate multiple medications with dosages
                    </p>
                    @error('medications')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                <div>
                    <label for="medical_conditions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medical Conditions</label>
                    <textarea id="medical_conditions" name="medical_conditions" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Separate each condition with a semicolon (;) - Example: Type 2 Diabetes; Hypertension; Asthma; Previous appendectomy (2019)">{{ old('medical_conditions', $healthProfile->medical_conditions) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use semicolon (;) to separate multiple conditions, surgeries, or medical history
                    </p>
                    @error('medical_conditions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-phone-alt mr-3 text-red-600 dark:text-red-400"></i>
                    Emergency Contact
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Person to contact in case of emergency</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Name</label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                               value="{{ old('emergency_contact_name', $healthProfile->emergency_contact_name) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Full name">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Phone</label>
                        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone"
                               value="{{ old('emergency_contact_phone', $healthProfile->emergency_contact_phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Phone number">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relationship</label>
                        <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship"
                               value="{{ old('emergency_contact_relationship', $healthProfile->emergency_contact_relationship) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="e.g., Spouse, Parent, Sibling, Friend">
                        @error('emergency_contact_relationship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Insurance Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-shield-alt mr-3 text-purple-600 dark:text-purple-400"></i>
                    Insurance Information
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your health insurance details</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="insurance_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insurance Provider</label>
                        <input type="text" id="insurance_provider" name="insurance_provider"
                               value="{{ old('insurance_provider', $healthProfile->insurance_provider) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Insurance company name">
                        @error('insurance_provider')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Policy Number</label>
                        <input type="text" id="insurance_policy_number" name="insurance_policy_number"
                               value="{{ old('insurance_policy_number', $healthProfile->insurance_policy_number) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Policy/Member ID">
                        @error('insurance_policy_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_group_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Group Number</label>
                        <input type="text" id="insurance_group_number" name="insurance_group_number"
                               value="{{ old('insurance_group_number', $healthProfile->insurance_group_number) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Group number (if applicable)">
                        @error('insurance_group_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insurance Phone</label>
                        <input type="tel" id="insurance_phone" name="insurance_phone"
                               value="{{ old('insurance_phone', $healthProfile->insurance_phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Insurance company phone">
                        @error('insurance_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Lifestyle Information -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-running mr-3 text-yellow-600 dark:text-yellow-400"></i>
                    Lifestyle Information
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your lifestyle habits and dietary information</p>
            </div>
            <div class="p-6 space-y-6">                <div>
                    <label for="dietary_restrictions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dietary Restrictions</label>
                    <textarea id="dietary_restrictions" name="dietary_restrictions" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Separate each restriction with a semicolon (;) - Example: Vegetarian; Gluten-free; Lactose intolerant; No nuts; Low sodium">{{ old('dietary_restrictions', $healthProfile->dietary_restrictions) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use semicolon (;) to separate multiple dietary restrictions or preferences
                    </p>
                    @error('dietary_restrictions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="smoking_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Smoking Status</label>
                        <select id="smoking_status" name="smoking_status"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Status</option>
                            <option value="never" {{ old('smoking_status', $healthProfile->smoking_status) == 'never' ? 'selected' : '' }}>Never</option>
                            <option value="former" {{ old('smoking_status', $healthProfile->smoking_status) == 'former' ? 'selected' : '' }}>Former</option>
                            <option value="current" {{ old('smoking_status', $healthProfile->smoking_status) == 'current' ? 'selected' : '' }}>Current</option>
                        </select>
                        @error('smoking_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alcohol_consumption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alcohol Consumption</label>
                        <select id="alcohol_consumption" name="alcohol_consumption"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Frequency</option>
                            <option value="never" {{ old('alcohol_consumption', $healthProfile->alcohol_consumption) == 'never' ? 'selected' : '' }}>Never</option>
                            <option value="rarely" {{ old('alcohol_consumption', $healthProfile->alcohol_consumption) == 'rarely' ? 'selected' : '' }}>Rarely</option>
                            <option value="occasionally" {{ old('alcohol_consumption', $healthProfile->alcohol_consumption) == 'occasionally' ? 'selected' : '' }}>Occasionally</option>
                            <option value="weekly" {{ old('alcohol_consumption', $healthProfile->alcohol_consumption) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="daily" {{ old('alcohol_consumption', $healthProfile->alcohol_consumption) == 'daily' ? 'selected' : '' }}>Daily</option>
                        </select>
                        @error('alcohol_consumption')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="exercise_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Exercise Frequency</label>
                        <select id="exercise_frequency" name="exercise_frequency"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">Select Frequency</option>
                            <option value="sedentary" {{ old('exercise_frequency', $healthProfile->exercise_frequency) == 'sedentary' ? 'selected' : '' }}>Sedentary</option>
                            <option value="light" {{ old('exercise_frequency', $healthProfile->exercise_frequency) == 'light' ? 'selected' : '' }}>Light (1-2 times/week)</option>
                            <option value="moderate" {{ old('exercise_frequency', $healthProfile->exercise_frequency) == 'moderate' ? 'selected' : '' }}>Moderate (3-4 times/week)</option>
                            <option value="active" {{ old('exercise_frequency', $healthProfile->exercise_frequency) == 'active' ? 'selected' : '' }}>Active (5-6 times/week)</option>
                            <option value="very_active" {{ old('exercise_frequency', $healthProfile->exercise_frequency) == 'very_active' ? 'selected' : '' }}>Very Active (daily)</option>
                        </select>
                        @error('exercise_frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="lifestyle_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Lifestyle Notes</label>
                    <textarea id="lifestyle_notes" name="lifestyle_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Any additional information about your lifestyle, sleep habits, stress levels, etc.">{{ old('lifestyle_notes', $healthProfile->lifestyle_notes) }}</textarea>
                    @error('lifestyle_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-sticky-note mr-3 text-gray-600 dark:text-gray-400"></i>
                    Additional Notes
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Any other important health information</p>
            </div>
            <div class="p-6">
                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Health Information</label>
                    <textarea id="additional_notes" name="additional_notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none"
                              placeholder="Include any other relevant health information, family history, or concerns you'd like your healthcare provider to know">{{ old('additional_notes', $healthProfile->additional_notes) }}</textarea>
                    @error('additional_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-end">
            <a href="{{ route('patient.health-profile.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transition-all duration-200">
                <i class="fas fa-save mr-2"></i>
                Update Health Profile
            </button>
        </div>
    </form>
</div>
@endsection
