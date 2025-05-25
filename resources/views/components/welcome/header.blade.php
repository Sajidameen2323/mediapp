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
