@extends('layouts.admin')

@section('title', 'Admin Dashboard - Medi App')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-tachometer-alt text-blue-600 dark:text-blue-400 mr-3"></i>
                        Admin Dashboard
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Welcome back! Here's what's happening with your medical platform today.
                    </p>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg">
                        <div class="text-sm font-medium text-blue-900 dark:text-blue-300">Last Login</div>
                        <div class="text-xs text-blue-600 dark:text-blue-400">{{ now()->format('M d, Y - h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>        <!-- Enhanced Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>+12% from last month
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Active Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeUsers }}</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            {{ round(($activeUsers / $totalUsers) * 100, 1) }}% active rate
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600 dark:text-green-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Doctors -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Doctors</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('user_type', 'doctor')->count() }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            {{ \App\Models\Doctor::where('is_available', true)->count() }} available
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-stethoscope text-purple-600 dark:text-purple-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Patients -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Patients</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::where('user_type', 'patient')->count() }}</p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                            {{ \App\Models\Appointment::whereDate('created_at', today())->distinct('patient_id')->count() }} active today
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-injured text-yellow-600 dark:text-yellow-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Laboratories -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Laboratories</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Laboratory::count() }}</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ \App\Models\Laboratory::where('is_available', true)->count() }} active
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flask text-emerald-600 dark:text-emerald-400 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Pharmacies -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Pharmacies</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Pharmacy::count() }}</p>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                            {{ \App\Models\Pharmacy::where('is_available', true)->count() }} active
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Activity Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Today's Appointments -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Today's Appointments</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</p>
                        <p class="text-blue-200 text-xs mt-1">
                            {{ \App\Models\Appointment::whereDate('appointment_date', today())->where('status', 'completed')->count() }} completed
                        </p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Pending Approvals</p>
                        <p class="text-3xl font-bold">{{ \App\Models\Appointment::where('status', 'pending')->count() + \App\Models\DoctorHoliday::where('status', 'pending')->count() }}</p>
                        <p class="text-yellow-200 text-xs mt-1">
                            Appointments & Holidays
                        </p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Monthly Revenue</p>
                        <p class="text-3xl font-bold">${{ number_format(\App\Models\Payment::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount'), 0) }}</p>
                        <p class="text-green-200 text-xs mt-1">
                            This month
                        </p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1">System Health</p>
                        <p class="text-3xl font-bold">98%</p>
                        <p class="text-purple-200 text-xs mt-1">
                            All systems operational
                        </p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <i class="fas fa-heartbeat text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Admin Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Doctor Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-md text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded-full text-xs font-medium">
                        {{ \App\Models\Doctor::where('is_available', true)->count() }} Active
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Doctor Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Register and manage doctors in your network</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.doctors.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-list mr-2"></i>Manage Doctors
                    </a>
                    <a href="{{ route('admin.doctors.create') }}" class="block w-full bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-plus mr-2"></i>Add New Doctor
                    </a>
                </div>
            </div>

            <!-- Laboratory Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-flask text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded-full text-xs font-medium">
                        {{ \App\Models\Laboratory::where('is_available', true)->count() }} Active
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Laboratory Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage partner laboratories and testing facilities</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.laboratories.index') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-list mr-2"></i>Manage Laboratories
                    </a>
                    <a href="{{ route('admin.laboratories.create') }}" class="block w-full bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-plus mr-2"></i>Add New Laboratory
                    </a>
                </div>
            </div>

            <!-- Pharmacy Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-pills text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                    <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 px-2 py-1 rounded-full text-xs font-medium">
                        {{ \App\Models\Pharmacy::where('is_available', true)->count() }} Active
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pharmacy Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage partner pharmacies and medication services</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.pharmacies.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-list mr-2"></i>Manage Pharmacies
                    </a>
                    <a href="{{ route('admin.pharmacies.create') }}" class="block w-full bg-purple-100 dark:bg-purple-900/30 hover:bg-purple-200 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-plus mr-2"></i>Add New Pharmacy
                    </a>
                </div>
            </div>

            <!-- Service Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-briefcase-medical text-indigo-600 dark:text-indigo-400 text-xl"></i>
                    </div>
                    <span class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded-full text-xs font-medium">
                        {{ \App\Models\Service::where('is_active', true)->count() }} Active
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Service Management</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Manage medical services and treatment options</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.services.index') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-list mr-2"></i>Manage Services
                    </a>
                    <a href="{{ route('admin.services.create') }}" class="block w-full bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-200 dark:hover:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-plus mr-2"></i>Add New Service
                    </a>
                </div>
            </div>

            <!-- Appointment Configuration -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cogs text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                    <span class="bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 px-2 py-1 rounded-full text-xs font-medium">
                        Settings
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">System Configuration</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Configure appointment settings and system policies</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.appointment-config.index') }}" class="block w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-calendar-alt mr-2"></i>Appointment Settings
                    </a>
                    <a href="{{ route('admin.appointment-config.edit') }}" class="block w-full bg-orange-100 dark:bg-orange-900/30 hover:bg-orange-200 dark:hover:bg-orange-900/50 text-orange-700 dark:text-orange-300 px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-edit mr-2"></i>Configure System
                    </a>
                </div>
            </div>

            <!-- Holiday Requests -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-times text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <span class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-2 py-1 rounded-full text-xs font-medium">
                        {{ \App\Models\DoctorHoliday::where('status', 'pending')->count() }} Pending
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Holiday Requests</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Review and approve doctor holiday requests</p>
                <div class="space-y-2">
                    <a href="{{ route('admin.holidays.index') }}" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2.5 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-calendar-check mr-2"></i>Manage Requests
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="text-center bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 px-2 py-1.5 rounded-lg text-xs font-medium">
                            {{ \App\Models\DoctorHoliday::where('status', 'pending')->count() }} Pending
                        </div>
                        <div class="text-center bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 px-2 py-1.5 rounded-lg text-xs font-medium">
                            {{ \App\Models\DoctorHoliday::where('status', 'approved')->count() }} Approved
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Appointment Management -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Appointment Management</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View and manage all system appointments</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Appointment::where('status', 'pending')->count() }}</div>
                        <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">Pending</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Appointment::where('status', 'confirmed')->count() }}</div>
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">Confirmed</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</div>
                        <div class="text-xs text-purple-600 dark:text-purple-400 font-medium">Today</div>
                    </div>
                </div>
                
                <a href="{{ route('admin.appointments.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition-colors text-center font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>Manage All Appointments
                </a>
            </div>

            <!-- Reports & Analytics -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reports & Analytics</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View system reports and performance analytics</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="text-xl font-bold text-green-600 dark:text-green-400">${{ number_format(\App\Models\Payment::whereMonth('created_at', now()->month)->where('status', 'completed')->sum('amount'), 0) }}</div>
                        <div class="text-xs text-green-600 dark:text-green-400 font-medium">Monthly Revenue</div>
                    </div>
                    <div class="text-center p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ \App\Models\Appointment::whereMonth('created_at', now()->month)->count() }}</div>
                        <div class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Monthly Bookings</div>
                    </div>
                </div>
                
                <a href="{{ route('admin.reports.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition-colors text-center font-medium">
                    <i class="fas fa-chart-bar mr-2"></i>View Reports & Analytics
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
