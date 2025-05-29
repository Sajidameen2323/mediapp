{{-- Doctor Search and Filter Component --}}
@props(['services' => collect()])

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-6">
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Search by Doctor Name --}}
        <div class="flex-1">
            <label for="doctor_search" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-search mr-2 text-blue-600 dark:text-blue-400"></i>
                Search Doctor by Name
            </label>
            <div class="relative">
                <input type="text" 
                       id="doctor_search" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-xl 
                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                              transition-all duration-300"
                       placeholder="Type doctor name or specialization..."
                       autocomplete="off">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-user-md text-gray-400"></i>
                </div>
                <button type="button" 
                        id="clear_doctor_search" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Filter by Service --}}
        <div class="flex-1">
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
        </div>

        {{-- Specialization Filter --}}
        <div class="flex-1">
            <label for="specialization_filter" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <i class="fas fa-stethoscope mr-2 text-blue-600 dark:text-blue-400"></i>
                Specialization
            </label>
            <select id="specialization_filter" 
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl 
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                           transition-all duration-300">
                <option value="">All Specializations</option>
                <option value="Cardiology">Cardiology</option>
                <option value="Dermatology">Dermatology</option>
                <option value="Endocrinology">Endocrinology</option>
                <option value="Gastroenterology">Gastroenterology</option>
                <option value="General Practice">General Practice</option>
                <option value="Neurology">Neurology</option>
                <option value="Orthopedics">Orthopedics</option>
                <option value="Pediatrics">Pediatrics</option>
                <option value="Psychiatry">Psychiatry</option>
                <option value="Urology">Urology</option>
            </select>
        </div>
    </div>

    {{-- Active Filters Display --}}
    <div id="active_filters" class="mt-4 flex flex-wrap gap-2" style="display: none;">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active filters:</span>
    </div>
</div>
