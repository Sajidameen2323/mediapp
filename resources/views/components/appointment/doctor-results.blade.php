{{-- Doctor Results Grid Component --}}
@props(['doctors' => collect()])

<div id="doctor_results" class="grid gap-4 md:gap-6">
    {{-- Loading State --}}
    <div id="doctors_loading" class="hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Searching for doctors...</span>
            </div>
        </div>
    </div>

    {{-- No Results State --}}
    <div id="no_doctors_found" class="hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 text-center">
            <div class="text-gray-400 dark:text-gray-500 mb-4">
                <i class="fas fa-user-md text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">No Doctors Found</h3>
            <p class="text-gray-500 dark:text-gray-400">Try adjusting your search criteria or filters.</p>
        </div>
    </div>

    {{-- Doctor Cards Container --}}
    <div id="doctors_grid" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {{-- Doctor cards will be dynamically inserted here --}}
    </div>
</div>

{{-- Doctor Card Template (Hidden) --}}
<template id="doctor_card_template">
    <div class="doctor-card bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 cursor-pointer group">
        <div class="p-6">
            {{-- Doctor Avatar --}}
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 bg-gray-800 dark:bg-gray-700 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg">
                    <span class="doctor-initials"></span>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="doctor-name text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"></h3>
                    <p class="doctor-specialization text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
                <div class="text-right">
                    <div class="doctor-rating flex items-center text-yellow-400 text-sm">
                        <i class="fas fa-star"></i>
                        <span class="ml-1 rating-value">4.8</span>
                    </div>
                    <div class="doctor-reviews text-xs text-gray-500 dark:text-gray-400">
                        (<span class="reviews-count">24</span> reviews)
                    </div>
                </div>
            </div>

            {{-- Doctor Info --}}
            <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-graduation-cap w-4 text-blue-600 dark:text-blue-400"></i>
                    <span class="doctor-experience ml-2"></span>
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-dollar-sign w-4 text-green-600 dark:text-green-400"></i>
                    <span class="doctor-fee ml-2"></span>
                </div>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-clock w-4 text-orange-600 dark:text-orange-400"></i>
                    <span class="doctor-availability ml-2"></span>
                </div>
            </div>

            {{-- Services Tags --}}
            <div class="doctor-services mb-4">
                <div class="flex flex-wrap gap-1">
                    {{-- Service tags will be inserted here --}}
                </div>
            </div>

            {{-- Action Button --}}
            <button class="select-doctor-btn w-full bg-gray-800 dark:bg-gray-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 transform group-hover:scale-105">
                <i class="fas fa-calendar-plus mr-2"></i>
                Select Doctor
            </button>
        </div>
    </div>
</template>
