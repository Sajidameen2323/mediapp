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
                    {{-- <button type="button" 
                            id="change_service_btn" 
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                        <i class="fas fa-edit mr-1"></i>
                        Change Service
                    </button> --}}
                </div>
            </div>
        </div>        
        {{-- Date and Time Selection --}}
        <div class="appointment-datetime-section">
            {{-- Modern Appointment Calendar --}}
            <x-appointment.custom-calendar />
        </div>
</div>
