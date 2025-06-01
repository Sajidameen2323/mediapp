{{-- Appointment Confirmation Component --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
        {{-- <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
            <i class="fas fa-check-circle mr-2 text-green-600 dark:text-green-400"></i>
            Confirm Your Appointment
        </h3> --}}

        {{-- Appointment Summary Card --}}
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl p-6 mb-6">
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Doctor & Service Info --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-user-md mr-2 text-blue-600 dark:text-blue-400"></i>
                        Doctor & Service
                    </h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div id="confirm_doctor_avatar" class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-lg font-bold mr-3">
                                <span id="confirm_doctor_initials"></span>
                            </div>
                            <div>
                                <div id="confirm_doctor_name" class="font-semibold text-gray-900 dark:text-gray-100"></div>
                                <div id="confirm_doctor_specialization" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center pl-15">
                            <div id="confirm_service_icon" class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400 mr-3">
                                <i class="fas fa-stethoscope text-sm"></i>
                            </div>
                            <div>
                                <div id="confirm_service_name" class="font-medium text-gray-900 dark:text-gray-100"></div>
                                <div id="confirm_service_duration" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Date & Time Info --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        <i class="fas fa-calendar-check mr-2 text-green-600 dark:text-green-400"></i>
                        Appointment Details
                    </h4>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day w-5 text-blue-600 dark:text-blue-400 mr-3"></i>
                            <div>
                                <div id="confirm_date" class="font-semibold text-gray-900 dark:text-gray-100"></div>
                                <div id="confirm_day" class="text-sm text-gray-600 dark:text-gray-400"></div>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-clock w-5 text-orange-600 dark:text-orange-400 mr-3"></i>
                            <div>
                                <div id="confirm_time" class="font-semibold text-gray-900 dark:text-gray-100"></div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Estimated duration: <span id="confirm_duration"></span></div>
                            </div>
                        </div>
                          <div class="flex items-center">
                            <i class="fas fa-dollar-sign w-5 text-green-600 dark:text-green-400 mr-3"></i>
                            <div>
                                <div class="flex items-baseline space-x-2">
                                    <span id="confirm_fee" class="font-semibold text-green-600 dark:text-green-400 text-lg"></span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">+</span>
                                    <span id="confirm_tax" class="text-sm font-medium text-orange-600 dark:text-orange-400"></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">(tax)</span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Consultation fee</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        {{-- Patient Information --}}
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-2xl p-6 mb-6 border border-purple-100 dark:border-gray-600">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-user text-white text-lg"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    Visit Information
                </h4>
            </div>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div class="group">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition-colors duration-200">
                            <i class="fas fa-comment-medical text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Reason for Visit</label>
                    </div>
                    <div id="confirm_reason" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-500 shadow-sm min-h-[60px] flex items-center"></div>
                </div>
                
                <div class="group">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-200 dark:group-hover:bg-red-800 transition-colors duration-200">
                            <i class="fas fa-thermometer-half text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Symptoms</label>
                    </div>
                    <div id="confirm_symptoms" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-500 shadow-sm min-h-[60px] flex items-center"></div>
                </div>
                
                <div class="group">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 dark:group-hover:bg-orange-800 transition-colors duration-200">
                            <i class="fas fa-exclamation-triangle text-orange-600 dark:text-orange-400 text-sm"></i>
                        </div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Priority Level</label>
                    </div>
                    <div id="confirm_priority" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-500 shadow-sm min-h-[60px] flex items-center">
                        <span id="confirm_priority_badge" class="px-4 py-2 rounded-full text-sm font-semibold inline-flex items-center">
                            <i class="fas fa-circle text-xs mr-2"></i>
                        </span>
                    </div>
                </div>
                
                <div class="group">
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 dark:group-hover:bg-green-800 transition-colors duration-200">
                            <i class="fas fa-calendar-check text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Appointment Type</label>
                    </div>
                    <div id="confirm_appointment_type" class="text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-600 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-500 shadow-sm min-h-[60px] flex items-center"></div>
                </div>
            </div>
            
            {{-- Additional Info Section --}}
            <div class="mt-6 pt-6 border-t border-purple-200 dark:border-gray-600">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>All information will be kept confidential</span>
                    </div>
                    <div class="flex items-center text-purple-600 dark:text-purple-400">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>HIPAA Compliant</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Terms and Conditions --}}
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-xl p-4 mb-6">
            <h5 class="font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Important Notes
            </h5>
            <ul class="text-sm text-yellow-700 dark:text-yellow-400 space-y-1">
                <li>• Please arrive 15 minutes before your scheduled appointment</li>
                <li>• Bring a valid ID and any relevant medical records</li>
                <li>• Cancellation must be done at least 24 hours in advance</li>
                <li>• Late arrivals may result in appointment rescheduling</li>
            </ul>
        </div>        
        
        {{-- Agreement Checkbox --}}
        <div class="flex items-start mb-6">
            <input type="checkbox" 
                   id="terms_agreement" 
                   name="terms_agreement"
                   class="w-5 h-5 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 mt-1"
                   required>
            <label for="terms_agreement" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                I agree to the terms and conditions, privacy policy, and understand the cancellation policy. 
                I confirm that the information provided is accurate and complete.
                <span class="text-red-500">*</span>
            </label>
        </div>

        {{-- Debug Button (Development Only) --}}
        <div class="mb-6 border-t border-gray-200 dark:border-gray-600 pt-4">
            <button type="button" 
                    id="debug_form_state" 
                    class="w-full px-4 py-2 bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                <i class="fas fa-bug mr-2"></i>
                Debug: Log Current Form State
            </button>
        </div>
    </div>
</div>
