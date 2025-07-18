@props(['currentRoute' => null])

<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('welcome') }}" class="flex items-center group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                        <i class="fas fa-mortar-pestle text-white text-lg"></i>
                    </div>
                    <div class="ml-3 hidden sm:block">
                        <h1 class="text-lg font-bold text-gray-900 dark:text-white">MediCare</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PharmacyOrder Management</p>
                    </div>
                </a>
            </div>

            <!-- Main Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <!-- Dashboard -->
                <a href="{{ route('pharmacy.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                           {{ request()->routeIs('pharmacy.dashboard') 
                               ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 shadow-sm' 
                               : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                    <i class="fas fa-chart-line w-4 h-4 mr-2 {{ request()->routeIs('pharmacy.dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                    Dashboard
                </a>

                <!-- Orders Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 group
                                   {{ request()->routeIs('pharmacy.orders.*') 
                                       ? 'bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300 shadow-sm' 
                                       : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }}">
                        <i class="fas fa-prescription-bottle-alt w-4 h-4 mr-2 {{ request()->routeIs('pharmacy.orders.*') ? 'text-green-600 dark:text-green-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"></i>
                        Orders
                        <i class="fas fa-chevron-down w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Order Management</p>
                        </div>
                        
                        <a href="{{ route('pharmacy.orders.index') }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-list text-blue-600 dark:text-blue-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">All Orders</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">View and manage orders</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('pharmacy.orders.index', ['status' => 'confirmed']) }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-orange-600 dark:text-orange-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Pending Orders</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Orders awaiting preparation</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('pharmacy.orders.index', ['status' => 'preparing']) }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-cogs text-yellow-600 dark:text-yellow-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">In Preparation</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Orders being prepared</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('pharmacy.orders.index', ['status' => 'ready']) }}" 
                           class="flex items-center px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium">Ready Orders</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ready for pickup/delivery</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="hidden lg:flex items-center space-x-3 ml-6 pl-6 border-l border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Today</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \App\Models\PharmacyOrder::whereDate('created_at', today())->count() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="text-sm font-semibold text-orange-600 dark:text-orange-400">{{ \App\Models\PharmacyOrder::where('status', 'confirmed')->count() }}</p>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative ml-6" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-50 dark:hover:bg-gray-700 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-gray-900 dark:text-white font-medium">{{ auth()->user()->name ?? 'Pharmacy User' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pharmacy Staff</p>
                        </div>
                        <i class="fas fa-chevron-down w-3 h-3 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Pharmacy User' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? 'user@pharmacy.com' }}</p>
                        </div>
                        
                        <div class="py-1">
                            <a href="#" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-edit text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Edit Profile</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Update your information</p>
                                </div>
                            </a>
                            
                            <a href="#" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-id-card text-green-600 dark:text-green-400 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium">View Profile</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Your account details</p>
                                </div>
                            </a>
                            
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-sign-out-alt text-red-600 dark:text-red-400 text-sm"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-medium">Sign Out</p>
                                        <p class="text-xs text-red-600 dark:text-red-500">End your session</p>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        x-data="{ mobileMenuOpen: false }"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="md:hidden" x-data="{ mobileMenuOpen: false }" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }">
        <div class="px-4 pt-2 pb-3 space-y-1 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('pharmacy.dashboard') }}" 
               class="flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors
                       {{ request()->routeIs('pharmacy.dashboard') 
                           ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' 
                           : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                Dashboard
            </a>

            <div class="space-y-1">
                <p class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Orders</p>
                
                <a href="{{ route('pharmacy.orders.index') }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ request()->routeIs('pharmacy.orders.index') && !request('status')
                               ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-list w-4 h-4 mr-3"></i>
                    All Orders
                </a>
                
                <a href="{{ route('pharmacy.orders.index', ['status' => 'confirmed']) }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ request('status') === 'confirmed'
                               ? 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-clock w-4 h-4 mr-3"></i>
                    Pending Orders
                </a>
                
                <a href="{{ route('pharmacy.orders.index', ['status' => 'preparing']) }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ request('status') === 'preparing'
                               ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-cogs w-4 h-4 mr-3"></i>
                    In Preparation
                </a>
                
                <a href="{{ route('pharmacy.orders.index', ['status' => 'ready']) }}" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ request('status') === 'ready'
                               ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' 
                               : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <i class="fas fa-check-circle w-4 h-4 mr-3"></i>
                    Ready Orders
                </a>
            </div>

            <!-- Mobile Profile Section -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-1">
                <p class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Account</p>
                
                <div class="px-3 py-2 flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'Pharmacy User' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email ?? 'user@pharmacy.com' }}</p>
                    </div>
                </div>
                
                <a href="#" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="fas fa-user-edit w-4 h-4 mr-3"></i>
                    Edit Profile
                </a>
                
                <a href="#" 
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="fas fa-id-card w-4 h-4 mr-3"></i>
                    View Profile
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium transition-colors text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-3"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
