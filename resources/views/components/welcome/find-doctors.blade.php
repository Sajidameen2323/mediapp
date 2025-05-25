<!-- Find Doctors Section -->
<section id="doctors" class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Find the Right Doctor
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Browse our network of qualified healthcare professionals
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-6xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
                <form id="doctorSearchForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user-md mr-1"></i>
                            Doctor Name
                        </label>
                        <input type="text" id="nameFilter" name="search" placeholder="Enter doctor's name" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-stethoscope mr-1"></i>
                            Specialty
                        </label>
                        <select id="specialtyFilter" name="specialty" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                            <option value="">All Specialties</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Location
                        </label>
                        <input type="text" id="locationFilter" name="location" placeholder="Enter city or address" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-search mr-2"></i>
                            Search Doctors
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Featured Doctors -->
        <div id="searchResultsContainer">
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="text-center py-8 hidden">
                <div class="inline-flex items-center gap-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-600 dark:text-gray-400">Searching for doctors...</span>
                </div>
            </div>

            <!-- Search results -->
            <div id="doctorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Dynamic doctor cards will be inserted here -->
            </div>

            <!-- No results message -->
            <div id="noResultsMessage" class="text-center py-12 hidden">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-user-md text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No doctors found</h3>
                    <p class="text-gray-600 dark:text-gray-400">Try adjusting your search criteria or browse all available doctors.</p>
                    <button id="resetSearchBtn" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Show All Doctors
                    </button>
                </div>
            </div>

            <!-- Error message -->
            <div id="errorMessage" class="text-center py-12 hidden">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Something went wrong</h3>
                    <p class="text-gray-600 dark:text-gray-400" id="errorText">Unable to load doctors. Please try again.</p>
                    <button id="retrySearchBtn" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
