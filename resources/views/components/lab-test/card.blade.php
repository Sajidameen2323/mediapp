@props(['labTest'])

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl dark:hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1"
     data-test-name="{{ $labTest->test_name }}"
     data-doctor="{{ $labTest->medicalReport && $labTest->medicalReport->doctor ? $labTest->medicalReport->doctor->user->name : 'No doctor assigned' }}">
    <!-- Card Header -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ $labTest->test_name }}
                </h3>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-user-md mr-2 text-blue-500 dark:text-blue-400"></i>
                    @if($labTest->medicalReport && $labTest->medicalReport->doctor)
                        {{ $labTest->medicalReport->doctor->user->name }}
                    @else
                        <span class="text-gray-400 dark:text-gray-500">No doctor assigned</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-end space-y-2">
                <x-lab-test.status-badge :status="$labTest->status" />
                @if($labTest->priority && $labTest->priority !== 'normal')
                    <x-lab-test.priority-badge :priority="$labTest->priority" />
                @endif
            </div>
        </div>
    </div>

    <!-- Card Content -->
    <div class="p-6 space-y-4">
        <!-- Test Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Report Diagnosis -->
            @if($labTest->medicalReport)
                <div class="space-y-1">
                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        <i class="fas fa-file-medical mr-1"></i>
                        Report
                    </div>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $labTest->medicalReport->diagnosis ?? 'General Consultation' }}
                    </p>
                </div>
            @endif

            <!-- Test Type -->
            @if($labTest->test_type)
                <div class="space-y-1">
                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        <i class="fas fa-vial mr-1"></i>
                        Type
                    </div>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labTest->test_type }}</p>
                </div>
            @endif

            <!-- Date Requested -->
            <div class="space-y-1">
                <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <i class="fas fa-calendar mr-1"></i>
                    Requested
                </div>
                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labTest->created_at->format('M d, Y') }}</p>
            </div>

            <!-- Expected Date -->
            @if($labTest->expected_date)
                <div class="space-y-1">
                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        <i class="fas fa-clock mr-1"></i>
                        Expected
                    </div>
                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $labTest->expected_date->format('M d, Y') }}</p>
                </div>
            @endif
        </div>

        <!-- Instructions -->
        @if($labTest->instructions)
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Instructions</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-200">{{ Str::limit($labTest->instructions, 150) }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Card Footer -->
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-eye mr-2"></i>
                View Details
            </a>
            
            <div class="flex items-center space-x-3">
                @if($labTest->status === 'pending')
                    <a href="{{ route('patient.lab-appointments.create', ['lab_test_request_id' => $labTest->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Book Appointment
                    </a>
                @elseif($labTest->status === 'scheduled')
                    @php
                        $hasConfirmedAppointment = $labTest->labAppointments()->where('status', 'confirmed')->exists();
                        $latestAppointment = $labTest->labAppointments()->latest()->first();
                    @endphp
                    
                    @if($hasConfirmedAppointment)
                        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Appointment Confirmed
                        </span>
                    @elseif($latestAppointment && $latestAppointment->status === 'pending')
                        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <i class="fas fa-clock mr-2"></i>
                            Awaiting Confirmation
                        </span>
                    @else
                        <a href="{{ route('patient.lab-appointments.create', ['lab_test_request_id' => $labTest->id]) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Book Appointment
                        </a>
                    @endif
                @elseif($labTest->status === 'completed')
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="fas fa-check-circle mr-2"></i>
                        Completed
                    </span>
                @elseif($labTest->status === 'cancelled')
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <i class="fas fa-times-circle mr-2"></i>
                        Cancelled
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
