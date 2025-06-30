@props(['labTest'])

<div class="space-y-6">
    <!-- Test Overview Card -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-vial text-blue-500 dark:text-blue-400 mr-3"></i>
                    Test Overview
                </h2>
                <x-lab-test.status-badge :status="$labTest->status" class="text-sm" />
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Test Name</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $labTest->test_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Requested By</label>
                        <div class="flex items-center">
                            <i class="fas fa-user-md text-blue-500 dark:text-blue-400 mr-2"></i>
                            <p class="text-gray-900 dark:text-white">
                                @if($labTest->medicalReport && $labTest->medicalReport->doctor)
                                    {{ $labTest->medicalReport->doctor->user->name }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">No doctor assigned</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($labTest->test_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Test Type</label>
                            <p class="text-gray-900 dark:text-white">{{ $labTest->test_type }}</p>
                        </div>
                    @endif

                    @if($labTest->priority && $labTest->priority !== 'normal')
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Priority</label>
                            <x-lab-test.priority-badge :priority="$labTest->priority" />
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Date Requested</label>
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-green-500 dark:text-green-400 mr-2"></i>
                            <p class="text-gray-900 dark:text-white">{{ $labTest->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($labTest->expected_date)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Expected Completion</label>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-orange-500 dark:text-orange-400 mr-2"></i>
                                <p class="text-gray-900 dark:text-white">{{ $labTest->expected_date->format('F d, Y') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($labTest->completed_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Completed On</label>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-2"></i>
                                <p class="text-gray-900 dark:text-white">{{ $labTest->completed_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Medical Report</label>
                        @if($labTest->medicalReport)
                            <a href="{{ route('patient.medical-reports.show', $labTest->medicalReport) }}" 
                               class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                <i class="fas fa-file-medical mr-2"></i>
                                {{ $labTest->medicalReport->diagnosis ?? 'View Report' }}
                                <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                            </a>
                        @else
                            <p class="text-gray-400 dark:text-gray-500">No associated report</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    @if($labTest->clinical_notes)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Clinical Notes
            </h3>
            <p class="text-blue-800 dark:text-blue-200 leading-relaxed">{{ $labTest->clinical_notes }}</p>
        </div>
    @endif

    <!-- Results Card (if completed) -->
    @if($labTest->status === 'completed')
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-file-medical text-green-500 dark:text-green-400 mr-3"></i>
                    Test Results
                </h2>
            </div>
            <div class="p-6">
                <!-- Check if we have any results to display -->
                @if($labTest->test_results || $labTest->result_notes || $labTest->results_file_path)
                    <div class="space-y-6">
                        <!-- Text Results -->
                        @if($labTest->test_results)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3 flex items-center">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Test Results
                                </h3>
                                <div class="prose dark:prose-invert max-w-none">
                                    @if(is_array($labTest->test_results))
                                        @foreach($labTest->test_results as $key => $value)
                                            <div class="mb-2">
                                                <strong class="text-green-800 dark:text-green-200">{{ ucfirst($key) }}:</strong>
                                                <span class="text-green-700 dark:text-green-300">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-green-800 dark:text-green-200 leading-relaxed whitespace-pre-wrap">{{ $labTest->test_results }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Additional Notes -->
                        @if($labTest->result_notes)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                                    <i class="fas fa-sticky-note mr-2"></i>
                                    Additional Notes
                                </h3>
                                <p class="text-blue-800 dark:text-blue-200 leading-relaxed whitespace-pre-wrap">{{ $labTest->result_notes }}</p>
                            </div>
                        @endif

                        <!-- File Download -->
                        @if($labTest->results_file_path)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-file-pdf mr-2 text-red-500"></i>
                                    Results Document
                                </h3>
                                <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Laboratory Results.pdf</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Completed {{ $labTest->completed_at ? $labTest->completed_at->format('M d, Y g:i A') : 'Recently' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ asset('storage/' . $labTest->results_file_path) }}" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            View
                                        </a>
                                        <a href="{{ asset('storage/' . $labTest->results_file_path) }}" 
                                           download
                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-download mr-2"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Results Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-check text-green-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed On</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $labTest->completed_at ? $labTest->completed_at->format('M d, Y') : 'Unknown' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($labTest->created_at && $labTest->completed_at)
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-blue-500 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Processing Time</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($labTest->created_at->diffInDays($labTest->completed_at, false), 1) }} days
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-vial text-purple-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Test Type</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $labTest->test_type ?? 'Lab Test' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No results available yet -->
                    <div class="text-center py-8">
                        <i class="fas fa-hourglass-half text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Results Processing</h3>
                        <p class="text-gray-600 dark:text-gray-400">Your test has been completed but results are still being processed. You will be notified once they are available.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4">
        @if($labTest->status === 'pending')
            <a href="{{ route('patient.lab-appointments.create', ['lab_test_request_id' => $labTest->id]) }}" 
               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-calendar-plus mr-2"></i>
                Book Lab Appointment
            </a>
        @elseif($labTest->status === 'completed' && $labTest->results_file_path)
            <a href="{{ asset('storage/' . $labTest->results_file_path) }}" 
               download
               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-download mr-2"></i>
                Download Results PDF
            </a>
        @elseif($labTest->status === 'completed')
            <div class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-medium rounded-lg cursor-not-allowed">
                <i class="fas fa-check-circle mr-2"></i>
                Test Completed
            </div>
        @elseif($labTest->status === 'scheduled')
            <div class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 font-medium rounded-lg">
                <i class="fas fa-clock mr-2"></i>
                Appointment Scheduled
            </div>
        @endif
        
        <a href="{{ route('patient.lab-tests.index') }}" 
           class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Lab Tests
        </a>
    </div>
</div>
