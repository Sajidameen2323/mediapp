@extends('layouts.app')

@section('title', 'My Medical Reports')

@section('content')
    <x-patient-navigation />

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Medical Reports</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-300">View your medical consultation reports and history
                        </p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex items-center space-x-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                            <i class="fas fa-file-medical mr-2"></i>
                            {{ $medicalReports->count() }} Reports
                        </span>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                    <!-- Search Bar -->
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchReports"
                                placeholder="Search by diagnosis, doctor, or symptoms..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400">
                        </div>
                    </div>

                    <!-- Filter Options -->
                    <div class="flex flex-wrap items-center space-x-4">
                        <select id="doctorFilter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Doctors</option>
                            @foreach ($medicalReports->pluck('doctor.user.name')->unique() as $doctorName)
                                <option value="{{ $doctorName }}">{{ $doctorName }}</option>
                            @endforeach
                        </select>

                        <select id="dateFilter"
                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="3months">Last 3 Months</option>
                            <option value="year">This Year</option>
                        </select>

                        <button id="clearFilters"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            <i class="fas fa-times mr-1"></i>
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            @if ($medicalReports->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto h-32 w-32 text-gray-400 dark:text-gray-500 mb-6">
                        <i class="fas fa-file-medical-alt text-8xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No medical reports yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">Your medical consultation reports will
                        appear here after doctor visits. Book an appointment to get started.</p>
                    <a href="{{ route('patient.appointments.create-enhanced') }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Book Appointment
                    </a>
                </div>
            @else
                <!-- Results Info -->
                <div class="mb-6 flex items-center justify-between">
                    <div id="resultsInfo" class="text-sm text-gray-600 dark:text-gray-400">
                        Showing <span id="visibleCount">{{ $medicalReports->count() }}</span> of
                        {{ $medicalReports->count() }} reports
                    </div>
                    <div class="flex items-center space-x-2">
                        <button id="gridView"
                            class="p-2 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-200">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button id="listView"
                            class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
                <!-- Medical Reports Grid -->
                <div id="reportsContainer" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-view-mode="grid">
                    @foreach ($medicalReports as $report)
                        <div class="report-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-gray-900/25 transition-all duration-300 hover:scale-[1.02]"
                            data-report-id="{{ $report->id }}" 
                            data-doctor="{{ $report->doctor->user->name }}"
                            data-diagnosis="{{ $report->diagnosis ?? '' }}" data-symptoms="{{ $report->symptoms ?? '' }}"
                            data-treatment="{{ $report->treatment ?? '' }}"
                            data-date="{{ $report->created_at->format('Y-m-d') }}">

                            <!-- Card Header with Report Title -->
                            <div
                                class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 leading-tight">
                                            <i class="fas fa-stethoscope text-blue-600 dark:text-blue-400 mr-2"></i>
                                            {{ $report->diagnosis ?? 'General Consultation' }}
                                        </h3>
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                            <i class="fas fa-user-md mr-1"></i>
                                            <span class="font-medium">{{ $report->doctor->user->name }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $report->created_at->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Content -->
                            <div class="px-6 py-5">
                                <div class="space-y-4">
                                    <!-- Appointment Info -->
                                    @if ($report->appointment)
                                        <div class="flex items-center text-sm">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-800 rounded-lg mr-3">
                                                <i
                                                    class="fas fa-calendar-check text-green-600 dark:text-green-300 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">Appointment Date</p>
                                                <p class="text-gray-600 dark:text-gray-300">
                                                    {{ $report->appointment->appointment_date->format('M d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Symptoms -->
                                    @if ($report->symptoms)
                                        <div class="flex items-start text-sm">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-orange-100 dark:bg-orange-800 rounded-lg mr-3 mt-0.5">
                                                <i
                                                    class="fas fa-thermometer-half text-orange-600 dark:text-orange-300 text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white">Symptoms</p>
                                                <p class="text-gray-600 dark:text-gray-300 line-clamp-2">
                                                    {{ Str::limit($report->symptoms, 100) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Treatment -->
                                    @if ($report->treatment)
                                        <div class="flex items-start text-sm">
                                            <div
                                                class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-800 rounded-lg mr-3 mt-0.5">
                                                <i class="fas fa-pills text-purple-600 dark:text-purple-300 text-xs"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900 dark:text-white">Treatment</p>
                                                <p class="text-gray-600 dark:text-gray-300 line-clamp-2">
                                                    {{ Str::limit($report->treatment, 100) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Stats -->
                                    <div
                                        class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center space-x-4 text-sm">
                                            @if ($report->prescriptions_count > 0)
                                                <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                                    <i class="fas fa-prescription-bottle-alt mr-1"></i>
                                                    {{ $report->prescriptions_count }} Rx
                                                </span>
                                            @endif
                                            @if ($report->lab_test_requests_count > 0)
                                                <span class="inline-flex items-center text-purple-600 dark:text-purple-400">
                                                    <i class="fas fa-flask mr-1"></i>
                                                    {{ $report->lab_test_requests_count }} Labs
                                                </span>
                                            @endif
                                            @if ($report->prescriptions_count == 0 && $report->lab_test_requests_count == 0)
                                                <span class="text-gray-500 dark:text-gray-400 text-xs italic">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    Consultation only
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $report->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div
                                class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex flex-col space-y-3">
                                    <!-- Primary Action Button -->
                                    <a href="{{ route('patient.medical-reports.show', $report) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Full Report
                                    </a>                                    
                                    <!-- Secondary Action Buttons -->
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        {{-- <!-- Access Management Button -->
                                        <a href="{{ route('patient.medical-reports.access.index', $report) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-800/30 hover:bg-blue-100 dark:hover:bg-blue-800/50 rounded-md border border-blue-200 dark:border-blue-700 transition-colors duration-200">
                                            <i class="fas fa-user-shield mr-1.5 text-sm"></i>
                                            <span>Manage Access</span>
                                        </a> --}}
                                        
                                        @if ($report->prescriptions_count > 0)
                                            <a href="{{ route('patient.prescriptions.index', ['medical_report' => $report->id]) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-800/30 hover:bg-green-100 dark:hover:bg-green-800/50 rounded-md border border-green-200 dark:border-green-700 transition-colors duration-200">
                                                <i class="fas fa-prescription-bottle-alt mr-1.5 text-sm"></i>
                                                <span>Prescriptions ({{ $report->prescriptions_count }})</span>
                                            </a>
                                        @endif
                                        @if ($report->lab_test_requests_count > 0)
                                            <a href="{{ route('patient.lab-tests.index', ['medical_report' => $report->id]) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-800/30 hover:bg-purple-100 dark:hover:bg-purple-800/50 rounded-md border border-purple-200 dark:border-purple-700 transition-colors duration-200">
                                                <i class="fas fa-flask mr-1.5 text-sm"></i>
                                                <span>Lab Tests ({{ $report->lab_test_requests_count }})</span>
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if ($report->prescriptions_count == 0 && $report->lab_test_requests_count == 0)
                                        <div class="text-center py-2">
                                            <span
                                                class="inline-flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-info-circle mr-1.5"></i>
                                                Consultation only - No prescriptions or lab tests
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="hidden text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-search text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No reports found</h3>
                    <p class="text-gray-500 dark:text-gray-400">Try adjusting your search criteria or filters.</p>
                </div>

                <!-- Pagination -->
                @if ($medicalReports->hasPages())
                    <div class="mt-8">
                        {{ $medicalReports->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- JavaScript for Search and Filter Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchReports');
            const doctorFilter = document.getElementById('doctorFilter');
            const dateFilter = document.getElementById('dateFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const reportsContainer = document.getElementById('reportsContainer');
            const noResults = document.getElementById('noResults');
            const visibleCount = document.getElementById('visibleCount');
            const gridViewBtn = document.getElementById('gridView');
            const listViewBtn = document.getElementById('listView');
            let allReports = Array.from(document.querySelectorAll('.report-card'));
            const totalReports = allReports.length;

            // Load saved view preference
            const savedViewMode = localStorage.getItem('medical-reports-view-mode') || 'grid';
            if (savedViewMode === 'list') {
                setListView();
            } else {
                setGridView();
            }

            // Search functionality
            function filterReports() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedDoctor = doctorFilter.value;
                const selectedDateRange = dateFilter.value;

                let visibleReports = 0;

                allReports.forEach(card => {
                    const doctor = card.getAttribute('data-doctor').toLowerCase();
                    const diagnosis = card.getAttribute('data-diagnosis').toLowerCase();
                    const symptoms = card.getAttribute('data-symptoms').toLowerCase();
                    const treatment = card.getAttribute('data-treatment').toLowerCase();
                    const reportDate = new Date(card.getAttribute('data-date'));

                    // Search filter
                    const matchesSearch = !searchTerm ||
                        diagnosis.includes(searchTerm) ||
                        doctor.includes(searchTerm) ||
                        symptoms.includes(searchTerm) ||
                        treatment.includes(searchTerm);

                    // Doctor filter
                    console.log(selectedDoctor, " - ", doctor);

                    const matchesDoctor = !selectedDoctor || doctor.includes(selectedDoctor.toLowerCase());

                    // Date filter
                    let matchesDate = true;
                    if (selectedDateRange) {
                        const now = new Date();
                        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                        switch (selectedDateRange) {
                            case 'today':
                                matchesDate = reportDate >= today;
                                break;
                            case 'week':
                                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                                matchesDate = reportDate >= weekAgo;
                                break;
                            case 'month':
                                const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today
                                    .getDate());
                                matchesDate = reportDate >= monthAgo;
                                break;
                            case '3months':
                                const threeMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 3,
                                    today.getDate());
                                matchesDate = reportDate >= threeMonthsAgo;
                                break;
                            case 'year':
                                const yearAgo = new Date(today.getFullYear() - 1, today.getMonth(), today
                                    .getDate());
                                matchesDate = reportDate >= yearAgo;
                                break;
                        }
                    }

                    if (matchesSearch && matchesDoctor && matchesDate) {
                        card.style.display = 'block';
                        visibleReports++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                // Update results info
                visibleCount.textContent = visibleReports;

                // Show/hide no results message
                if (visibleReports === 0) {
                    reportsContainer.style.display = 'none';
                    noResults.classList.remove('hidden');
                } else {
                    reportsContainer.style.display = 'grid';
                    noResults.classList.add('hidden');
                    // Maintain current view mode after filtering
                    const currentViewMode = reportsContainer.getAttribute('data-view-mode');
                    if (currentViewMode === 'list') {
                        reportsContainer.className = 'space-y-4';
                        // Apply list view classes to visible cards
                        allReports.forEach(card => {
                            if (card.style.display !== 'none') {
                                card.classList.add('lg:flex', 'lg:items-center');
                            } else {
                                card.classList.remove('lg:flex', 'lg:items-center');
                            }
                        });
                    } else {
                        reportsContainer.className = 'grid gap-6 md:grid-cols-2 lg:grid-cols-3';
                        // Remove list view classes from all cards
                        allReports.forEach(card => {
                            card.classList.remove('lg:flex', 'lg:items-center');
                        });
                    }
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterReports);
            doctorFilter.addEventListener('change', filterReports);
            dateFilter.addEventListener('change', filterReports);

            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                doctorFilter.value = '';
                dateFilter.value = '';
                filterReports();
            }); // View toggle functionality
            function setGridView() {
                reportsContainer.className = 'grid gap-6 md:grid-cols-2 lg:grid-cols-3';
                reportsContainer.setAttribute('data-view-mode', 'grid');
                localStorage.setItem('medical-reports-view-mode', 'grid');

                // Remove list view classes from all cards
                allReports.forEach(card => {
                    card.classList.remove('lg:flex', 'lg:items-center');
                });

                // Update button states
                gridViewBtn.classList.add('bg-blue-100', 'text-blue-600', 'dark:bg-blue-800', 'dark:text-blue-200');
                gridViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600', 'dark:text-gray-500',
                    'dark:hover:text-gray-300');
                listViewBtn.classList.remove('bg-blue-100', 'text-blue-600', 'dark:bg-blue-800',
                    'dark:text-blue-200');
                listViewBtn.classList.add('text-gray-400', 'hover:text-gray-600', 'dark:text-gray-500',
                    'dark:hover:text-gray-300');
            }

            function setListView() {
                reportsContainer.className = 'space-y-4';
                reportsContainer.setAttribute('data-view-mode', 'list');
                localStorage.setItem('medical-reports-view-mode', 'list');

                // Add list view classes to visible cards
                allReports.forEach(card => {
                    if (card.style.display !== 'none') {
                        card.classList.add('lg:flex', 'lg:items-center');
                    }
                });

                // Update button states
                listViewBtn.classList.add('bg-blue-100', 'text-blue-600', 'dark:bg-blue-800', 'dark:text-blue-200');
                listViewBtn.classList.remove('text-gray-400', 'hover:text-gray-600', 'dark:text-gray-500',
                    'dark:hover:text-gray-300');
                gridViewBtn.classList.remove('bg-blue-100', 'text-blue-600', 'dark:bg-blue-800',
                    'dark:text-blue-200');
                gridViewBtn.classList.add('text-gray-400', 'hover:text-gray-600', 'dark:text-gray-500',
                    'dark:hover:text-gray-300');
            }

            // Event listeners for view toggle
            gridViewBtn.addEventListener('click', setGridView);
            listViewBtn.addEventListener('click', setListView);
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .report-card {
            transition: all 0.3s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
        }

        /* Button consistency improvements */
        .report-card .inline-flex {
            min-height: 40px;
            /* Ensure consistent button height */
        }

        /* Ensure buttons maintain consistent sizing */
        @media (min-width: 640px) {
            .report-card .inline-flex {
                white-space: nowrap;
            }
        }

        /* Improved button alignment for mobile */
        @media (max-width: 639px) {
            .report-card .flex-col>.flex {
                justify-content: stretch;
            }

            .report-card .flex-col>.flex>a,
            .report-card .flex-col>.flex>div {
                flex: 1;
                text-align: center;
            }
        }

        /* List view specific styling */
        .report-card.lg\:flex {
            transition: all 0.3s ease;
        }

        @media (min-width: 1024px) {
            .report-card.lg\:flex {
                flex-direction: row;
                align-items: stretch;
            }

            .report-card.lg\:flex .px-6.py-5:first-of-type {
                flex: 0 0 300px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .report-card.lg\:flex .px-6.py-5:not(:first-of-type) {
                flex: 1;
            }

            .report-card.lg\:flex .px-6.py-4 {
                flex: 0 0 auto;
                display: flex;
                align-items: center;
                border-left: 1px solid;
                border-top: none;
            }
        }

        /* Ensure proper reset of styles when switching views */
        .report-card:not(.lg\:flex) {
            flex-direction: column;
        }

        .report-card:not(.lg\:flex) .px-6 {
            flex: none;
        }
    </style>
    </style>

@endsection
