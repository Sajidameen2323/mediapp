<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <!-- Logo/Brand -->
            <div class="flex items-center">
                <a href="{{ route('patient.dashboard') }}" class="flex items-center space-x-2 group">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition-colors">
                        <i class="fas fa-heartbeat text-white text-sm"></i>
                    </div>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white hidden sm:block">MediApp</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Dashboard -->
                <a href="{{ route('patient.dashboard') }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.dashboard') 
                       ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' 
                       : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-home {{ request()->routeIs('patient.dashboard') ? 'text-blue-600' : 'text-gray-400' }} mr-2"></i>
                    <span>Home</span>
                </a>

                <!-- Appointments -->
                <a href="{{ route('patient.appointments.index') }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('patient.appointments.*') 
                       ? 'bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300' 
                       : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                    <i class="fas fa-calendar-check {{ request()->routeIs('patient.appointments.*') ? 'text-green-600' : 'text-gray-400' }} mr-2"></i>
                    <span>Appointments</span>
                </a>

                <!-- Health Services Dropdown -->
                <div class="relative dropdown-container">
                    <button class="dropdown-toggle flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs(['patient.prescriptions.*', 'patient.pharmacy-orders.*', 'patient.medical-reports.*']) 
                                ? 'bg-purple-50 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' 
                                : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <i class="fas fa-heartbeat {{ request()->routeIs(['patient.prescriptions.*', 'patient.pharmacy-orders.*', 'patient.medical-reports.*']) ? 'text-purple-600' : 'text-gray-400' }} mr-2"></i>
                        <span>Health</span>
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 opacity-0 invisible transform scale-95 transition-all duration-200">
                        <div class="py-2">
                            <a href="{{ route('patient.prescriptions.index') }}" 
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ request()->routeIs('patient.prescriptions.*') ? 'bg-purple-50 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' : '' }}">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-pills text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Prescriptions</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">View your medications</div>
                                </div>
                            </a>
                            <a href="{{ route('patient.pharmacy-orders.index') }}" 
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ request()->routeIs('patient.pharmacy-orders.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : '' }}">
                                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-shopping-cart text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Pharmacy Orders</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Track your orders</div>
                                </div>
                            </a>
                            <a href="{{ route('patient.medical-reports.index') }}" 
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ request()->routeIs('patient.medical-reports.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : '' }}">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-medical text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Medical Reports</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Access your reports</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Lab Services Dropdown -->
                <div class="relative dropdown-container">
                    <button class="dropdown-toggle flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs(['patient.lab-tests.*', 'patient.lab-appointments.*']) 
                                ? 'bg-yellow-50 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' 
                                : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <i class="fas fa-vial {{ request()->routeIs(['patient.lab-tests.*', 'patient.lab-appointments.*']) ? 'text-yellow-600' : 'text-gray-400' }} mr-2"></i>
                        <span>Lab</span>
                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 opacity-0 invisible transform scale-95 transition-all duration-200">
                        <div class="py-2">
                            <a href="{{ route('patient.lab-tests.index') }}" 
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ request()->routeIs('patient.lab-tests.*') ? 'bg-yellow-50 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' : '' }}">
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-vial text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Test Results</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">View lab results</div>
                                </div>
                            </a>
                            <a href="{{ route('patient.lab-appointments.index') }}" 
                               class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ request()->routeIs('patient.lab-appointments.*') ? 'bg-orange-50 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300' : '' }}">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-medium">Lab Appointments</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Schedule tests</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center space-x-2">
                <!-- Notifications -->
                <button class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-all relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Menu -->
                <div class="relative dropdown-container">
                    <button class="dropdown-toggle flex items-center space-x-2 p-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-all">
                        <div class="w-7 h-7 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 dark:text-gray-300 text-sm"></i>
                        </div>
                        <span class="hidden lg:block text-sm font-medium">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform dropdown-arrow"></i>
                    </button>
                    <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 opacity-0 invisible transform scale-95 transition-all duration-200">
                        <div class="py-2">
                            <a href="{{ route('patient.health-profile.index') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fas fa-user-circle mr-3 text-gray-400"></i>My Profile
                            </a>
                            <a href="#" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fas fa-cog mr-3 text-gray-400"></i>Settings
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3 text-gray-400"></i>Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button id="mobile-menu-button" 
                        class="md:hidden p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-all">
                    <i class="fas fa-bars text-lg" id="mobile-menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <div class="px-4 py-3 space-y-2">
                <!-- Main Navigation -->
                <a href="{{ route('patient.dashboard') }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.dashboard') 
                       ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' 
                       : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                    <i class="fas fa-home mr-3 text-blue-600"></i>Home
                </a>

                <a href="{{ route('patient.appointments.index') }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.appointments.*') 
                       ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' 
                       : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                    <i class="fas fa-calendar-check mr-3 text-green-600"></i>Appointments
                </a>

                <!-- Health Services Section -->
                <div class="pt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Health Services</div>
                    <div class="space-y-1">
                        <a href="{{ route('patient.prescriptions.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.prescriptions.*') 
                               ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                            <i class="fas fa-pills mr-3 text-purple-600"></i>Prescriptions
                        </a>
                        <a href="{{ route('patient.pharmacy-orders.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.pharmacy-orders.*') 
                               ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                            <i class="fas fa-shopping-cart mr-3 text-indigo-600"></i>Pharmacy Orders
                        </a>
                        <a href="{{ route('patient.medical-reports.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.medical-reports.*') 
                               ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                            <i class="fas fa-file-medical mr-3 text-blue-600"></i>Medical Reports
                        </a>
                    </div>
                </div>

                <!-- Lab Services Section -->
                <div class="pt-2">
                    <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Lab Services</div>
                    <div class="space-y-1">
                        <a href="{{ route('patient.lab-tests.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.lab-tests.*') 
                               ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                            <i class="fas fa-vial mr-3 text-yellow-600"></i>Test Results
                        </a>
                        <a href="{{ route('patient.lab-appointments.index') }}" 
                           class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.lab-appointments.*') 
                               ? 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                            <i class="fas fa-calendar-alt mr-3 text-orange-600"></i>Lab Appointments
                        </a>
                    </div>
                </div>

                <!-- Profile Section -->
                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('patient.health-profile.index') }}" 
                       class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('patient.health-profile.*') 
                           ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                        <i class="fas fa-user-circle mr-3 text-gray-600"></i>My Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Simplified Breadcrumb for Detail Pages -->
