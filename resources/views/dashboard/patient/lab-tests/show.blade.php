@extends('layouts.app')

@section('title', 'Lab Test Details - ' . $labTest->test_name)

@section('content')
<x-patient-navigation />

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('patient.dashboard') }}" 
                           class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors duration-200">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.lab-tests.index') }}" 
                               class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                Lab Tests
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $labTest->test_name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $labTest->test_name }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Lab test details and information</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <x-lab-test-status-badge :status="$labTest->status" class="text-base px-4 py-2" />
                    @if($labTest->priority && $labTest->priority !== 'normal')
                        <x-lab-test-priority-badge :priority="$labTest->priority" class="text-base px-3 py-2" />
                    @endif
                </div>
            </div>
        </div>

        <!-- Lab Test Details -->
        <x-lab-test-details :lab-test="$labTest" />
    </div>
</div>
@endsection
