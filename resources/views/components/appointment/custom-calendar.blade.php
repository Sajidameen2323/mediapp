{{-- Custom Calendar Component for Appointment Booking --}}
<div class="custom-calendar-wrapper">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 p-6">
        <div class="calendar-header flex items-center justify-between mb-4">
            <button type="button" id="prevMonth" class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            
            <div class="calendar-title">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" id="monthYear">
                    <!-- Will be populated by JavaScript -->
                </h3>
            </div>
            
            <button type="button" id="nextMonth" class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
        </div>

        {{-- Loading State --}}
        <div id="calendar-loading" class="hidden">
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Loading calendar...</span>
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div id="calendar-grid" class="calendar-grid">
            {{-- Days of Week Header --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Sun</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Mon</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Tue</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Wed</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Thu</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Fri</div>
                <div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Sat</div>
            </div>

            {{-- Calendar Days Grid --}}
            <div id="calendar-days" class="grid grid-cols-7 gap-1">
                {{-- Will be populated by JavaScript --}}
            </div>
        </div>

        {{-- Calendar Legend --}}
        <div class="calendar-legend mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
            <div class="flex flex-wrap items-center gap-4 text-xs">
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded bg-blue-500 mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Selected</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded bg-green-500 mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded bg-red-500 mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Unavailable</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 rounded bg-gray-300 dark:bg-gray-600 mr-2"></div>
                    <span class="text-gray-600 dark:text-gray-400">Past/Blocked</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Date Info Panel --}}
    <div id="selected-date-info" class="hidden mt-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100" id="selected-date-title">
                    <!-- Selected date will be shown here -->
                </h4>
                <div class="mt-1">
                    <p class="text-sm text-blue-700 dark:text-blue-300" id="selected-date-slots">
                        <!-- Available slots info will be shown here -->
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Error State --}}
    <div id="calendar-error" class="hidden mt-4 bg-red-50 dark:bg-red-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-red-900 dark:text-red-100">
                    Error loading calendar
                </h4>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1" id="calendar-error-message">
                    Unable to load calendar data. Please try again.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-day {
    @apply w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium transition-all duration-200 cursor-pointer relative;
}

.calendar-day.disabled {
    @apply cursor-not-allowed opacity-50;
}

.calendar-day.other-month {
    @apply text-gray-400 dark:text-gray-600;
}

.calendar-day.current-month {
    @apply text-gray-900 dark:text-gray-100;
}

.calendar-day.today {
    @apply ring-2 ring-blue-500 dark:ring-blue-400;
}

.calendar-day.selected {
    @apply bg-blue-500 text-white;
}

.calendar-day.available {
    @apply bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-900/50;
}

.calendar-day.available.hover\:shadow-sm:hover {
    @apply shadow-sm;
}

.calendar-day.unavailable {
    @apply bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 cursor-not-allowed;
}

.calendar-day.past {
    @apply bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-600 cursor-not-allowed;
}

.calendar-day .slot-indicator {
    @apply absolute -top-1 -right-1 w-3 h-3 rounded-full text-xs flex items-center justify-center;
}

.calendar-day.available .slot-indicator {
    @apply bg-green-600 dark:bg-green-500 text-white;
}

.calendar-day.limited-slots .slot-indicator {
    @apply bg-yellow-600 dark:bg-yellow-500 text-white;
}
</style>