@if(request()->routeIs(['*.show']))
<div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('patient.dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-xs"></i>
            @if(request()->routeIs('patient.prescriptions.*'))
                <a href="{{ route('patient.prescriptions.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Prescriptions</a>
            @elseif(request()->routeIs('patient.pharmacy-orders.*'))
                <a href="{{ route('patient.pharmacy-orders.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Pharmacy Orders</a>
            @elseif(request()->routeIs('patient.medical-reports.*'))
                <a href="{{ route('patient.medical-reports.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Medical Reports</a>
            @elseif(request()->routeIs('patient.lab-tests.*'))
                <a href="{{ route('patient.lab-tests.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">Lab Tests</a>
            @endif
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-900 dark:text-white font-medium">Details</span>
        </nav>
    </div>
</div>
@endif

<script>
// Enhanced navigation with dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                mobileMenuIcon.classList.replace('fa-bars', 'fa-times');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenuIcon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = mobileMenuButton.contains(event.target) || mobileMenu.contains(event.target);
            
            if (!isClickInsideNav && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                mobileMenuIcon.classList.replace('fa-times', 'fa-bars');
            }
        });
    }

    // Desktop dropdown functionality
    const dropdownContainers = document.querySelectorAll('.dropdown-container');
    
    dropdownContainers.forEach(container => {
        const toggle = container.querySelector('.dropdown-toggle');
        const menu = container.querySelector('.dropdown-menu');
        const arrow = container.querySelector('.dropdown-arrow');
        
        if (toggle && menu && arrow) {
            let isOpen = false;
            
            const showDropdown = () => {
                // Close all other dropdowns first
                dropdownContainers.forEach(otherContainer => {
                    if (otherContainer !== container) {
                        const otherMenu = otherContainer.querySelector('.dropdown-menu');
                        const otherArrow = otherContainer.querySelector('.dropdown-arrow');
                        if (otherMenu && otherArrow) {
                            otherMenu.classList.add('opacity-0', 'invisible', 'scale-95');
                            otherMenu.classList.remove('opacity-100', 'visible', 'scale-100');
                            otherArrow.classList.remove('rotate-180');
                        }
                    }
                });
                
                // Show this dropdown
                menu.classList.remove('opacity-0', 'invisible', 'scale-95');
                menu.classList.add('opacity-100', 'visible', 'scale-100');
                arrow.classList.add('rotate-180');
                isOpen = true;
            };
            
            const hideDropdown = () => {
                menu.classList.add('opacity-0', 'invisible', 'scale-95');
                menu.classList.remove('opacity-100', 'visible', 'scale-100');
                arrow.classList.remove('rotate-180');
                isOpen = false;
            };
            
            // Click to toggle
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (isOpen) {
                    hideDropdown();
                } else {
                    showDropdown();
                }
            });
            
            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target) && isOpen) {
                    hideDropdown();
                }
            });
            
            // Close when pressing escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isOpen) {
                    hideDropdown();
                }
            });
        }
    });
});
</script>
