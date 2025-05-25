@extends('layouts.app')

@section('title', 'Patient Dashboard - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Patient Dashboard</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your health and medical appointments</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">My Appointments</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and book appointments</p>
                <div class="mt-4">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        View Appointments
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Medical History</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View your medical records</p>
                <div class="mt-4">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        View History
                    </button>
                </div>
            </div>
        </div>        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Prescriptions</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View your active prescriptions</p>
                <div class="mt-4">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        View Prescriptions
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Lab Results</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View your lab test results</p>
                <div class="mt-4">
                    <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        View Results
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-user-injured mr-2 text-red-600 dark:text-red-400"></i>
                    Health Profile
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your comprehensive health information</p>
                @if(auth()->user()->healthProfile)
                    <!-- Health Profile exists -->
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="text-green-600 dark:text-green-400 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>Complete
                            </span>
                        </div>
                        @if(auth()->user()->healthProfile->blood_type)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Blood Type:</span>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->blood_type }}</span>
                            </div>
                        @endif
                        @if(auth()->user()->healthProfile->height && auth()->user()->healthProfile->weight)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">BMI:</span>
                                {{-- <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->getBMI() }}</span> --}}
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->bmi_category }}</span>

                            </div>
                        @endif
                    </div>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('patient.health-profile.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>View Profile
                        </a>
                        <a href="{{ route('patient.health-profile.edit') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Update Profile
                        </a>
                    </div>
                @else
                    <!-- No Health Profile -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="text-orange-600 dark:text-orange-400 font-medium flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Incomplete
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Create your health profile to help healthcare providers better assist you.</p>
                        <a href="{{ route('patient.health-profile.create') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Create Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Emergency Contacts</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage emergency contacts</p>
                <div class="mt-4">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        Manage Contacts
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
