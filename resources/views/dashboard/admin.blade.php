@extends('layouts.app')

@section('title', 'Admin Dashboard - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage the medical application system</p>
    </div>    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">U</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Users</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalUsers }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">A</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $activeUsers }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Doctors</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\User::where('user_type', 'doctor')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Patients</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\User::where('user_type', 'patient')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-flask text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Laboratories</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\Laboratory::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-pills text-white text-xs"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pharmacies</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\Pharmacy::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Doctor Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Register and manage doctors</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.doctors.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-user-md mr-2"></i>Manage Doctors
                    </a>
                    <a href="{{ route('admin.doctors.create') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-plus mr-2"></i>Add New Doctor
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Laboratory Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Register and manage partner laboratories</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.laboratories.index') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-flask mr-2"></i>Manage Laboratories
                    </a>
                    <a href="{{ route('admin.laboratories.create') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-plus mr-2"></i>Add New Laboratory
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Pharmacy Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Register and manage partner pharmacies</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.pharmacies.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-pills mr-2"></i>Manage Pharmacies
                    </a>
                    <a href="{{ route('admin.pharmacies.create') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-plus mr-2"></i>Add New Pharmacy
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Service Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage medical services</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.services.index') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-briefcase-medical mr-2"></i>Manage Services
                    </a>
                    <a href="{{ route('admin.services.create') }}" class="block w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-plus mr-2"></i>Add New Service
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Appointment Configuration</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Configure appointment settings and policies</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.appointment-config.index') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Appointment Settings
                    </a>
                    <a href="{{ route('admin.appointment-config.edit') }}" class="block w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-cog mr-2"></i>Configure System
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Holiday Request Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Review and approve doctor holiday requests</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.holidays.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-calendar-check mr-2"></i>Manage Holiday Requests
                    </a>
                    <div class="flex space-x-2 mt-2">
                        <span class="flex-1 text-center bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-2 py-1 rounded text-xs">
                            {{ \App\Models\DoctorHoliday::where('status', 'pending')->count() }} Pending
                        </span>
                        <span class="flex-1 text-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs">
                            {{ \App\Models\DoctorHoliday::where('status', 'approved')->count() }} Approved
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Appointment Management</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and manage all appointments</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.appointments.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 text-center">
                        <i class="fas fa-calendar-alt mr-2"></i>Manage Appointments
                    </a>
                    <div class="flex space-x-2 mt-2">
                        <span class="flex-1 text-center bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-2 py-1 rounded text-xs">
                            {{ \App\Models\Appointment::where('status', 'pending')->count() }} Pending
                        </span>
                        <span class="flex-1 text-center bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs">
                            {{ \App\Models\Appointment::where('status', 'confirmed')->count() }} Confirmed
                        </span>
                        <span class="flex-1 text-center bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs">
                            {{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }} Today
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Reports</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View system reports and analytics</p>
                <div class="mt-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors duration-200">
                        View Reports
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
