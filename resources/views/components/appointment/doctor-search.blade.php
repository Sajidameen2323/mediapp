{{-- Doctor Search and Filter Component --}}
@props(['services' => collect()])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
    <div class="flex flex-col lg:flex-row gap-4">
        {{-- Intelligent Search Input --}}
        <div class="flex-3 lg:flex-2">
            <label for="doctor_search" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-brain mr-2 text-blue-600 dark:text-blue-400"></i>
                Search by Symptoms, Specialization
            </label>
            <div class="relative">
                <input type="text" 
                       id="doctor_search" 
                       class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 dark:border-gray-600 rounded-xl 
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              transition-all duration-300"
                       placeholder="e.g., 'headache', 'cardiology'"
                       autocomplete="off">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-stethoscope text-gray-400"></i>
                </div>
                <button type="button" 
                        id="clear_doctor_search" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                <span class="inline-block mr-4">💡 Try: "chest pain", "skin problems", "anxiety"</span>
                <span class="inline-block">🔍 Smart search with typo tolerance</span>
            </div>
        </div>

        {{-- Filter by Service --}}
        {{-- <div class="flex-1">
            <label for="service_filter" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-filter mr-2 text-blue-600 dark:text-blue-400"></i>
                Filter by Service
            </label>
            <select id="service_filter" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           transition-all duration-300">
                <option value="">All Services</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div> --}}

        {{-- Enhanced Specialization Filter --}}
        <div class="flex-1">
            <label for="specialization_filter" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-stethoscope mr-2 text-blue-600 dark:text-blue-400"></i>
                Filter by Specialty
            </label>
            <select id="specialization_filter" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           transition-all duration-300">
                <option value="">All Specialties</option>
                <option value="Cardiology">Cardiology</option>
                <option value="Dermatology">Dermatology</option>
                <option value="Endocrinology">Endocrinology</option>
                <option value="Gastroenterology">Gastroenterology</option>
                <option value="General Practice">General Practice</option>
                <option value="Gynecology">Gynecology</option>
                <option value="Neurology">Neurology</option>
                <option value="Ophthalmology">Ophthalmology</option>
                <option value="Orthopedics">Orthopedics</option>
                <option value="Pediatrics">Pediatrics</option>
                <option value="Psychiatry">Psychiatry</option>
                <option value="Pulmonology">Pulmonology</option>
                <option value="Urology">Urology</option>
                <option value="ENT">ENT (Ear, Nose & Throat)</option>
            </select>
        </div>


    </div>

    {{-- Search Feedback Section --}}
    <div id="search_feedback" class="mt-4 hidden">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-blue-600 dark:text-blue-400 mr-2 mt-1"></i>
                <div>
                    <p class="text-sm text-blue-800 dark:text-blue-200" id="search_feedback_text"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Filters Display --}}
    <div id="active_filters" class="mt-4 flex flex-wrap gap-2" style="display: none;">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active filters:</span>
    </div>
</div>
