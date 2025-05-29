{{-- Date and Time Selection Component --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
        {{-- Selected Service Info --}}
        <div id="selected_service_info" class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white">
                        <i id="selected_service_icon" class="fas fa-stethoscope"></i>
                    </div>
                    <div class="ml-4">
                        <h3 id="selected_service_name" class="text-lg font-bold text-gray-900 dark:text-gray-100"></h3>
                        <p id="selected_service_details" class="text-sm text-gray-600 dark:text-gray-400"></p>
                    </div>
                </div>
                <div class="text-right">
                    <div id="selected_service_price" class="text-lg font-bold text-green-600 dark:text-green-400"></div>
                    <button type="button" 
                            id="change_service_btn" 
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                        <i class="fas fa-edit mr-1"></i>
                        Change Service
                    </button>
                </div>
            </div>
        </div>        {{-- Date and Time Selection --}}
        <div class="appointment-datetime-section">
            {{-- Modern Appointment Calendar --}}
            <x-appointment.custom-calendar />
        </div>

        {{-- Additional Details --}}
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Additional Information</h4>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for Visit <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" 
                              name="reason"
                              rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                     bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                     transition-all duration-300"
                              placeholder="Please describe your reason for this appointment..."
                              required></textarea>
                </div>
                
                <div>
                    <label for="symptoms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Symptoms (Optional)
                    </label>
                    <textarea id="symptoms" 
                              name="symptoms"
                              rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                     bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                     transition-all duration-300"
                              placeholder="Describe any symptoms you're experiencing..."></textarea>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Priority Level <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" 
                            name="priority"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                   transition-all duration-300"
                            required>
                        <option value="">Select Priority</option>
                        <option value="low">Low - Routine checkup</option>
                        <option value="medium">Medium - Non-urgent concern</option>
                        <option value="high">High - Concerning symptoms</option>
                        <option value="urgent">Urgent - Immediate attention needed</option>
                    </select>
                </div>
                
                <div>
                    <label for="appointment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Appointment Type <span class="text-red-500">*</span>
                    </label>
                    <select id="appointment_type" 
                            name="appointment_type"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                                   bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                   transition-all duration-300"
                            required>
                        <option value="">Select Type</option>
                        <option value="consultation">New Consultation</option>
                        <option value="follow_up">Follow-up Visit</option>
                        <option value="check_up">General Check-up</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
            </div>    </div>
</div>
