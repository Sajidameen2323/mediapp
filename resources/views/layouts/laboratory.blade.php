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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
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
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">Lab Portal</h1>
                            @if(auth()->user()->laboratory()->first())
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ auth()->user()->laboratory()->first()->name ?? 'Laboratory' }}</span>
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
                <div class="flex items-center space-x-4">
                    <!-- Availability Toggle -->
                    @if(auth()->user()->laboratory()->first())
                        <div class="hidden sm:flex items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mr-2">Status:</span>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       id="availability-toggle"
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
                    @endif

                    <!-- User Profile Section -->
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <div class="hidden md:block">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400">Laboratory Staff</span>
                            </div>
                        </div>
                        
                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="flex items-center text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-1"></i>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="toggleMobileMenu()" 
                            class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500">
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
            
            fetch('{{ route("laboratory.settings.toggle-availability") }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_available: toggle ? toggle.checked : mobileToggle.checked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update both toggles
                    [toggle, mobileToggle].forEach(t => {
                        if (t) {
                            const bg = t.parentElement.querySelector('.block');
                            const dot = t.parentElement.querySelector('.dot');
                            
                            if (data.is_available) {
                                bg.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                                bg.classList.add('bg-green-400', 'dark:bg-green-500');
                                dot.classList.add('translate-x-6');
                                t.checked = true;
                            } else {
                                bg.classList.remove('bg-green-400', 'dark:bg-green-500');
                                bg.classList.add('bg-gray-300', 'dark:bg-gray-600');
                                dot.classList.remove('translate-x-6');
                                t.checked = false;
                            }
                        }
                    });
                    
                    // Update status text
                    const statusTexts = document.querySelectorAll('[data-status-text]');
                    statusTexts.forEach(text => {
                        text.textContent = data.is_available ? 'Online' : 'Offline';
                    });
                    
                    // Show success message
                    showNotification(data.message, 'success');
                } else {
                    // Show error message
                    showNotification(data.message || 'Failed to update availability', 'error');
                    
                    // Revert toggle state
                    [toggle, mobileToggle].forEach(t => {
                        if (t) t.checked = !t.checked;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to update availability', 'error');
                
                // Revert toggle state
                [toggle, mobileToggle].forEach(t => {
                    if (t) t.checked = !t.checked;
                });
            });
        }

        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-opacity duration-300 ${
                type === 'success' 
                    ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800' 
                    : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800'
            }`;
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Initialize availability toggle on page load
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('availability-toggle');
            const mobileToggle = document.getElementById('mobile-availability-toggle');
            
            [toggle, mobileToggle].forEach(t => {
                if (t && t.checked) {
                    const dot = t.parentElement.querySelector('.dot');
                    const bg = t.parentElement.querySelector('.block');
                    
                    bg.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                    bg.classList.add('bg-green-400', 'dark:bg-green-500');
                    dot.classList.add('translate-x-6');
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
    </script>
</body>
</html>
