@props(['currentStatus' => null])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('patient.lab-tests.index') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ !$currentStatus ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-list mr-2"></i>
                All Tests
            </a>
            <a href="{{ route('patient.lab-tests.index', ['status' => 'pending']) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentStatus === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-clock mr-2"></i>
                Pending
            </a>
            <a href="{{ route('patient.lab-tests.index', ['status' => 'in_progress']) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentStatus === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-spinner mr-2"></i>
                In Progress
            </a>
            <a href="{{ route('patient.lab-tests.index', ['status' => 'completed']) }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 {{ $currentStatus === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                <i class="fas fa-check-circle mr-2"></i>
                Completed
            </a>
        </div>

        <!-- Search and Sort -->
        <div class="flex items-center space-x-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       placeholder="Search tests..." 
                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 dark:text-gray-500"></i>
                </div>
            </div>

            <!-- Sort Dropdown -->
            <div class="relative">
                <button type="button" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-sort mr-2"></i>
                    Sort by Date
                    <i class="fas fa-chevron-down ml-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
