<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediCare - Healthcare Management Platform')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Dark mode transition */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Enhanced navigation with dropdown functionality */
        .dropdown-container {
            position: relative;
        }
        
        .dropdown-menu {
            transform-origin: top;
        }
        
        .dropdown-arrow {
            transition: transform 0.2s ease;
        }
        
        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Minimal navigation hover effects */
        .nav-item {
            position: relative;
            transition: all 0.15s ease;
        }
        
        .nav-item:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
        
        .dark .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .nav-item.active {
            background-color: rgba(249, 115, 22, 0.08);
            color: #ea580c;
        }
        
        .dark .nav-item.active {
            background-color: rgba(249, 115, 22, 0.12);
            color: #fb923c;
        }
        
        /* Minimal dropdown hover effects */
        .dropdown-item {
            transition: all 0.15s ease;
        }
        
        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }
        
        .dark .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.06);
        }
        
        /* Minimal mobile navigation */
        .mobile-nav-item {
            transition: all 0.15s ease;
        }
        
        .mobile-nav-item:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .dark .mobile-nav-item:hover {
            background-color: rgba(255, 255, 255, 0.04);
        }
    </style>
        
    @stack('head')
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen antialiased">
    <!-- Admin Navigation -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="w-7 h-7 bg-orange-600 rounded-lg flex items-center justify-center group-hover:bg-orange-700 transition-colors">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white hidden sm:block">MediCare Admin</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-item flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.dashboard') 
                           ? 'active' 
                           : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-500' }} mr-2 text-sm"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Appointments Dropdown -->
                    <div class="relative dropdown-container" style="z-index: 45;" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.away="open = false"
                                class="nav-item dropdown-toggle flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.appointments.*', 'admin.appointment-config.*') 
                                    ? 'active' 
                                    : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <i class="fas fa-calendar-check {{ request()->routeIs('admin.appointments.*', 'admin.appointment-config.*') ? 'text-green-600' : 'text-gray-500' }} mr-2 text-sm"></i>
                            <span>Appointments</span>
                            <i class="fas fa-chevron-down ml-2 text-xs transition-transform dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="dropdown-menu absolute left-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1.5">
                                <a href="{{ route('admin.appointments.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.appointments.index') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-green-100 dark:bg-green-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-list text-green-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">All Appointments</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Manage appointments</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.appointments.export') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md">
                                    <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-download text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Export Data</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Download reports</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.appointment-config.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.appointment-config.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-purple-100 dark:bg-purple-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-cog text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Configuration</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Appointment settings</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Management Dropdown -->
                    <div class="relative dropdown-container" style="z-index: 45;" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.away="open = false"
                                class="nav-item dropdown-toggle flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.doctors.*', 'admin.services.*', 'admin.holidays.*') 
                                    ? 'active' 
                                    : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <i class="fas fa-users-cog {{ request()->routeIs('admin.doctors.*', 'admin.services.*', 'admin.holidays.*') ? 'text-blue-600' : 'text-gray-500' }} mr-2 text-sm"></i>
                            <span>Staff</span>
                            <i class="fas fa-chevron-down ml-2 text-xs transition-transform dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="dropdown-menu absolute left-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1.5">
                                <a href="{{ route('admin.doctors.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.doctors.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-blue-100 dark:bg-blue-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-stethoscope text-blue-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Doctors</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Manage doctors</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.services.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.services.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-indigo-100 dark:bg-indigo-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-notes-medical text-indigo-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Services</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Medical services</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.holidays.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.holidays.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-yellow-100 dark:bg-yellow-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar-times text-yellow-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Holiday Requests</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Staff holidays</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Facilities Dropdown -->
                    <div class="relative dropdown-container" style="z-index: 45;" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.away="open = false"
                                class="nav-item dropdown-toggle flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.laboratories.*', 'admin.pharmacies.*') 
                                    ? 'active' 
                                    : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                            <i class="fas fa-building {{ request()->routeIs('admin.laboratories.*', 'admin.pharmacies.*') ? 'text-purple-600' : 'text-gray-500' }} mr-2 text-sm"></i>
                            <span>Facilities</span>
                            <i class="fas fa-chevron-down ml-2 text-xs transition-transform dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="dropdown-menu absolute left-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1.5">
                                <a href="{{ route('admin.laboratories.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.laboratories.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-purple-100 dark:bg-purple-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-flask text-purple-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Laboratories</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Lab management</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.pharmacies.index') }}" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('admin.pharmacies.*') ? 'bg-gray-100 dark:bg-gray-700 font-medium' : '' }}">
                                    <div class="w-7 h-7 bg-teal-100 dark:bg-teal-900/30 rounded-md flex items-center justify-center mr-3">
                                        <i class="fas fa-pills text-teal-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">Pharmacies</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Pharmacy management</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reports & Analytics -->
                    <a href="{{ route('admin.reports.index') }}" 
                       class="nav-item flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.reports.*') 
                           ? 'active' 
                           : 'text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-chart-line {{ request()->routeIs('admin.reports.*') ? 'text-indigo-600' : 'text-gray-500' }} mr-2 text-sm"></i>
                        <span>Reports</span>
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-2">
                    <!-- User Menu -->
                    <div class="relative dropdown-container" style="z-index: 45;" x-data="{ open: false }">
                        <button @click="open = !open" 
                                @click.away="open = false"
                                class="dropdown-toggle flex items-center space-x-2 p-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white rounded-md transition-all">
                            <div class="w-7 h-7 bg-orange-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform dropdown-arrow" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="dropdown-menu absolute right-0 mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="py-1.5">
                                <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">System Administrator</div>
                                </div>
                                <a href="#" 
                                   class="dropdown-item flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md">
                                    <i class="fas fa-cog mr-3 text-gray-400 text-xs"></i>Settings
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="logout-button dropdown-item flex items-center w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md">
                                        <i class="fas fa-sign-out-alt mr-3 text-gray-400 text-xs"></i>Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" 
                            class="md:hidden p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 rounded-md transition-all">
                        <i class="fas fa-bars text-lg" id="mobile-menu-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="px-4 py-3 space-y-2">
                    <!-- Main Navigation -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="mobile-nav-item flex items-center px-3 py-2.5 rounded-md text-base font-medium {{ request()->routeIs('admin.dashboard') 
                           ? 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-300' 
                           : 'text-gray-700 dark:text-gray-300' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-orange-600 text-sm"></i>Dashboard
                    </a>

                    <!-- Appointments Section -->
                    <div class="pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Appointments</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.appointments.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.appointments.*') 
                                   ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-list mr-3 text-green-600"></i>All Appointments
                            </a>
                            <a href="{{ route('admin.appointment-config.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.appointment-config.*') 
                                   ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-cog mr-3 text-purple-600"></i>Configuration
                            </a>
                        </div>
                    </div>

                    <!-- Staff Management Section -->
                    <div class="pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Staff Management</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.doctors.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.doctors.*') 
                                   ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-stethoscope mr-3 text-blue-600"></i>Doctors
                            </a>
                            <a href="{{ route('admin.services.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.services.*') 
                                   ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-medical-bag mr-3 text-indigo-600"></i>Services
                            </a>
                            <a href="{{ route('admin.holidays.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.holidays.*') 
                                   ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-calendar-times mr-3 text-yellow-600"></i>Holiday Requests
                            </a>
                        </div>
                    </div>

                    <!-- Facilities Section -->
                    <div class="pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Facilities</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.laboratories.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.laboratories.*') 
                                   ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-flask mr-3 text-purple-600"></i>Laboratories
                            </a>
                            <a href="{{ route('admin.pharmacies.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.pharmacies.*') 
                                   ? 'bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-pills mr-3 text-teal-600"></i>Pharmacies
                            </a>
                        </div>
                    </div>

                    <!-- Reports Section -->
                    <div class="pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Analytics</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.reports.index') }}" 
                               class="mobile-nav-item flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors {{ request()->routeIs('admin.reports.*') 
                                   ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' 
                                   : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800' }}">
                                <i class="fas fa-chart-line mr-3 text-indigo-600"></i>Reports & Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
      <!-- Main Content Area with proper spacing -->
    <main class="min-h-screen">
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <i class="fas fa-check-circle mr-3 text-green-600 dark:text-green-400"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl flex items-center shadow-sm">
                    <i class="fas fa-exclamation-circle mr-3 text-red-600 dark:text-red-400"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    
    @stack('scripts')

    <script>
        // Mobile menu toggle with Alpine.js support
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                    
                    // Toggle icon
                    const icon = mobileMenuButton.querySelector('i');
                    if (mobileMenu.classList.contains('hidden')) {
                        icon.className = 'fas fa-bars text-lg';
                    } else {
                        icon.className = 'fas fa-times text-lg';
                    }
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                        const icon = mobileMenuButton.querySelector('i');
                        icon.className = 'fas fa-bars text-lg';
                    }
                });
            }
        });

        // Add smooth hover effects for navigation items
        document.addEventListener('DOMContentLoaded', function() {
            // Minimal hover effects - no transform animations for cleaner look
            const navItems = document.querySelectorAll('.nav-item, .dropdown-item, .mobile-nav-item');
            
            navItems.forEach(item => {
                // Remove the transform animations for minimal design
                item.addEventListener('mouseenter', function() {
                    // Just rely on CSS hover states for minimal effect
                });
                
                item.addEventListener('mouseleave', function() {
                    // Just rely on CSS hover states for minimal effect
                });
            });
        });
    </script>
</body>

</html>
