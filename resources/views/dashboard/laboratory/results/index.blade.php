@extends('layouts.laboratory')

@section('title', 'Test Results')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Test Results Management</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Release and manage laboratory test results for patients.</p>
    </div>

    <!-- Coming Soon Notice -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/30 mb-4">
                <i class="fas fa-file-medical text-2xl text-blue-600 dark:text-blue-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Test Results Management</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                This section will allow you to release and manage laboratory test results for your patients. 
                Features will include result upload, patient notifications, and result history.
            </p>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">Upcoming Features:</h4>
                <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                    <li>• Upload and attach test result files</li>
                    <li>• Send automatic notifications to patients</li>
                    <li>• Track result delivery status</li>
                    <li>• Generate printable reports</li>
                    <li>• Manage result templates</li>
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('laboratory.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
