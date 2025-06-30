<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb Section -->
        <div class="py-2 border-b border-gray-100 dark:border-gray-700">
            <nav class="flex" aria-label="Breadcrumb" id="breadcrumb-nav">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('patient.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-home mr-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <!-- Dynamic breadcrumb items will be inserted here -->
                </ol>
            </nav>
        </div>

        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('patient.dashboard') }}" class="flex-shrink-0 flex items-center text-xl font-bold text-gray-900 dark:text-white">
                    Patient Portal
                </a>
            </div>

            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                <a href="{{ route('patient.dashboard') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.dashboard') 
                               ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>

                <a href="{{ route('patient.appointments.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.appointments.*') 
                               ? 'border-green-500 text-green-600 dark:text-green-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Appointments
                </a>

                <a href="{{ route('patient.medical-reports.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.medical-reports.*') 
                               ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-file-medical mr-2"></i>
                    Medical Reports
                </a>

                <a href="{{ route('patient.prescriptions.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.prescriptions.*') 
                               ? 'border-purple-500 text-purple-600 dark:text-purple-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-pills mr-2"></i>
                    Prescriptions
                </a>

                <a href="{{ route('patient.lab-tests.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.lab-tests.*') 
                               ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-vial mr-2"></i>
                    Lab Tests
                </a>

                <a href="{{ route('patient.lab-appointments.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.lab-appointments.*') 
                               ? 'border-orange-500 text-orange-600 dark:text-orange-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-flask mr-2"></i>
                    Lab Appointments
                </a>

                <a href="{{ route('patient.health-profile.index') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200
                           {{ request()->routeIs('patient.health-profile.*') 
                               ? 'border-red-500 text-red-600 dark:text-red-400' 
                               : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-user-injured mr-2"></i>
                    Health Profile
                </a>
            </div>

            <div class="flex items-center sm:ml-6">
                <div class="relative">
                    <button onclick="toggleDropdown()" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Quick Actions</span>
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
                            <a href="{{ route('patient.lab-appointments.create') }}" 
                               class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-flask mr-3 text-orange-500"></i>
                                Book Lab Test
                            </a>
                            <a href="{{ route('patient.appointments.search-doctors') }}" 
                               class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-search mr-3 text-purple-500"></i>
                                Find Doctors
                            </a>
                        </div>
                    </div>
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" onclick="toggleMobileMenu()" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" 
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" id="mobile-menu-icon-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" id="mobile-menu-icon-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('patient.dashboard') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.dashboard') 
                           ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Dashboard
            </a>

            <a href="{{ route('patient.appointments.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.appointments.*') 
                           ? 'bg-green-50 dark:bg-green-900 text-green-700 dark:text-green-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-calendar-check mr-2"></i>
                Appointments
            </a>

            <a href="{{ route('patient.medical-reports.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.medical-reports.*') 
                           ? 'bg-blue-50 dark:bg-blue-900 text-blue-700 dark:text-blue-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-file-medical mr-2"></i>
                Medical Reports
            </a>

            <a href="{{ route('patient.prescriptions.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.prescriptions.*') 
                           ? 'bg-purple-50 dark:bg-purple-900 text-purple-700 dark:text-purple-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-pills mr-2"></i>
                Prescriptions
            </a>

            <a href="{{ route('patient.lab-tests.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.lab-tests.*') 
                           ? 'bg-yellow-50 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-vial mr-2"></i>
                Lab Tests
            </a>

            <a href="{{ route('patient.lab-appointments.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.lab-appointments.*') 
                           ? 'bg-orange-50 dark:bg-orange-900 text-orange-700 dark:text-orange-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-flask mr-2"></i>
                Lab Appointments
            </a>

            <a href="{{ route('patient.health-profile.index') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200
                       {{ request()->routeIs('patient.health-profile.*') 
                           ? 'bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-200' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                <i class="fas fa-user-injured mr-2"></i>
                Health Profile
            </a>
        </div>
    </div>
</nav>

<script>
    // Breadcrumb management
    const breadcrumbConfig = {
        'patient.dashboard': { name: 'Dashboard', icon: 'fas fa-home' },
        'patient.appointments.index': { name: 'Appointments', icon: 'fas fa-calendar-check' },
        'patient.appointments.create': { name: 'Book Appointment', icon: 'fas fa-calendar-plus', parent: 'patient.appointments.index' },
        'patient.appointments.show': { name: 'Appointment Details', icon: 'fas fa-calendar-alt', parent: 'patient.appointments.index' },
        'patient.appointments.edit': { name: 'Edit Appointment', icon: 'fas fa-calendar-edit', parent: 'patient.appointments.index' },
        'patient.appointments.search-doctors': { name: 'Find Doctors', icon: 'fas fa-search', parent: 'patient.appointments.index' },
        'patient.medical-reports.index': { name: 'Medical Reports', icon: 'fas fa-file-medical' },
        'patient.medical-reports.show': { name: 'Report Details', icon: 'fas fa-file-alt', parent: 'patient.medical-reports.index' },
        'patient.prescriptions.index': { name: 'Prescriptions', icon: 'fas fa-pills' },
        'patient.prescriptions.show': { name: 'Prescription Details', icon: 'fas fa-prescription', parent: 'patient.prescriptions.index' },
        'patient.lab-tests.index': { name: 'Lab Tests', icon: 'fas fa-vial' },
        'patient.lab-tests.show': { name: 'Test Results', icon: 'fas fa-chart-line', parent: 'patient.lab-tests.index' },
        'patient.lab-appointments.index': { name: 'Lab Appointments', icon: 'fas fa-flask' },
        'patient.lab-appointments.create': { name: 'Book Lab Appointment', icon: 'fas fa-plus-circle', parent: 'patient.lab-appointments.index' },
        'patient.lab-appointments.show': { name: 'Appointment Details', icon: 'fas fa-info-circle', parent: 'patient.lab-appointments.index' },
        'patient.health-profile.index': { name: 'Health Profile', icon: 'fas fa-user-injured' },
        'patient.health-profile.create': { name: 'Create Profile', icon: 'fas fa-user-plus', parent: 'patient.health-profile.index' },
        'patient.health-profile.edit': { name: 'Edit Profile', icon: 'fas fa-user-edit', parent: 'patient.health-profile.index' }
    };

    function updateBreadcrumb() {
        const currentRoute = '{{ Route::currentRouteName() }}';
        const breadcrumbNav = document.getElementById('breadcrumb-nav').querySelector('ol');
        
        // Clear existing breadcrumb items except the home link
        const homeItem = breadcrumbNav.querySelector('li');
        breadcrumbNav.innerHTML = homeItem.outerHTML;
        
        if (currentRoute === 'patient.dashboard') {
            return; // Don't show breadcrumb for dashboard
        }
        
        const breadcrumbPath = buildBreadcrumbPath(currentRoute);
        
        breadcrumbPath.forEach((routeName, index) => {
            const config = breadcrumbConfig[routeName];
            if (config && routeName !== 'patient.dashboard') {
                const li = document.createElement('li');
                li.className = 'flex items-center';
                
                const isLast = index === breadcrumbPath.length - 1;
                
                li.innerHTML = `
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    ${isLast ? 
                        `<span class="text-gray-900 dark:text-white font-medium">
                            <i class="${config.icon} mr-1"></i>
                            ${config.name}
                        </span>` :
                        `<a href="${getRouteUrl(routeName)}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="${config.icon} mr-1"></i>
                            ${config.name}
                        </a>`
                    }
                `;
                
                breadcrumbNav.appendChild(li);
            }
        });
    }
    
    function buildBreadcrumbPath(currentRoute) {
        const path = [];
        let route = currentRoute;
        
        while (route && breadcrumbConfig[route]) {
            path.unshift(route);
            route = breadcrumbConfig[route].parent;
        }
        
        return path;
    }
    
    function getRouteUrl(routeName) {
        // Map route names to URLs - you might need to adjust these based on your actual routes
        const routeMap = {
            'patient.dashboard': '{{ route("patient.dashboard") }}',
            'patient.appointments.index': '{{ route("patient.appointments.index") }}',
            'patient.medical-reports.index': '{{ route("patient.medical-reports.index") }}',
            'patient.prescriptions.index': '{{ route("patient.prescriptions.index") }}',
            'patient.lab-tests.index': '{{ route("patient.lab-tests.index") }}',
            'patient.lab-appointments.index': '{{ route("patient.lab-appointments.index") }}',
            'patient.health-profile.index': '{{ route("patient.health-profile.index") }}'
        };
        
        return routeMap[routeName] || '#';
    }

    function toggleDropdown() {
        const dropdown = document.getElementById('quickActionsDropdown');
        dropdown.classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const iconClosed = document.getElementById('mobile-menu-icon-closed');
        const iconOpen = document.getElementById('mobile-menu-icon-open');

        mobileMenu.classList.toggle('hidden');
        iconClosed.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
    }

    // Initialize breadcrumb on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateBreadcrumb();
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        // Quick Actions Dropdown
        const quickActionsDropdown = document.getElementById('quickActionsDropdown');
        const quickActionsButton = event.target.closest('button[onclick="toggleDropdown()"]');
        
        if (!quickActionsButton && !quickActionsDropdown.contains(event.target)) {
            quickActionsDropdown.classList.add('hidden');
        }

        // Mobile Menu (only close if not clicking the toggle button)
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuButton = event.target.closest('button[onclick="toggleMobileMenu()"]');

        if (!mobileMenuButton && !mobileMenu.contains(event.target) && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            document.getElementById('mobile-menu-icon-closed').classList.remove('hidden');
            document.getElementById('mobile-menu-icon-open').classList.add('hidden');
        }
    });
</script>
