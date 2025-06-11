<!-- Find Doctors Section -->
<section id="doctors" class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Find the Right Doctor
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400">
                Search by symptoms, specialization, or doctor name to find the perfect healthcare professional
            </p>
        </div>        <!-- Enhanced Search Bar -->
        <div class="max-w-6xl mx-auto mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
                <form id="doctorSearchForm">
                    <!-- Search Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-1"></i>
                                Search by Symptoms, Specialization, or Doctor Name
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="doctorSearchInput" 
                                    name="q" 
                                    placeholder="e.g., 'headache', 'cardiology', or 'Dr. Smith'" 
                                    class="w-full p-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-stethoscope text-gray-400"></i>
                                </div>
                                <button type="button" id="clearSearchBtn" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hidden">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-block mr-4">💡 Try: "chest pain", "skin problems", "anxiety"</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-filter mr-1"></i>
                                Filter by Specialty
                            </label>
                            <select id="specialtyFilter" name="specialization" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                                <option value="">All Specialties</option>
                                <option value="Cardiology">Cardiology</option>
                                <option value="Dermatology">Dermatology</option>
                                <option value="Gastroenterology">Gastroenterology</option>
                                <option value="Neurology">Neurology</option>
                                <option value="Orthopedics">Orthopedics</option>
                                <option value="Pediatrics">Pediatrics</option>
                                <option value="Psychiatry">Psychiatry</option>
                                <option value="Pulmonology">Pulmonology</option>
                                <option value="Urology">Urology</option>
                                <option value="Gynecology">Gynecology</option>
                                <option value="Ophthalmology">Ophthalmology</option>
                                <option value="ENT">ENT</option>
                                <option value="General Practice">General Practice</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-6 flex gap-3">
                        <button type="button" id="searchDoctorsBtn" class="flex-1 bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-search mr-2"></i>
                            Find Doctors
                        </button>
                        <button type="button" id="resetFiltersBtn" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Feedback -->
        <div id="searchFeedback" class="max-w-6xl mx-auto mb-6 hidden">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-blue-600 mr-3 mt-1"></i>
                    <div>
                        <p class="text-sm text-blue-800 dark:text-blue-200" id="searchFeedbackText"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Container -->
        <div id="searchResultsContainer" class="max-w-6xl mx-auto">
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="text-center py-12 hidden">
                <div class="inline-flex items-center gap-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="text-gray-600 dark:text-gray-400 text-lg">Finding the best doctors for you...</span>
                </div>
            </div>

            <!-- Search results -->
            <div id="doctorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Dynamic doctor cards will be inserted here -->
            </div>

            <!-- No results message -->
            <div id="noResultsMessage" class="text-center py-16 hidden">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-user-md text-6xl text-gray-300 dark:text-gray-600 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">No doctors found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Try searching with different symptoms or specializations, or browse our general practitioners.
                    </p>
                    <div class="space-y-3">
                        <button id="showGeneralDoctorsBtn" class="block w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-md mr-2"></i>
                            Show General Practitioners
                        </button>
                        <button id="resetSearchBtn" class="block w-full border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-redo mr-2"></i>
                            Reset Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Error message -->
            <div id="errorMessage" class="text-center py-16 hidden">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-300 dark:text-red-600 mb-6"></i>
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">Something went wrong</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6" id="errorText">Unable to load doctors. Please try again.</p>
                    <button id="retrySearchBtn" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-retry mr-2"></i>
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
