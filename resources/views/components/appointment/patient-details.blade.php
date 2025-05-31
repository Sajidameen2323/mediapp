{{-- Patient Details Component - Appointment Booking Form --}}
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

    {{-- Appointment Details Form --}}
    <div class="space-y-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
            <i class="fas fa-clipboard-list mr-3 text-green-600 dark:text-green-400"></i>
            Appointment Information
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Required Fields --}}
            <div class="space-y-4">
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Visit <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" 
                              name="reason" 
                              rows="3"
                              maxlength="500"
                              class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Please describe your reason for this appointment... (max 500 characters)"
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Priority Level <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" 
                            name="priority"
                            class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required>
                        <option value="" {{ old('priority') === '' ? 'selected' : '' }}>Select Priority</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low - Routine checkup</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium - Non-urgent concern</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High - Concerning symptoms</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent - Immediate attention needed</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Appointment Type <span class="text-red-500">*</span>
                    </label>
                    <select id="appointment_type" 
                            name="appointment_type"
                            class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required>
                        <option value="" {{ old('appointment_type') === '' ? 'selected' : '' }}>Select Type</option>
                        <option value="consultation" {{ old('appointment_type') === 'consultation' ? 'selected' : '' }}>New Consultation</option>
                        <option value="follow_up" {{ old('appointment_type') === 'follow_up' ? 'selected' : '' }}>Follow-up Visit</option>
                        <option value="check_up" {{ old('appointment_type') === 'check_up' ? 'selected' : '' }}>General Check-up</option>
                        <option value="emergency" {{ old('appointment_type') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                    @error('appointment_type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Optional Fields --}}
            <div class="space-y-4">
                <div>
                    <label for="symptoms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Symptoms <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea id="symptoms" 
                              name="symptoms" 
                              rows="3"
                              maxlength="1000"
                              class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Describe any symptoms you're experiencing... (max 1000 characters)">{{ old('symptoms') }}</textarea>
                    @error('symptoms')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Notes <span class="text-gray-400">(Optional)</span>
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              maxlength="500"
                              class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                              placeholder="Any additional information you'd like to share... (max 500 characters)">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
