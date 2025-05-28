<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MediCare - Healthcare Management Platform')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Dark mode transition */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
    </style>
    
    {{-- <script>
        // Dark mode toggle functionality
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }
        
        // Initialize dark mode from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true' || (!darkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        });
    </script> --}}
    
    @stack('head')
    @stack('styles')
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen antialiased">
    <!-- Simple Healthcare Navigation -->
    <nav class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-b border-gray-200/20 dark:border-gray-700/20 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand Section with Home Navigation -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group transition-all duration-200 hover:scale-105">
                    <div class="bg-gray-900 p-2.5 rounded-xl shadow-lg group-hover:shadow-xl transition-all duration-200">
                        <i class="fas fa-heartbeat text-white text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">MediCare</h1>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Healthcare Platform</span>
                    </div>
                </a>                @auth
                    <div class="flex items-center space-x-6">
                        @switch(auth()->user()->user_type)
                            @case('admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                                    <i class="fas fa-shield-alt mr-2 text-orange-500"></i>Admin Panel
                                </a>
                            @break

                            @case('doctor')
                                <a href="{{ route('doctor.dashboard') }}"
                                    class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                                    <i class="fas fa-stethoscope mr-2 text-green-500"></i>Doctor Panel
                                </a>
                            @break

                            @case('patient')
                                <a href="{{ route('patient.dashboard') }}"
                                    class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                                    <i class="fas fa-user-injured mr-2 text-blue-500"></i>My Health
                                </a>
                            @break

                            @case('laboratory_staff')
                                <a href="{{ route('lab.dashboard') }}"
                                    class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                                    <i class="fas fa-flask mr-2 text-purple-500"></i>Lab Panel
                                </a>
                            @break

                            @case('pharmacist')
                                <a href="{{ route('pharmacy.dashboard') }}"
                                    class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                                    <i class="fas fa-pills mr-2 text-teal-500"></i>Pharmacy Panel
                                </a>
                            @break
                        @endswitch

                        <!-- Dark Mode Toggle -->
                        {{-- <button onclick="toggleDarkMode()" 
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                            <i class="fas fa-moon dark:hidden text-lg"></i>
                            <i class="fas fa-sun hidden dark:block text-lg"></i>
                        </button> --}}

                        <!-- User Profile Section -->
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div class="hidden sm:block">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', auth()->user()->user_type)) }}</span>
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
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-3">
                        <!-- Dark Mode Toggle for guests -->
                        {{-- <button onclick="toggleDarkMode()" 
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200">
                            <i class="fas fa-moon dark:hidden text-lg"></i>
                            <i class="fas fa-sun hidden dark:block text-lg"></i>
                        </button> --}}
                        
                        <a href="{{ route('login') }}"
                            class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>Get Started
                        </a>
                    </div>
                @endauth</div>
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
</body>

</html>
