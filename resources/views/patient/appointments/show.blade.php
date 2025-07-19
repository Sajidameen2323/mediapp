@extends('layouts.patient')

@section('title', 'Appointment Details')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-gradient-to-r from-blue-600 to-blue-700 w-12 h-12 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                Appointment #{{ $appointment->appointment_number }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Status Badge -->
                    <span
                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                    {{ $appointment->status === 'confirmed'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                        : ($appointment->status === 'pending'
                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'
                            : ($appointment->status === 'cancelled'
                                ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                                : ($appointment->status === 'completed'
                                    ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400'
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400'))) }}">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ ucfirst($appointment->status) }}
                    </span>

                    <!-- Back Button -->
                    <a href="{{ route('patient.appointments.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Appointment Overview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                            Appointment Overview
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date & Time -->
                            <div class="space-y-4">
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-calendar text-blue-600 dark:text-blue-400 mr-2"></i>
                                        <h3 class="font-medium text-gray-900 dark:text-white">Date & Time</h3>
                                    </div>
                                    <p class="text-lg font-semibold text-blue-900 dark:text-blue-300">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->setTimezone($config->timezone ?? 'UTC')->format('l, F d, Y') }}
                                    </p>
                                    <p class="text-blue-700 dark:text-blue-400">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                        @if ($appointment->end_time)
                                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                                        Duration:
                                        {{ $appointment->duration_minutes ?? ($appointment->service->duration_minutes ?? 30) }}
                                        minutes
                                    </p>
                                </div>
                            </div>

                            <!-- Type & Priority -->
                            <div class="space-y-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-tag text-gray-600 dark:text-gray-400 mr-2"></i>
                                        <h3 class="font-medium text-gray-900 dark:text-white">Type & Priority</h3>
                                    </div>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->appointment_type)) }}
                                    </p>
                                    <div class="mt-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $appointment->priority === 'high' || $appointment->priority === 'urgent'
                                            ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
                                            : ($appointment->priority === 'medium'
                                                ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'
                                                : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400') }}">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ ucfirst($appointment->priority) }} Priority
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <i class="fas fa-user-md text-green-600 mr-3"></i>
                            Doctor Information
                        </h2>

                        <div class="flex items-start space-x-4">
                            <div
                                class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/20 dark:to-green-800/20 w-16 h-16 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $appointment->doctor->user->name }}
                                </h3>
                                @php
                                    $doctorProfile = $appointment->doctor->user->profile();
                                @endphp
                                @if (!empty($doctorProfile) && isset($doctorProfile['specialization']))
                                    <p class="text-green-600 dark:text-green-400 font-medium">
                                        {{ $doctorProfile['specialization'] }}
                                    </p>
                                @endif
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                        <i class="fas fa-envelope w-4 mr-2"></i>
                                        {{ $appointment->doctor->user->email }}
                                    </p>
                                    @if (!empty($doctorProfile) && isset($doctorProfile['phone']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                            <i class="fas fa-phone w-4 mr-2"></i>
                                            {{ $doctorProfile['phone'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <i class="fas fa-stethoscope text-purple-600 mr-3"></i>
                            Service Information
                        </h2>

                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-200">
                                        {{ $appointment->service->name }}
                                    </h3>
                                    @if ($appointment->service->description)
                                        <p class="text-purple-700 dark:text-purple-300 mt-1 text-sm">
                                            {{ $appointment->service->description }}
                                        </p>
                                    @endif
                                    <div class="mt-3 flex items-center space-x-4 text-sm">
                                        <span class="flex items-center text-purple-600 dark:text-purple-400">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $appointment->service->duration_minutes ?? 30 }} minutes
                                        </span>
                                        @if ($appointment->service->price)
                                            <span class="flex items-center text-purple-600 dark:text-purple-400">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                ${{ number_format($appointment->service->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <i class="fas fa-clipboard-list text-orange-600 mr-3"></i>
                            Appointment Details
                        </h2>

                        <div class="space-y-4">
                            <!-- Reason -->
                            <div class="border-l-4 border-orange-400 pl-4">
                                <h3 class="font-medium text-gray-900 dark:text-white mb-1">Reason for Visit</h3>
                                <p class="text-gray-700 dark:text-gray-300">{{ $appointment->reason }}</p>
                            </div>

                            <!-- Symptoms -->
                            @if ($appointment->symptoms)
                                <div class="border-l-4 border-red-400 pl-4">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-1">Symptoms</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->symptoms }}</p>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if ($appointment->notes)
                                <div class="border-l-4 border-blue-400 pl-4">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-1">Additional Notes</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->notes }}</p>
                                </div>
                            @endif

                            <!-- Special Instructions -->
                            @if ($appointment->special_instructions)
                                <div class="border-l-4 border-indigo-400 pl-4">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-1">Special Instructions</h3>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->special_instructions }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Doctor's Notes & Medical Information -->
                @if ($appointment->doctor_notes || $appointment->prescription || $appointment->follow_up_instructions)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fas fa-notes-medical text-teal-600 mr-3"></i>
                                Medical Information
                            </h2>

                            <div class="space-y-6">
                                @if ($appointment->doctor_notes)
                                    <div
                                        class="bg-teal-50 dark:bg-teal-900/20 p-4 rounded-lg border border-teal-200 dark:border-teal-700">
                                        <h3 class="font-medium text-teal-900 dark:text-teal-200 mb-2 flex items-center">
                                            <i class="fas fa-user-md mr-2"></i>
                                            Doctor's Notes
                                        </h3>
                                        <p class="text-teal-800 dark:text-teal-300">{{ $appointment->doctor_notes }}</p>
                                    </div>
                                @endif

                                @if ($appointment->prescription)
                                    <div
                                        class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-700">
                                        <h3 class="font-medium text-green-900 dark:text-green-200 mb-2 flex items-center">
                                            <i class="fas fa-prescription-bottle-alt mr-2"></i>
                                            Prescription
                                        </h3>
                                        <p class="text-green-800 dark:text-green-300">{{ $appointment->prescription }}</p>
                                    </div>
                                @endif

                                @if ($appointment->follow_up_instructions)
                                    <div
                                        class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                        <h3 class="font-medium text-yellow-900 dark:text-yellow-200 mb-2 flex items-center">
                                            <i class="fas fa-redo mr-2"></i>
                                            Follow-up Instructions
                                        </h3>
                                        <p class="text-yellow-800 dark:text-yellow-300">
                                            {{ $appointment->follow_up_instructions }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6"> <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                            Quick Actions
                        </h3>

                        <div class="space-y-3">                            <x-appointment.action-buttons :appointment="$appointment" layout="vertical" size="md"
                                :show-print="true" :show-rating="$appointment->canBeRated()" cancel-action="showCancelModal()"
                                rating-action="showRatingModal('ratingModal_{{ $appointment->id }}')" />
                        </div>
                    </div>
                </div>
                
                <!-- Payment Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-credit-card text-green-500 mr-2"></i>
                            Payment Details
                        </h3>
                        
                        @php
                            $paymentStatus = $appointment->payment_status ?? 'unpaid';
                            $paidAmount = $appointment->paid_amount ?? 0;
                            $remainingAmount = ($appointment->total_amount ?? 0) - $paidAmount;
                            $isFullyPaid = $remainingAmount <= 0;
                        @endphp
                        
                        <div class="space-y-4">
                            <!-- Payment Status Badge -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $paymentStatus === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 
                                       ($paymentStatus === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : 
                                       'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400') }}">
                                    <i class="fas {{ $paymentStatus === 'paid' ? 'fa-check-circle' : 
                                                     ($paymentStatus === 'partial' ? 'fa-dot-circle' : 'fa-times-circle') }} mr-1"></i>
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </div>
                            
                            <!-- Amount Information -->
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="text-gray-600 dark:text-gray-400">Total Amount</div>
                                <div class="text-right font-medium text-gray-900 dark:text-white">${{ number_format($appointment->total_amount ?? 0, 2) }}</div>
                                
                                @if($paidAmount > 0)
                                <div class="text-gray-600 dark:text-gray-400">Paid Amount</div>
                                <div class="text-right font-medium text-green-600 dark:text-green-400">${{ number_format($paidAmount, 2) }}</div>
                                
                                <div class="text-gray-600 dark:text-gray-400">Remaining</div>
                                <div class="text-right font-medium {{ $isFullyPaid ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    ${{ number_format($remainingAmount, 2) }}
                                </div>
                                @endif
                                
                                @if($appointment->tax_amount > 0)
                                <div class="text-gray-600 dark:text-gray-400">Tax ({{ $appointment->tax_percentage }}%)</div>
                                <div class="text-right font-medium text-gray-500 dark:text-gray-400">${{ number_format($appointment->tax_amount, 2) }}</div>
                                @endif
                            </div>
                            
                            <!-- Payment Action Button -->
                            @if(!$isFullyPaid)
                            <button type="button" onclick="showPaymentModal({{ $remainingAmount }}, {{ $appointment->id }}, 'Pay for Appointment')" 
                                class="w-full mt-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-200 dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                <i class="fas fa-credit-card mr-2"></i>
                                {{ $paymentStatus === 'partial' ? 'Pay Remaining Balance' : 'Pay Now' }}
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Payment History -->
                @php
                    // Check if the payments table exists
                    $paymentsTableExists = \Illuminate\Support\Facades\Schema::hasTable('payments');
                    $payments = collect([]);
                    
                    if ($paymentsTableExists) {
                        $payments = \App\Models\Payment::where('appointment_id', $appointment->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                    }
                @endphp
                
                @if($paymentsTableExists && $payments->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-history text-purple-500 mr-2"></i>
                            Payment History
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($payments as $payment)
                            <div class="border-b border-gray-100 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-gray-800 dark:text-gray-200 font-medium">${{ number_format($payment->amount, 2) }}</span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $payment->created_at->format('M d, Y g:i A') }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                            {{ $payment->status }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $payment->formatted_payment_method }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Appointment Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-history text-indigo-500 mr-2"></i>
                            Timeline
                        </h3>

                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @php
                                    // Create chronological timeline events
                                    $events = collect();
                                    
                                    // Appointment Booked
                                    $events->push([
                                        'type' => 'booked',
                                        'title' => 'Appointment Booked',
                                        'subtitle' => 'by ' . $appointment->patient->name,
                                        'datetime' => $appointment->created_at,
                                        'icon' => 'fas fa-plus',
                                        'color' => 'blue',
                                        'description' => null
                                    ]);
                                    
                                    // Rescheduled
                                    if ($appointment->rescheduled_at) {
                                        $events->push([
                                            'type' => 'rescheduled',
                                            'title' => 'Appointment Rescheduled',
                                            'subtitle' => 'by ' . $appointment->rescheduled_by,
                                            'datetime' => \Carbon\Carbon::parse($appointment->rescheduled_at),
                                            'icon' => 'fas fa-calendar-alt',
                                            'color' => 'yellow',
                                            'description' => $appointment->reschedule_reason
                                        ]);
                                    }
                                    
                                    // Cancelled
                                    if ($appointment->cancelled_at) {
                                        $events->push([
                                            'type' => 'cancelled',
                                            'title' => 'Appointment Cancelled',
                                            'subtitle' => 'by ' . $appointment->cancelled_by,
                                            'datetime' => \Carbon\Carbon::parse($appointment->cancelled_at),
                                            'icon' => 'fas fa-times',
                                            'color' => 'red',
                                            'description' => $appointment->cancellation_reason
                                        ]);
                                    }
                                    
                                    // Confirmed
                                    if ($appointment->confirmed_at) {
                                        $events->push([
                                            'type' => 'confirmed',
                                            'title' => 'Appointment Confirmed',
                                            'subtitle' => 'by doctor',
                                            'datetime' => \Carbon\Carbon::parse($appointment->confirmed_at),
                                            'icon' => 'fas fa-check',
                                            'color' => 'green',
                                            'description' => null
                                        ]);
                                    }
                                    
                                    // Completed
                                    if ($appointment->completed_at) {
                                        $events->push([
                                            'type' => 'completed',
                                            'title' => 'Appointment Completed',
                                            'subtitle' => 'by doctor',
                                            'datetime' => \Carbon\Carbon::parse($appointment->completed_at),
                                            'icon' => 'fas fa-clipboard-check',
                                            'color' => 'indigo',
                                            'description' => null
                                        ]);
                                    }
                                    
                                    // Sort by datetime
                                    $events = $events->sortBy('datetime');
                                @endphp

                                @foreach ($events as $index => $event)
                                    <li>
                                        <div class="relative {{ $loop->last ? '' : 'pb-8' }}">
                                            @if (!$loop->last)
                                                <span
                                                    class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div
                                                    class="bg-{{ $event['color'] }}-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <i class="{{ $event['icon'] }} text-white text-xs"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-white font-medium">
                                                            {{ $event['title'] }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $event['subtitle'] }}
                                                        </p>
                                                        @if ($event['description'])
                                                            <p class="text-xs text-{{ $event['color'] }}-600 dark:text-{{ $event['color'] }}-400 mt-1">
                                                                {{ $event['description'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $event['datetime']->setTimezone($config->timezone ?? 'UTC')->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Policies Information -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700 p-4">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Appointment Policies
                    </h3>
                    <div class="space-y-2 text-xs text-blue-800 dark:text-blue-300">
                        @if ($config->allow_cancellation)
                            <p class="flex items-center">
                                <i class="fas fa-clock mr-2 w-3"></i>
                                Cancel: {{ $config->cancellation_hours_limit }}h before
                            </p>
                        @endif
                        @if ($config->allow_rescheduling)
                            <p class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2 w-3"></i>
                                Reschedule: {{ $config->reschedule_hours_limit }}h before
                            </p>
                        @endif
                        <p class="flex items-center">
                            <i class="fas fa-dollar-sign mr-2 w-3"></i>
                            Total: ${{ number_format($appointment->total_amount, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white text-center mt-5">Cancel Appointment</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                        Please provide a reason for cancelling this appointment. This action cannot be undone.
                    </p>
                </div>

                <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" id="cancelForm">
                    @csrf
                    @method('PATCH')
                    <div class="px-7 py-3">
                        <label for="cancellation_reason"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cancellation Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                            placeholder="Please explain why you need to cancel this appointment..."></textarea>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 10 characters required</p>
                    </div>

                    <div class="px-4 py-3 text-center space-x-3">
                        <button type="submit"
                            class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel Appointment
                        </button>
                        <button type="button" onclick="hideCancelModal()"
                            class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>    <!-- Rating Modal -->
    @if ($appointment->canBeRated())
        <x-appointment.rating-modal :appointment="$appointment" modal-id="ratingModal_{{ $appointment->id }}" />
    @endif
    
    <!-- Payment Modal -->
    <x-payment-modal id="payment-modal" :appointment-id="$appointment->id" />
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e9ecef;
        }

        .timeline-content h6 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 12px;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-1px);
        }        .badge.fs-6 {
            font-size: 1rem !important;
            padding: 0.5rem 1rem;
        }

        @media print {

            .btn,
            .modal,
            .card-header {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/payment-modal-helper.js') }}"></script>
    <script>
        // Modal Functions
        function showCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reset form
            document.getElementById('cancellation_reason').value = '';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const cancelModal = document.getElementById('cancelModal');

            if (event.target === cancelModal) {
                hideCancelModal();
            }
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const cancelModal = document.getElementById('cancelModal');

                if (!cancelModal.classList.contains('hidden')) {
                    hideCancelModal();
                }
            }
        });
        
        // Payment modal success callback
        document.addEventListener('payment:success', function(e) {
            if (e.detail && e.detail.appointmentId == {{ $appointment->id }}) {
                // Reload the page to show updated payment status
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });
    </script>
@endpush
