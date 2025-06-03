<!-- Page Header -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-1">
                <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                Appointment Calendar
            </h1>
            <p class="text-gray-600 dark:text-gray-400">View and manage your appointments in calendar format</p>
        </div>
        <a href="{{ route('doctor.appointments.index') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
            <i class="fas fa-list mr-2"></i>
            List View
        </a>
    </div>
</div>
