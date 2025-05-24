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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Health Profile</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update your health information</p>
                <div class="mt-4">
                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        Update Profile
                    </button>
                </div>
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
