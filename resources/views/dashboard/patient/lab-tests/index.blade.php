@extends('layouts.app')

@section('title', 'My Lab Tests')

@section('content')
<x-patient-navigation />

<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Lab Tests</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">View and manage your laboratory test requests</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <!-- Stats Badge -->
                    <div class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                        <i class="fas fa-vial text-blue-500 dark:text-blue-400 mr-2"></i>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $labTests->total() }} Total Tests</span>
                    </div>
                    
                    <!-- Quick Actions -->
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-plus mr-2"></i>
                        Book Consultation
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <x-lab-test.filters :current-status="request('status')" />

        @if($labTests->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                @if(request('status'))
                    <x-lab-test.empty-state 
                        :title="'No ' . str_replace('_', ' ', request('status')) . ' tests found'"
                        :description="'There are no ' . str_replace('_', ' ', request('status')) . ' lab tests at the moment.'" />
                @else
                    <x-lab-test.empty-state />
                @endif
            </div>
        @else
            <!-- Lab Tests Grid -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($labTests as $labTest)
                    <x-lab-test.card :lab-test="$labTest" />
                @endforeach
            </div>

            <!-- Pagination -->
            @if($labTests->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        {{ $labTests->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add search functionality
    const searchInput = document.querySelector('input[placeholder="Search tests..."]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll('[data-test-name]');
                
                cards.forEach(card => {
                    const testName = card.dataset.testName.toLowerCase();
                    const doctor = card.dataset.doctor.toLowerCase();
                    const visible = testName.includes(searchTerm) || doctor.includes(searchTerm);
                    card.style.display = visible ? 'block' : 'none';
                });
            }, 300);
        });
    }
});
</script>
@endpush
@endsection
