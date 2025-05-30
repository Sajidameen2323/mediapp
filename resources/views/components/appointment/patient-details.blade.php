{{-- Patient Details Component --}}
<div class="max-w-4xl mx-auto">
    {{-- Current User Info --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl p-6 mb-8">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-blue-600 dark:bg-blue-500 rounded-xl flex items-center justify-center text-white text-lg font-bold mr-4">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

    {{-- Patient Details Form --}}
    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Contact Information --}}
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-user mr-3 text-blue-600 dark:text-blue-400"></i>
                Contact Information
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="patient_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" 
                               id="patient_phone" 
                               name="patient_phone" 
                               value="{{ old('patient_phone', auth()->user()->phone_number) }}"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="Enter your phone number"
                               required>
                    </div>
                    @error('patient_phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Emergency Contact
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone-alt text-gray-400"></i>
                        </div>
                        <input type="tel" 
                               id="emergency_contact" 
                               name="emergency_contact"
                               value="{{ old('emergency_contact') }}"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               placeholder="Emergency contact number">
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointment Details --}}
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-clipboard-list mr-3 text-green-600 dark:text-green-400"></i>
                Appointment Information
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="chief_complaint" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Chief Complaint <span class="text-red-500">*</span>
                    </label>
                    <textarea id="chief_complaint" 
                              name="chief_complaint" 
                              rows="3"
                              class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Briefly describe your main concern or reason for visit"
                              required>{{ old('chief_complaint') }}</textarea>
                    @error('chief_complaint')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="symptoms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Symptoms
                    </label>
                    <textarea id="symptoms" 
                              name="symptoms" 
                              rows="3"
                              class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Describe any symptoms you're experiencing">{{ old('symptoms') }}</textarea>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Priority Level
                    </label>
                    <select id="priority" 
                            name="priority"
                            class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="routine" {{ old('priority') === 'routine' ? 'selected' : '' }}>Routine</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="emergency" {{ old('priority') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Notes Section --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center mb-4">
            <i class="fas fa-notes-medical mr-3 text-purple-600 dark:text-purple-400"></i>
            Additional Information
        </h3>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="medical_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Relevant Medical History
                </label>
                <textarea id="medical_history" 
                          name="medical_history" 
                          rows="4"
                          class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                          placeholder="Any relevant medical history, allergies, or medications">{{ old('medical_history') }}</textarea>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Additional Notes
                </label>
                <textarea id="notes" 
                          name="notes" 
                          rows="4"
                          class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                          placeholder="Any additional information you'd like to share">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Insurance Information (Optional) --}}
    <div class="mt-8 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center mb-4">
            <i class="fas fa-shield-alt mr-3 text-green-600 dark:text-green-400"></i>
            Insurance Information (Optional)
        </h3>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="insurance_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Insurance Provider
                </label>
                <input type="text" 
                       id="insurance_provider" 
                       name="insurance_provider"
                       value="{{ old('insurance_provider') }}"
                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                       placeholder="Insurance company name">
            </div>

            <div>
                <label for="insurance_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Insurance ID
                </label>
                <input type="text" 
                       id="insurance_id" 
                       name="insurance_id"
                       value="{{ old('insurance_id') }}"
                       class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                       placeholder="Insurance member ID">
            </div>
        </div>
    </div>
</div>
