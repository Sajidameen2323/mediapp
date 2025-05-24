@extends('layouts.app')

@section('title', 'Laboratory Dashboard - Medi App')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laboratory Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage lab tests and results</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Tests</h3>
                <p class="mt-2 text-sm text-gray-600">Tests waiting to be processed</p>
                <div class="mt-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        View Pending
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add Test Results</h3>
                <p class="mt-2 text-sm text-gray-600">Enter new test results</p>
                <div class="mt-4">
                    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Add Results
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Test History</h3>
                <p class="mt-2 text-sm text-gray-600">View completed tests</p>
                <div class="mt-4">
                    <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        View History
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Sample Management</h3>
                <p class="mt-2 text-sm text-gray-600">Track sample collection</p>
                <div class="mt-4">
                    <button class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                        Manage Samples
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Equipment Status</h3>
                <p class="mt-2 text-sm text-gray-600">Monitor lab equipment</p>
                <div class="mt-4">
                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Check Equipment
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reports</h3>
                <p class="mt-2 text-sm text-gray-600">Generate lab reports</p>
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
