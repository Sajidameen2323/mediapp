@extends('layouts.app')

@section('title', 'Doctor Dashboard - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Doctor Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage your patients and medical practice</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">My Patients</h3>
                <p class="mt-2 text-sm text-gray-600">View and manage your patients</p>
                <div class="mt-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        View Patients
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Appointments</h3>
                <p class="mt-2 text-sm text-gray-600">Manage your appointments</p>
                <div class="mt-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        View Schedule
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Prescriptions</h3>
                <p class="mt-2 text-sm text-gray-600">Create and manage prescriptions</p>
                <div class="mt-4">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Manage Prescriptions
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Medical Records</h3>
                <p class="mt-2 text-sm text-gray-600">Access patient medical records</p>
                <div class="mt-4">
                    <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        View Records
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Lab Results</h3>
                <p class="mt-2 text-sm text-gray-600">Review patient lab results</p>
                <div class="mt-4">
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        View Results
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reports</h3>
                <p class="mt-2 text-sm text-gray-600">Generate medical reports</p>
                <div class="mt-4">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Generate Reports
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
