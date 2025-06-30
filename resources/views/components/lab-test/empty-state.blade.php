@props(['title' => 'No lab tests yet', 'description' => 'Your laboratory test requests from medical consultations will appear here.'])

<div class="text-center py-16">
    <div class="mx-auto h-32 w-32 text-gray-400 dark:text-gray-500 mb-6">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
            </path>
        </svg>
    </div>
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>
    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">{{ $description }}</p>
    
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('patient.appointments.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fas fa-calendar-plus mr-2"></i>
            Book Consultation
        </a>
        <a href="{{ route('patient.medical-reports.index') }}" 
           class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            <i class="fas fa-file-medical mr-2"></i>
            View Medical Reports
        </a>
    </div>
</div>
