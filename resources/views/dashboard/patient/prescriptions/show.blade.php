@extends('layouts.app')

@section('title', 'Prescription Details')

@section('content')
    <x-patient-navigation />

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="max-w-6xl mx-auto py-2 sm:px-6 lg:px-8">
            <div class="px-4 sm:px-0">
                <div class="rounded-md bg-green-50 dark:bg-green-900 p-4 border border-green-200 dark:border-green-700">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 dark:text-green-300"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-6xl mx-auto py-2 sm:px-6 lg:px-8">
            <div class="px-4 sm:px-0">
                <div class="rounded-md bg-red-50 dark:bg-red-900 p-4 border border-red-200 dark:border-red-700">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 dark:text-red-300"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-4">
                                <li>
                                    <a href="{{ route('patient.prescriptions.index') }}"
                                        class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-300">
                                        <i class="fas fa-prescription-bottle-alt"></i>
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                                        <a href="{{ route('patient.prescriptions.index') }}"
                                            class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Prescriptions</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                            aria-current="page">Prescription
                                            #{{ $prescription->prescription_number ?? $prescription->id }}</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Prescription Details</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your medication prescription</p>
                    </div>
                    <div class="mt-4 lg:mt-0 flex flex-wrap items-center gap-3">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prescription->getStatusBadgeColor() }}">
                            <i class="fas fa-circle mr-2" style="font-size: 0.5rem;"></i>
                            {{ ucfirst($prescription->status) }}
                        </span>

                        @if ($prescription->hasRefillsRemaining())
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200">
                                <i class="fas fa-redo mr-1"></i>
                                {{ $prescription->refills_remaining }} refills left
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Prescription Overview -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-info-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                                Prescription Overview
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-md text-blue-600 dark:text-blue-300 text-sm"></i>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prescribed by</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                            @if ($prescription->doctor && $prescription->doctor->user)
                                                {{ $prescription->doctor->user->name }}
                                            @elseif($prescription->medicalReport && $prescription->medicalReport->doctor && $prescription->medicalReport->doctor->user)
                                                {{ $prescription->medicalReport->doctor->user->name }}
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">Doctor information not
                                                    available</span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar-alt text-green-600 dark:text-green-300 text-sm"></i>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date prescribed
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $prescription->created_at->format('F d, Y \a\t g:i A') }}</dd>
                                    </div>
                                </div>

                                @if ($prescription->medicalReport)
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-8 h-8 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-file-medical text-purple-600 dark:text-purple-300 text-sm"></i>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Medical report
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                <a href="{{ route('patient.medical-reports.show', $prescription->medicalReport) }}"
                                                    class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                                                    {{ $prescription->medicalReport->diagnosis ?? 'General Consultation' }}
                                                </a>
                                            </dd>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start space-x-3">
                                    <div
                                        class="w-8 h-8 bg-orange-100 dark:bg-orange-800 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-pills text-orange-600 dark:text-orange-300 text-sm"></i>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total medications
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                            {{ $prescription->prescriptionMedications->count() }} items</dd>
                                    </div>
                                </div>

                                @if ($prescription->valid_until)
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-8 h-8 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-red-600 dark:text-red-300 text-sm"></i>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valid until
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $prescription->valid_until->format('F d, Y') }}</dd>
                                        </div>
                                    </div>
                                @endif

                                @if ($prescription->notes)
                                    <div class="sm:col-span-2 flex items-start space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-sticky-note text-gray-600 dark:text-gray-300 text-sm"></i>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Doctor's notes
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $prescription->notes }}</dd>
                                        </div>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Medications -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-pills mr-2 text-green-600 dark:text-green-400"></i>
                                Prescribed Medications
                            </h2>
                        </div>
                        <div class="overflow-hidden">
                            @if ($prescription->prescriptionMedications->isEmpty())
                                <div class="px-6 py-8 text-center">
                                    <div class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-pills text-4xl"></i>
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No medications</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No medications prescribed yet.
                                    </p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Medication
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Dosage
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Frequency
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Duration
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Instructions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($prescription->prescriptionMedications as $prescriptionMedication)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                <div
                                                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                                                    <i
                                                                        class="fas fa-pills text-blue-600 dark:text-blue-300"></i>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $prescriptionMedication->medication->name }}
                                                                </div>
                                                                @if ($prescriptionMedication->medication->description)
                                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                        {{ $prescriptionMedication->medication->description }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white font-medium">
                                                            {{ $prescriptionMedication->dosage }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ $prescriptionMedication->frequency }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ $prescriptionMedication->duration }}</div>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ $prescriptionMedication->instructions ?: 'As directed by doctor' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Orders -->
                @if ($prescription->pharmacyOrders->isNotEmpty())
                    <div
                        class="lg:mb-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-store mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                Pharmacy Orders
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                @foreach ($prescription->pharmacyOrders as $order)
                                    <div
                                        class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Order
                                                        #{{ $order->order_number }}</h4>
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusBadgeColor() }}">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                    <div class="flex items-center space-x-4">
                                                        @if ($order->pharmacy)
                                                            <span><i
                                                                    class="fas fa-store-alt mr-1"></i>{{ $order->pharmacy->pharmacy_name }}</span>
                                                        @endif
                                                        <span><i
                                                                class="fas fa-calendar mr-1"></i>{{ $order->created_at->format('M d, Y') }}</span>
                                                        @if ($order->total_amount)
                                                            <span><i
                                                                    class="fas fa-dollar-sign mr-1"></i>${{ number_format($order->total_amount, 2) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($order->canBeCancelled())
                                                <div class="mt-3 sm:mt-0 sm:ml-4">
                                                    <button type="button"
                                                        onclick="confirmCancelOrder('{{ $order->id }}')"
                                                        class="inline-flex items-center px-3 py-1 border border-red-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-gray-600 dark:text-red-300 dark:border-red-600 dark:hover:bg-red-900">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Cancel
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Important Notes -->
                <div
                    class="mb-6 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 dark:text-yellow-300 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important Reminders</h3>
                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Take medications exactly as prescribed by your doctor</li>
                                    <li>Complete the full course of treatment, even if you feel better</li>
                                    <li>Do not share medications with others</li>
                                    <li>Store medications in a cool, dry place away from children</li>
                                    <li>Contact your doctor if you experience any side effects</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-gray-700 dark:to-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-bolt mr-2 text-blue-600 dark:text-blue-400"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @if ($prescription->canBeOrdered())
                            <a href="{{ route('patient.prescriptions.order-pharmacy', $prescription) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                                <i class="fas fa-store mr-2"></i>
                                Order from Pharmacy
                            </a>
                        @endif                        @if ($prescription->hasRefillsRemaining())
                            <form action="{{ route('patient.prescriptions.request-refill', $prescription) }}"
                                method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to request a refill for this prescription?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 transition-colors">
                                    <i class="fas fa-redo mr-2"></i>
                                    Request Refill ({{ $prescription->refills_remaining }} left)
                                </button>
                            </form>
                        @endif

                        @if ($prescription->canBeActivated())
                            <form action="{{ route('patient.prescriptions.mark-active', $prescription) }}"
                                method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to mark this prescription as active?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-play mr-2"></i>
                                    Mark as Active
                                </button>
                            </form>
                        @endif

                        @if ($prescription->canBeCompleted())
                            <form action="{{ route('patient.prescriptions.mark-completed', $prescription) }}"
                                method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to mark this prescription as completed? This action cannot be undone.')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark as Completed
                                </button>
                            </form>
                        @endif

                        <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                            <button type="button" onclick="window.print()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-print mr-2"></i>
                                Print Prescription
                            </button>

                            <a href="{{ route('patient.prescriptions.index') }}"
                                class="mt-2 w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Prescriptions
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Prescription Info -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700 dark:to-gray-800">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-info-circle mr-2 text-gray-600 dark:text-gray-400"></i>
                            Prescription Info
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prescription Number</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                                    {{ $prescription->prescription_number ?? 'RX-' . $prescription->id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->getStatusBadgeColor() }}">
                                        <i class="fas fa-circle mr-1" style="font-size: 0.375rem;"></i>
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </dd>
                            </div>

                            @if ($prescription->is_repeatable)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Refills</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $prescription->refills_remaining }} / {{ $prescription->refills_allowed }}
                                        remaining
                                    </dd>
                                </div>
                            @endif

                            @if ($prescription->valid_until)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valid Until</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ $prescription->valid_until->format('M d, Y') }}</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Prescribed Date</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $prescription->created_at->format('M d, Y \a\t g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Latest Pharmacy Order -->
                @if ($prescription->latestPharmacyOrder())
                    @php($latestOrder = $prescription->latestPharmacyOrder())
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-800">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-shopping-cart mr-2 text-purple-600 dark:text-purple-400"></i>
                                Latest Order
                            </h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Number</span>
                                    <span
                                        class="text-sm text-gray-900 dark:text-white font-mono">{{ $latestOrder->order_number }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $latestOrder->getStatusBadgeColor() }}">
                                        {{ ucfirst($latestOrder->status) }}
                                    </span>
                                </div>

                                @if ($latestOrder->pharmacy)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Pharmacy</span>
                                        <span
                                            class="text-sm text-gray-900 dark:text-white">{{ $latestOrder->pharmacy->pharmacy_name }}</span>
                                    </div>
                                @endif

                                @if ($latestOrder->total_amount)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</span>
                                        <span
                                            class="text-sm text-gray-900 dark:text-white font-semibold">${{ number_format($latestOrder->total_amount, 2) }}</span>
                                    </div>
                                @endif

                                @if ($latestOrder->estimated_ready_at)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ready By</span>
                                        <span
                                            class="text-sm text-gray-900 dark:text-white">{{ $latestOrder->estimated_ready_at->format('M d, g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal (if needed) -->
    <div id="cancelOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-5">Cancel Order</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to cancel this pharmacy order? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="cancelOrderForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Cancel
                        </button>
                    </form>
                    <button onclick="closeCancelModal()"
                        class="px-4 py-2 bg-gray-500 dark:bg-gray-600 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmCancelOrder(orderId) {
                const modal = document.getElementById('cancelOrderModal');
                const form = document.getElementById('cancelOrderForm');
                form.action = `{{ url('/patient/pharmacy-orders') }}/${orderId}`;
                modal.classList.remove('hidden');
            }

            function closeCancelModal() {
                const modal = document.getElementById('cancelOrderModal');
                modal.classList.add('hidden');
            }

            // Close modal when clicking outside
            document.getElementById('cancelOrderModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCancelModal();
                }
            });

            // Close modal with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCancelModal();
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                /* Sidebar should not print */
                .lg\:col-span-2~div {
                    display: none !important;
                }

                /* Expand main content to full width */
                .lg\:col-span-2 {
                    grid-column: span 3 !important;
                }

                body {
                    print-color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                }

                /* Ensure proper colors for print */
                .bg-yellow-50 {
                    background-color: #fffbeb !important;
                }

                .border-yellow-200 {
                    border-color: #fde68a !important;
                }

                .text-yellow-800 {
                    color: #92400e !important;
                }

                .text-yellow-700 {
                    color: #b45309 !important;
                }

                /* Dark mode adjustments for print */
                .dark\:bg-gray-800 {
                    background-color: #ffffff !important;
                }

                .dark\:text-white {
                    color: #000000 !important;
                }

                .dark\:border-gray-700 {
                    border-color: #d1d5db !important;
                }

                /* Hide action buttons when printing */
                button,
                .no-print {
                    display: none !important;
                }

                /* Adjust spacing for print */
                .space-y-6>*+* {
                    margin-top: 1rem !important;
                }
            }
        </style>
    @endpush
@endsection
