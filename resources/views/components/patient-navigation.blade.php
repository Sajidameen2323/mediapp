{{-- Patient Navigation Component --}}
<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex space-x-8">
                <!-- Dashboard -->
                <a href="{{ route('patient.dashboard') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.dashboard') 
                             ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>

                <!-- Appointments -->
                <a href="{{ route('patient.appointments.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.appointments.*') 
                             ? 'border-green-500 text-green-600 dark:text-green-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Appointments
                </a>

                <!-- Medical Reports -->
                <a href="{{ route('patient.medical-reports.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.medical-reports.*') 
                             ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-file-medical mr-2"></i>
                    Medical Reports
                </a>

                <!-- Prescriptions -->
                <a href="{{ route('patient.prescriptions.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.prescriptions.*') 
                             ? 'border-purple-500 text-purple-600 dark:text-purple-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-pills mr-2"></i>
                    Prescriptions
                </a>

                <!-- Lab Tests -->
                <a href="{{ route('patient.lab-tests.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.lab-tests.*') 
                             ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-vial mr-2"></i>
                    Lab Tests
                </a>

                <!-- Health Profile -->
                <a href="{{ route('patient.health-profile.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                          {{ request()->routeIs('patient.health-profile.*') 
                             ? 'border-red-500 text-red-600 dark:text-red-400' 
                             : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-user-injured mr-2"></i>
                    Health Profile
                </a>
            </div>            <!-- Quick Actions Dropdown -->
            <div class="flex items-center">
                <div class="relative">
                    <button onclick="toggleDropdown()" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        Quick Actions
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>

                    <div id="quickActionsDropdown" 
                         class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50">
                        <div class="py-1">
                            <a href="{{ route('patient.appointments.create') }}" 
                               class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-calendar-plus mr-3 text-blue-500"></i>
                                Book Appointment
                            </a>
                            @if(!auth()->user()->healthProfile)
                                <a href="{{ route('patient.health-profile.create') }}" 
                                   class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-user-injured mr-3 text-red-500"></i>
                                    Create Health Profile
                                </a>
                            @endif
                            <a href="{{ route('patient.appointments.search-doctors') }}" 
                               class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-search mr-3 text-purple-500"></i>
                                Find Doctors
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>    </div>
</nav>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('quickActionsDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('quickActionsDropdown');
    const button = event.target.closest('button');
    
    if (!button || button.getAttribute('onclick') !== 'toggleDropdown()') {
        dropdown.classList.add('hidden');
    }
});
</script>
