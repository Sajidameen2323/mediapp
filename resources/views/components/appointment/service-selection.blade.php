{{-- Service Selection Component --}}
@props(['selectedDoctor' => null])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
    {{-- Selected Doctor Info --}}
    <div id="selected_doctor_info"
        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-xl p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-lg font-bold">
                    <span id="selected_doctor_initials"></span>
                </div>
                <div class="ml-4">
                    <h3 id="selected_doctor_name" class="text-lg font-bold text-gray-900 dark:text-gray-100"></h3>
                    <p id="selected_doctor_specialization" class="text-sm text-gray-600 dark:text-gray-400"></p>
                </div>
            </div>
            <button type="button" id="change_doctor_btn"
                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium text-sm">
                <i class="fas fa-edit mr-1"></i>
                Change Doctor
            </button>
        </div>
    </div>

    {{-- Service Selection --}}
    <div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
            <i class="fas fa-medical-bag mr-2 text-blue-600 dark:text-blue-400"></i>
            Choose a Service
        </h3>

        <div id="services_loading" class="hidden">
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Loading services...</span>
            </div>
        </div>

        <div id="services_grid" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            {{-- Service cards will be dynamically loaded --}}
        </div>
    </div>
</div>
</div>

{{-- Service Card Template --}}
<template id="service_card_template">
    <div
        class="service-card bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl p-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 hover:shadow-lg transition-all duration-300 group">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <div
                        class="service-icon w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 mr-3">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h4
                            class="service-name font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                        </h4>
                        <p class="service-duration text-xs text-gray-500 dark:text-gray-400"></p>
                    </div>
                </div>

                <p class="service-description text-sm text-gray-600 dark:text-gray-400 mb-3"></p>

                <div class="flex items-center justify-between">
                    <span class="service-price text-lg font-bold text-green-600 dark:text-green-400"></span>
                    <div class="service-radio">
                        <input type="radio" name="service_id"
                            class="service-radio-input w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
</div>
