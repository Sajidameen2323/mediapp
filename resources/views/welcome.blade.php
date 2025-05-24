<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>MediCare - Your Complete Healthcare Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
    
        @endif
        
        <style>
            body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
            
            @keyframes float {
                0%, 100% {
                    transform: translateY(0px);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            
            .animate-pulse-slow {
                animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 antialiased">
        <!-- Navigation Header -->
        <header class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-b border-gray-200/20 dark:border-gray-700/20 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 lg:h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2.5 rounded-xl shadow-lg">
                            <i class="fas fa-heartbeat text-white text-2xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">MediCare</h1>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Healthcare Platform</span>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex items-center space-x-8">
                        <a href="#services" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-all duration-200 relative group">
                            Services
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
                        </a>
                        <a href="#doctors" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-all duration-200 relative group">
                            Find Doctors
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
                        </a>
                        <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-all duration-200 relative group">
                            About
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
                        </a>
                        <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-all duration-200 relative group">
                            Contact
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
                        </a>
                    </nav>

                    <!-- Auth Buttons -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-3">
                            @auth
                                <a href="{{ \App\Http\Controllers\AuthController::getDashboardRoute() }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 lg:px-6 py-2 lg:py-2.5 rounded-xl font-semibold text-sm lg:text-base hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span class="hidden sm:inline">Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-semibold text-sm lg:text-base transition-colors duration-200">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 lg:px-6 py-2 lg:py-2.5 rounded-xl font-semibold text-sm lg:text-base hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                        {{-- <i class="fas fa-user-plus"></i> --}}
                                        <span class="hidden sm:inline">Get Started</span>
                                    </a>
                                @endif
                            @endauth
                            
                            <!-- Mobile Menu Button -->
                            <button id="mobile-menu-button" class="lg:hidden p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-bars text-xl" id="menu-icon"></i>
                                <i class="fas fa-times text-xl hidden" id="close-icon"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-200/20 dark:border-gray-700/20">
                <div class="px-4 py-4 space-y-3">
                    <a href="#services" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2 transition-colors">
                        Services
                    </a>
                    <a href="#doctors" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2 transition-colors">
                        Find Doctors
                    </a>
                    <a href="#about" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2 transition-colors">
                        About
                    </a>
                    <a href="#contact" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium py-2 transition-colors">
                        Contact
                    </a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ \App\Http\Controllers\AuthController::getDashboardRoute() }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg mt-4">
                                Dashboard
                            </a>
                        @else
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                                <a href="{{ route('login') }}" class="block text-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-semibold py-2 transition-colors">
                                    Sign In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg">
                                        Get Started
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 text-white">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="text-center">
                    <!-- Hero Badge -->
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-8 border border-white/20">
                        <i class="fas fa-star text-yellow-300"></i>
                        <span class="text-sm font-medium text-white/90">AI-Powered Healthcare Platform</span>
                    </div>

                    <!-- Hero Title -->
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                        Your Health,
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            Our Priority
                        </span>
                    </h1>

                    <!-- Hero Description -->
                    <p class="text-lg sm:text-xl lg:text-2xl mb-10 text-blue-100 max-w-4xl mx-auto leading-relaxed font-light">
                        Complete healthcare ecosystem with AI-powered assistance, seamless appointment booking, 
                        comprehensive lab services, and intelligent medication management - all unified in one platform.
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                        <button class="inline-flex items-center gap-3 bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-2xl hover:shadow-white/20 group">
                            <i class="fas fa-calendar-alt group-hover:rotate-12 transition-transform duration-200"></i>
                            Book Appointment
                        </button>
                        <button class="inline-flex items-center gap-3 border-2 border-white/30 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white hover:text-blue-600 transform hover:scale-105 transition-all duration-200 backdrop-blur-sm group">
                            <i class="fas fa-search group-hover:scale-110 transition-transform duration-200"></i>
                            Find a Doctor
                        </button>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                        <div class="text-center group">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                                <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-2">500+</div>
                                <div class="text-blue-200 font-medium">Expert Doctors</div>
                            </div>
                        </div>
                        <div class="text-center group">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                                <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-2">50K+</div>
                                <div class="text-blue-200 font-medium">Happy Patients</div>
                            </div>
                        </div>
                        <div class="text-center group">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                                <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-2">100+</div>
                                <div class="text-blue-200 font-medium">Specialties</div>
                            </div>
                        </div>
                        <div class="text-center group">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                                <div class="text-3xl lg:text-4xl font-bold text-yellow-300 mb-2">24/7</div>
                                <div class="text-blue-200 font-medium">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Assistant Section -->
        <section class="py-16 lg:py-24 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-full px-4 py-2 mb-6">
                        <i class="fas fa-microchip text-sm text-blue-600 dark:text-blue-400"></i>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">AI-Powered Assistant</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        Meet Your Intelligent
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Health Assistant
                        </span>
                    </h2>
                    <p class="text-lg lg:text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                        Get instant, personalized healthcare guidance with our advanced AI that understands your needs, 
                        finds the perfect specialists, and streamlines your entire healthcare journey.
                    </p>
                </div>

                <!-- AI Demo Card -->
                <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-3xl p-8 lg:p-12 text-white shadow-2xl">
                    <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        <!-- Chat Interface Demo -->
                        <div class="order-2 lg:order-1">
                            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 lg:p-8 border border-white/20">
                                <!-- Chat Header -->
                                <div class="flex items-center gap-3 pb-6 border-b border-white/20 mb-6">
                                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-sparkles text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">MediCare AI</h3>
                                        <p class="text-sm text-white/70">Your Health Assistant</p>
                                    </div>
                                    <div class="ml-auto">
                                        <div class="flex items-center gap-1">
                                            <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                            <span class="text-xs text-white/70">Online</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Messages -->
                                <div class="space-y-4">
                                    <div class="flex justify-end">
                                        <div class="bg-white/20 rounded-2xl rounded-tr-md px-4 py-3 max-w-xs">
                                            <p class="text-sm text-white">"I need a cardiologist appointment urgently"</p>
                                        </div>
                                    </div>
                                    <div class="flex justify-start">
                                        <div class="bg-white/30 rounded-2xl rounded-tl-md px-4 py-3 max-w-sm">
                                            <p class="text-sm text-white font-medium">I found 3 cardiologists with immediate availability:</p>
                                            <div class="mt-2 space-y-1 text-xs text-white/90">
                                                <p>• Dr. Sarah Johnson - Available today 3:30 PM</p>
                                                <p>• Dr. Michael Chen - Available tomorrow 9:00 AM</p>
                                                <p>• Dr. Emily Parker - Available today 4:15 PM</p>
                                            </div>
                                            <p class="text-sm text-white mt-2">Would you like me to book with Dr. Johnson?</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Typing Indicator -->
                                <div class="flex items-center gap-2 mt-4 text-white/70">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-white/50 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-white/50 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                    <span class="text-sm">AI is typing...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="order-1 lg:order-2">
                            <h3 class="text-2xl lg:text-3xl font-bold mb-6">Intelligent Healthcare Navigation</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check text-sm text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white mb-1">Smart Doctor Matching</h4>
                                        <p class="text-sm text-white/80">Find specialists by symptoms, location, and availability</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check text-sm text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white mb-1">Symptom Analysis</h4>
                                        <p class="text-sm text-white/80">Get preliminary assessments and recommendations</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check text-sm text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white mb-1">Instant Scheduling</h4>
                                        <p class="text-sm text-white/80">Book appointments and lab tests in seconds</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-400 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check text-sm text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-white mb-1">24/7 Health Support</h4>
                                        <p class="text-sm text-white/80">Round-the-clock guidance and emergency assistance</p>
                                    </div>
                                </div>
                            </div>
                            <button class="mt-8 inline-flex items-center gap-3 bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transform hover:scale-105 transition-all duration-200 shadow-xl group">
                                <i class="fas fa-comment-dots text-lg group-hover:scale-110 transition-transform duration-200"></i>
                                Start Chatting Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Services Grid -->
        <section id="services" class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <div class="inline-flex items-center gap-2 bg-white dark:bg-gray-900 rounded-full px-4 py-2 mb-6 shadow-lg border border-gray-200 dark:border-gray-700">
                        <i class="fas fa-plus-square text-sm text-blue-600 dark:text-blue-400"></i>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Complete Healthcare Services</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        Everything You Need for Your
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Health Journey
                        </span>
                    </h2>
                    <p class="text-lg lg:text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                        Comprehensive healthcare solutions integrated into one seamless platform for your convenience and peace of mind.
                    </p>
                </div>

                <!-- Services Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Doctor Appointments -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-blue-200 dark:hover:border-blue-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Doctor Appointments</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Book consultations with over 500 specialists across 100+ medical disciplines with real-time availability.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clock text-xs text-green-500"></i>
                                <span>Next available: Today 3:30 PM</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-user text-xs text-blue-500"></i>
                                <span>Dr. Sarah Johnson - Cardiologist</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-star text-xs text-yellow-500"></i>
                                <span>4.9 rating (150 reviews)</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Book Appointment
                        </button>
                    </div>

                    <!-- Pharmacy Portal -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-green-200 dark:hover:border-green-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/50 dark:to-green-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-pills text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Pharmacy Portal</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Order medications online with home delivery, prescription management, and automated refill reminders.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clock text-xs text-green-500"></i>
                                <span>24/7 pharmacy network</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-truck text-xs text-blue-500"></i>
                                <span>Same-day delivery available</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-bell text-xs text-yellow-500"></i>
                                <span>Smart refill reminders</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Order Medicine
                        </button>
                    </div>

                    <!-- Lab Tests -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-purple-200 dark:hover:border-purple-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/50 dark:to-purple-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clipboard-list text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Lab Tests & Results</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Schedule lab tests and access digital results with AI-powered insights and detailed explanations.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-home text-xs text-green-500"></i>
                                <span>Home sample collection</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-file-alt text-xs text-blue-500"></i>
                                <span>Digital reports in 24-48 hrs</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-microchip text-xs text-purple-500"></i>
                                <span>AI-powered result insights</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Schedule Test
                        </button>
                    </div>

                    <!-- Medication Tracking -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-orange-200 dark:hover:border-orange-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/50 dark:to-orange-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-prescription-bottle-alt text-2xl text-orange-600 dark:text-orange-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Medication Tracking</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Never miss a dose with intelligent reminders, medication management, and interaction monitoring.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-th text-xs text-green-500"></i>
                                <span>3 active medications</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-bell text-xs text-blue-500"></i>
                                <span>Next: Metformin - 2:00 PM</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-exclamation-triangle text-xs text-orange-500"></i>
                                <span>Refill: Lisinopril - 3 days</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-orange-600 to-orange-700 text-white py-3 rounded-xl font-semibold hover:from-orange-700 hover:to-orange-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Manage Medications
                        </button>
                    </div>

                    <!-- Patient Profile -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-indigo-200 dark:hover:border-indigo-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/50 dark:to-indigo-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-id-card text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Patient Profile</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Comprehensive health profile with medical history, preferences, and secure information management.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-check-circle text-xs text-green-500"></i>
                                <span>Medical history: Complete</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-phone text-xs text-blue-500"></i>
                                <span>Emergency contacts: 2 added</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt text-xs text-indigo-500"></i>
                                <span>Insurance: Active coverage</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-3 rounded-xl font-semibold hover:from-indigo-700 hover:to-indigo-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Update Profile
                        </button>
                    </div>

                    <!-- Payment Integration -->
                    <div class="group bg-white dark:bg-gray-900 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border border-gray-100 dark:border-gray-800 hover:border-red-200 dark:hover:border-red-800 transform hover:-translate-y-2">
                        <div class="bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50 w-16 h-16 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-credit-card text-2xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Secure Payments</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">Safe and convenient payment options for all medical services with transparent pricing and insurance integration.</p>
                        
                        <!-- Service Details -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-money-bill-wave text-xs text-green-500"></i>
                                <span>Multiple payment methods</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-clipboard text-xs text-blue-500"></i>
                                <span>Insurance claim processing</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-eye text-xs text-red-500"></i>
                                <span>Transparent pricing</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Payment History
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Appointment Management Dashboard Preview -->
        <section class="py-16 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Smart Appointment Management
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400">
                        Never miss an appointment with intelligent scheduling and reminders
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Upcoming Appointments -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-lg text-blue-600"></i>
                                Upcoming Appointments
                            </h3>
                            <div class="space-y-4">
                                <div class="border-l-4 border-blue-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Dr. Sarah Johnson</p>
                                            <p class="text-gray-600 dark:text-gray-400">Cardiologist</p>
                                            <p class="text-sm text-gray-500">Today, 3:30 PM</p>
                                        </div>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Confirmed</span>
                                    </div>
                                </div>
                                <div class="border-l-4 border-purple-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Lab Test</p>
                                            <p class="text-gray-600 dark:text-gray-400">Blood Work Panel</p>
                                            <p class="text-sm text-gray-500">Tomorrow, 9:00 AM</p>
                                        </div>
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Scheduled</span>
                                    </div>
                                </div>
                                <div class="border-l-4 border-green-500 pl-4 py-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">Dr. Michael Chen</p>
                                            <p class="text-gray-600 dark:text-gray-400">Dermatologist</p>
                                            <p class="text-sm text-gray-500">Next Tuesday, 11:15 AM</p>
                                        </div>
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Reminder Set</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="space-y-4">
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <button class="bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-400 p-4 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition-colors">
                                        <i class="fas fa-plus text-xl mx-auto mb-2 block"></i>
                                        <span class="text-sm font-medium">Book New</span>
                                    </button>
                                    <button class="bg-green-50 dark:bg-green-900 text-green-600 dark:text-green-400 p-4 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition-colors">
                                        <i class="fas fa-calendar-alt text-xl mx-auto mb-2 block"></i>
                                        <span class="text-sm font-medium">Reschedule</span>
                                    </button>
                                    <button class="bg-purple-50 dark:bg-purple-900 text-purple-600 dark:text-purple-400 p-4 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition-colors">
                                        <i class="fas fa-history text-xl mx-auto mb-2 block"></i>
                                        <span class="text-sm font-medium">History</span>
                                    </button>
                                    <button class="bg-orange-50 dark:bg-orange-900 text-orange-600 dark:text-orange-400 p-4 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-800 transition-colors">
                                        <i class="fas fa-clock text-xl mx-auto mb-2 block"></i>
                                        <span class="text-sm font-medium">Reminders</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Health Summary -->
                            <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-lg">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Health Summary</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Blood Pressure</span>
                                        <span class="text-green-600 font-medium">Normal</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Cholesterol</span>
                                        <span class="text-yellow-600 font-medium">Monitor</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Blood Sugar</span>
                                        <span class="text-green-600 font-medium">Normal</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Last Checkup</span>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">2 weeks ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Find Doctors Section -->
        <section id="doctors" class="py-16 bg-gray-50 dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Find the Right Doctor
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400">
                        Browse our network of qualified healthcare professionals
                    </p>
                </div>

                <!-- Search Bar -->
                <div class="max-w-4xl mx-auto mb-8">
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Specialty</label>
                                <select class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                                    <option>All Specialties</option>
                                    <option>Cardiology</option>
                                    <option>Dermatology</option>
                                    <option>Neurology</option>
                                    <option>Pediatrics</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
                                <input type="text" placeholder="Enter city or zip" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white">
                            </div>
                            <div class="flex items-end">
                                <button class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Search Doctors
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Doctors -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32"></div>
                        <div class="p-6 -mt-16">
                            <div class="bg-white dark:bg-gray-800 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-blue-600">SJ</span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Dr. Sarah Johnson</h3>
                                <p class="text-blue-600 dark:text-blue-400 font-medium">Cardiologist</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">15 years experience</p>
                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">4.9 (150 reviews)</span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Next available: Today 3:30 PM</p>
                                <button class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Book Appointment
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-blue-600 h-32"></div>
                        <div class="p-6 -mt-16">
                            <div class="bg-white dark:bg-gray-800 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-green-600">MC</span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Dr. Michael Chen</h3>
                                <p class="text-green-600 dark:text-green-400 font-medium">Dermatologist</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">12 years experience</p>
                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">4.8 (98 reviews)</span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Next available: Tomorrow 10:00 AM</p>
                                <button class="mt-4 w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors">
                                    Book Appointment
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-32"></div>
                        <div class="p-6 -mt-16">
                            <div class="bg-white dark:bg-gray-800 w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                                <span class="text-2xl font-bold text-purple-600">EP</span>
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Dr. Emily Parker</h3>
                                <p class="text-purple-600 dark:text-purple-400 font-medium">Pediatrician</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">8 years experience</p>
                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-yellow-400">⭐⭐⭐⭐⭐</span>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm ml-2">4.9 (203 reviews)</span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Next available: Monday 2:15 PM</p>
                                <button class="mt-4 w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                    Book Appointment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Feedback Section -->
        <section class="py-16 bg-white dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        What Our Patients Say
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400">
                        Real feedback from real patients
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                J
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">John Smith</p>
                                <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">
                            "Excellent platform! Booking appointments is so easy, and the AI assistant helped me find the perfect doctor for my condition."
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                M
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">Maria Garcia</p>
                                <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">
                            "The medication tracking feature is a game-changer. I never miss my doses anymore, and the pharmacy delivery is super convenient."
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                D
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-900 dark:text-white">David Lee</p>
                                <div class="text-yellow-400">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">
                            "Lab results in my pocket! Getting test results online with detailed explanations has made managing my health so much easier."
                        </p>
                    </div>
                </div>

                <!-- Feedback Form -->
                <div class="mt-12 max-w-2xl mx-auto bg-gray-50 dark:bg-gray-800 rounded-xl p-8">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 text-center">Share Your Experience</h3>
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Name</label>
                            <input type="text" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex space-x-2">
                                <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">⭐</button>
                                <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">⭐</button>
                                <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">⭐</button>
                                <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">⭐</button>
                                <button type="button" class="text-3xl text-gray-300 hover:text-yellow-400 transition-colors">⭐</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Feedback</label>
                            <textarea rows="4" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white" placeholder="Tell us about your experience..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-blue-600 p-2 rounded-lg">
                                <i class="fas fa-heartbeat text-xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold">MediCare</h3>
                        </div>
                        <p class="text-gray-400 mb-4">Your complete healthcare platform for appointments, medications, lab tests, and more.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <span class="sr-only">Facebook</span>
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                <span class="sr-only">Twitter</span>
                                <i class="fab fa-twitter text-xl"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Services</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Doctor Appointments</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Lab Tests</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Pharmacy</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">AI Assistant</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Emergency</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Insurance</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                        <div class="space-y-2 text-gray-400">
                            <p>📞 1-800-MEDICARE</p>
                            <p>✉️ support@medicare.com</p>
                            <p>📍 123 Health St, Medical City</p>
                            <p>🕒 24/7 Emergency Support</p>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; 2025 MediCare. All rights reserved. | Privacy Policy | Terms of Service</p>
                </div>
            </div>
        </footer>

        <!-- Enhanced JavaScript for interactivity -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mobile menu toggle functionality
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                
                if (mobileMenuButton && mobileMenu) {
                    mobileMenuButton.addEventListener('click', function() {
                        mobileMenu.classList.toggle('hidden');
                        menuIcon.classList.toggle('hidden');
                        closeIcon.classList.toggle('hidden');
                    });

                    // Close mobile menu when clicking on a link
                    mobileMenu.querySelectorAll('a').forEach(link => {
                        link.addEventListener('click', function() {
                            mobileMenu.classList.add('hidden');
                            menuIcon.classList.remove('hidden');
                            closeIcon.classList.add('hidden');
                        });
                    });
                }

                // Add smooth scrolling to anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });

                // Enhanced star rating interaction for feedback form
                document.querySelectorAll('.text-3xl').forEach((star, index) => {
                    star.addEventListener('click', function() {
                        const stars = Array.from(this.parentElement.children);
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-yellow-400');
                            } else {
                                s.classList.remove('text-yellow-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });

                    star.addEventListener('mouseover', function() {
                        const stars = Array.from(this.parentElement.children);
                        stars.forEach((s, i) => {
                            if (i <= index) {
                                s.style.transform = 'scale(1.1)';
                            } else {
                                s.style.transform = 'scale(1)';
                            }
                        });
                    });

                    star.addEventListener('mouseout', function() {
                        const stars = Array.from(this.parentElement.children);
                        stars.forEach(s => {
                            s.style.transform = 'scale(1)';
                        });
                    });
                });

                // Add animation to feature cards on scroll
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);

                // Observe service cards for animation
                document.querySelectorAll('.group').forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });

                // Add typing animation to AI chat
                const aiMessages = document.querySelectorAll('.bg-white\\/30');
                if (aiMessages.length > 0) {
                    let delay = 0;
                    aiMessages.forEach(message => {
                        setTimeout(() => {
                            message.style.opacity = '0';
                            message.style.transform = 'translateY(10px)';
                            message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                            
                            setTimeout(() => {
                                message.style.opacity = '1';
                                message.style.transform = 'translateY(0)';
                            }, 100);
                        }, delay);
                        delay += 1000;
                    });
                }

                // Add floating animation to statistics cards
                const statsCards = document.querySelectorAll('.bg-white\\/10');
                statsCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.2}s`;
                    card.classList.add('animate-float');
                });

                // Add hover effects to service buttons
                document.querySelectorAll('button').forEach(button => {
                    button.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
                    });

                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '';
                    });
                });

                // Add simple form validation for feedback form
                const feedbackForm = document.querySelector('form');
                if (feedbackForm) {
                    feedbackForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const name = this.querySelector('input[type="text"]').value;
                        const feedback = this.querySelector('textarea').value;
                        const selectedStars = this.querySelectorAll('.text-yellow-400').length;
                        
                        if (!name || !feedback || selectedStars === 0) {
                            alert('Please fill in all fields and provide a rating.');
                            return;
                        }
                        
                        // Simulate form submission
                        const submitBtn = this.querySelector('button[type="submit"]');
                        const originalText = submitBtn.textContent;
                        submitBtn.textContent = 'Submitting...';
                        submitBtn.disabled = true;
                        
                        setTimeout(() => {
                            alert('Thank you for your feedback!');
                            this.reset();
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                            
                            // Reset stars
                            this.querySelectorAll('.text-3xl').forEach(star => {
                                star.classList.remove('text-yellow-400');
                                star.classList.add('text-gray-300');
                            });
                        }, 2000);
                    });
                }

                // Add scroll to top functionality
                const scrollToTopBtn = document.createElement('button');
                scrollToTopBtn.innerHTML = '↑';
                scrollToTopBtn.className = 'fixed bottom-8 right-8 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 opacity-0 pointer-events-none z-50';
                scrollToTopBtn.style.fontSize = '20px';
                scrollToTopBtn.style.fontWeight = 'bold';
                document.body.appendChild(scrollToTopBtn);

                scrollToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });

                // Show/hide scroll to top button
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        scrollToTopBtn.style.opacity = '1';
                        scrollToTopBtn.style.pointerEvents = 'auto';
                    } else {
                        scrollToTopBtn.style.opacity = '0';
                        scrollToTopBtn.style.pointerEvents = 'none';
                    }
                });

                // Add loading animation for buttons
                document.querySelectorAll('button').forEach(button => {
                    if (button.textContent.includes('Book') || button.textContent.includes('Schedule') || button.textContent.includes('Order')) {
                        button.addEventListener('click', function(e) {
                            if (this.getAttribute('type') !== 'submit') {
                                e.preventDefault();
                                const originalText = this.textContent;
                                this.innerHTML = '<span class="inline-block animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Loading...';
                                this.disabled = true;
                                
                                setTimeout(() => {
                                    this.textContent = originalText;
                                    this.disabled = false;
                                    alert('Feature coming soon! This will redirect to the booking system.');
                                }, 1500);
                            }
                        });
                    }
                });

                // Add navigation highlight on scroll
                const sections = document.querySelectorAll('section[id]');
                const navLinks = document.querySelectorAll('nav a[href^="#"]');
                
                window.addEventListener('scroll', function() {
                    let current = '';
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.clientHeight;
                        if (pageYOffset >= sectionTop - 200) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('text-blue-600');
                        if (link.getAttribute('href') === `#${current}`) {
                            link.classList.add('text-blue-600');
                        }
                    });
                });

                // Add search functionality for doctors
                const searchInput = document.querySelector('input[placeholder="Enter city or zip"]');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase();
                        const doctorCards = document.querySelectorAll('.bg-white.dark\\:bg-gray-900.rounded-xl.shadow-lg');
                        
                        if (doctorCards.length > 0) {
                            doctorCards.forEach(card => {
                                const doctorName = card.querySelector('h3')?.textContent.toLowerCase() || '';
                                const specialty = card.querySelector('.text-blue-600, .text-green-600, .text-purple-600')?.textContent.toLowerCase() || '';
                                
                                if (doctorName.includes(query) || specialty.includes(query) || query === '') {
                                    card.style.display = 'block';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                        }
                    });
                }

                console.log('🏥 MediCare platform loaded successfully!');
            });
        </script>
    </body>
</html>
