<!-- Calendar Header -->
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <button type="button" 
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md" 
                    id="prevMonth">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white" id="currentMonth"></h2>
            <button type="button" 
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md" 
                    id="nextMonth">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <button type="button" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors" 
                id="todayBtn">
            Today
        </button>
    </div>

    <!-- Legend -->
    @include('doctor.appointments.partials.calendar-legend')
</div>
