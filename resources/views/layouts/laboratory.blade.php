<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ request()->cookie('theme') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Laboratory Dashboard') - {{ config('app.name', 'MediApp') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { 
            display: none !important; 
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
    </style>
    
    @stack('head')
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen antialiased">
    <!-- Laboratory Navigation Top Bar -->
    <nav class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-b border-gray-200/20 dark:border-gray-700/20 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Section with Laboratory Info -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('laboratory.dashboard') }}" class="flex items-center space-x-3 group transition-all duration-200 hover:scale-105">
                        <div class="bg-purple-600 p-2.5 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-200">
                            <i class="fas fa-flask text-white text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">MediCare</h1>
                            @if(auth()->user()->laboratory()->first())
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ auth()->user()->laboratory()->first()->name ?? 'Laboratory Portal' }}</span>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Center Navigation - Desktop -->
                <div class="hidden lg:flex items-center space-x-1">
                    <!-- Dashboard -->
                    <a href="{{ route('laboratory.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.dashboard') 
                                  ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-tachometer-alt mr-2 {{ request()->routeIs('laboratory.dashboard') ? 'text-purple-500 dark:text-purple-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Dashboard
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('laboratory.appointments.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 relative
                              {{ request()->routeIs('laboratory.appointments.*') 
                                  ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-calendar-check mr-2 {{ request()->routeIs('laboratory.appointments.*') ? 'text-green-500 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Appointments
                        @php
                            $pendingCount = auth()->user()->laboratory()->first()?->labAppointments()->where('status', 'pending')->count() ?? 0;
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Test Results -->
                    <a href="{{ route('laboratory.results.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.results.*') 
                                  ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-file-medical mr-2 {{ request()->routeIs('laboratory.results.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Test Results
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('laboratory.settings.index') }}" 
                       class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.settings.*') 
                                  ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-cog mr-2 {{ request()->routeIs('laboratory.settings.*') ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Settings
                    </a>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-6">
                    <!-- Availability Toggle -->
                    @if(auth()->user()->laboratory()->first())
                        <div class="hidden md:flex items-center px-4 py-2 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Laboratory Status</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           id="availability-toggle"
                                           {{ auth()->user()->laboratory()->first()->is_available ? 'checked' : '' }}
                                           onchange="toggleAvailability()"
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                    <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400 status-label">
                                        {{ auth()->user()->laboratory()->first()->is_available ? 'Online' : 'Offline' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <!-- User Profile Section -->
                    <div x-data="{ isOpen: false }" @click.away="isOpen = false" class="relative">
                        <button @click="isOpen = !isOpen" 
                                type="button"
                                class="flex items-center space-x-4 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-500 rounded-xl shadow-lg flex items-center justify-center">
                                    <span class="text-white text-base font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Laboratory Staff</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 hidden md:block transition-transform duration-200" 
                                     :class="{'rotate-180': isOpen}"
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="isOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-60 rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Mobile Status Toggle -->
                            @if(auth()->user()->laboratory()->first())
                                <div class="md:hidden px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Laboratory Status</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   id="mobile-availability-toggle"
                                                   {{ auth()->user()->laboratory()->first()->is_available ? 'checked' : '' }}
                                                   onchange="toggleAvailability()"
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                            <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400 status-label">
                                                {{ auth()->user()->laboratory()->first()->is_available ? 'Online' : 'Offline' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="px-2 py-2">
                                <a href="{{ route('laboratory.settings.index') }}" 
                                   @click="isOpen = false"
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-cog w-4 text-gray-400"></i>
                                    Settings
                                </a>

                                <form action="{{ route('logout') }}" method="POST" class="px-2">
                                    @csrf
                                    <button type="submit" 
                                            @click="isOpen = false"
                                            class="w-full mt-2 flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-sign-out-alt w-4"></i>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button type="button" 
                            onclick="toggleMobileMenu()" 
                            class="lg:hidden inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="lg:hidden hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <!-- Dashboard -->
                    <a href="{{ route('laboratory.dashboard') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.dashboard') 
                                  ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('laboratory.dashboard') ? 'text-purple-500 dark:text-purple-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Dashboard
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('laboratory.appointments.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.appointments.*') 
                                  ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-calendar-check mr-3 {{ request()->routeIs('laboratory.appointments.*') ? 'text-green-500 dark:text-green-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Appointments
                        @if($pendingCount > 0)
                            <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Test Results -->
                    <a href="{{ route('laboratory.results.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.results.*') 
                                  ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-file-medical mr-3 {{ request()->routeIs('laboratory.results.*') ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Test Results
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('laboratory.settings.index') }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200
                              {{ request()->routeIs('laboratory.settings.*') 
                                  ? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200' 
                                  : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <i class="fas fa-cog mr-3 {{ request()->routeIs('laboratory.settings.*') ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        Settings
                    </a>

                    <!-- Mobile Availability Toggle -->
                    @if(auth()->user()->laboratory()->first())
                        <div class="px-3 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Laboratory Status</span>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" 
                                           id="mobile-availability-toggle"
                                           {{ auth()->user()->laboratory()->first()->is_available ? 'checked' : '' }}
                                           onchange="toggleAvailability()"
                                           class="sr-only">
                                    <div class="relative">
                                        <div class="block bg-gray-300 dark:bg-gray-600 w-12 h-6 rounded-full transition-colors duration-200"></div>
                                        <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-600 dark:text-gray-400 font-medium">
                                        {{ auth()->user()->laboratory()->first()->is_available ? 'Online' : 'Offline' }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
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
        // Mobile menu toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Initialize theme from localStorage or system preference
        document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });

        // Availability toggle functionality
        function toggleAvailability() {
            const toggle = document.getElementById('availability-toggle');
            const mobileToggle = document.getElementById('mobile-availability-toggle');
            const statusTexts = document.querySelectorAll('[data-status-text]');
            const currentStatus = toggle ? toggle.checked : mobileToggle.checked;
            
            // Show loading state
            [toggle, mobileToggle].forEach(t => {
                if (t) {
                    t.disabled = true;
                    const bg = t.parentElement.querySelector('.peer');
                    bg.classList.add('opacity-50');
                }
            });
            
            fetch('{{ route("laboratory.settings.toggle-availability") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_available: currentStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading state
                [toggle, mobileToggle].forEach(t => {
                    if (t) {
                        t.disabled = false;
                        const bg = t.parentElement.querySelector('.peer');
                        bg.classList.remove('opacity-50');
                    }
                });

                if (data.success) {
                    // Update toggle states
                    [toggle, mobileToggle].forEach(t => {
                        if (t) {
                            t.checked = data.is_available;
                            const bg = t.parentElement.querySelector('.peer');
                            
                            if (data.is_available) {
                                bg.classList.add('bg-purple-600');
                                bg.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                            } else {
                                bg.classList.remove('bg-purple-600');
                                bg.classList.add('bg-gray-200', 'dark:bg-gray-700');
                            }
                        }
                    });

                    // Update status text
                    const statusLabels = document.querySelectorAll('.status-label');
                    statusLabels.forEach(label => {
                        label.textContent = data.is_available ? 'Online' : 'Offline';
                    });

                    // Show success message
                    showNotification(data.message || 'Status updated successfully', 'success');
                } else {
                    // Revert toggle state on failure
                    [toggle, mobileToggle].forEach(t => {
                        if (t) {
                            t.checked = !currentStatus;
                            const bg = t.parentElement.querySelector('.peer');
                            
                            if (!currentStatus) {
                                bg.classList.add('bg-purple-600');
                                bg.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                            } else {
                                bg.classList.remove('bg-purple-600');
                                bg.classList.add('bg-gray-200', 'dark:bg-gray-700');
                            }
                        }
                    });
                    
                    // Show error message
                    showNotification(data.message || 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Remove loading state
                [toggle, mobileToggle].forEach(t => {
                    if (t) {
                        t.disabled = false;
                        const bg = t.parentElement.querySelector('.peer');
                        bg.classList.remove('opacity-50');
                    }
                });

                // Revert toggle state on error
                [toggle, mobileToggle].forEach(t => {
                    if (t) {
                        t.checked = !currentStatus;
                        const bg = t.parentElement.querySelector('.peer');
                        
                        if (!currentStatus) {
                            bg.classList.add('bg-purple-600');
                            bg.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                        } else {
                            bg.classList.remove('bg-purple-600');
                            bg.classList.add('bg-gray-200', 'dark:bg-gray-700');
                        }
                    }
                });
                
                // Show error message
                showNotification('Failed to update status. Please try again.', 'error');
            });
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notificationId = 'status-notification';
            let notification = document.getElementById(notificationId);
            
            // Remove existing notification if present
            if (notification) {
                notification.remove();
            }
            
            // Create new notification
            notification = document.createElement('div');
            notification.id = notificationId;
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 ${
                type === 'success' 
                    ? 'bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800' 
                    : 'bg-red-50 dark:bg-red-900/20 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} text-lg"></i>
                    <p class="text-sm font-medium">${message}</p>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.add('translate-y-1', 'opacity-100');
                notification.classList.remove('translate-y-0', 'opacity-0');
            }, 100);
            
            // Remove after delay
            setTimeout(() => {
                notification.classList.add('translate-y-0', 'opacity-0');
                notification.classList.remove('translate-y-1', 'opacity-100');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Initialize availability toggle on page load
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('availability-toggle');
            const mobileToggle = document.getElementById('mobile-availability-toggle');
            
            [toggle, mobileToggle].forEach(t => {
                if (t && t.checked) {
                    const bg = t.parentElement.querySelector('.peer');
                    if (bg) {
                        bg.classList.add('bg-purple-600');
                        bg.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                    }
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuButton = event.target.closest('[onclick="toggleMobileMenu()"]');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Theme toggle functionality
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
</body>
</html>
