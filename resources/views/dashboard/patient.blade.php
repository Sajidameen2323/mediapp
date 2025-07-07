@extends('layouts.patient')

@section('title', 'Patient Dashboard - Medi App')

@section('content')


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Patient Dashboard</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your health and medical appointments</p>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-medical text-white text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-blue-100 truncate">Medical Reports</dt>
                            <dd class="text-lg font-medium text-white">{{ $stats['totalMedicalReports'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-blue-700 dark:bg-blue-800 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('patient.medical-reports.index') }}" class="font-medium text-blue-100 hover:text-white">
                        View all reports <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-pills text-white text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-purple-100 truncate">Active Prescriptions</dt>
                            <dd class="text-lg font-medium text-white">{{ $stats['activePrescriptions'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-purple-700 dark:bg-purple-800 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('patient.prescriptions.index') }}" class="font-medium text-purple-100 hover:text-white">
                        View prescriptions <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-vial text-white text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-yellow-100 truncate">Pending Lab Tests</dt>
                            <dd class="text-lg font-medium text-white">{{ $stats['pendingLabTests'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-700 dark:bg-yellow-800 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('patient.lab-tests.index') }}" class="font-medium text-yellow-100 hover:text-white">
                        View lab tests <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-white text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-green-100 truncate">Total Appointments</dt>
                            <dd class="text-lg font-medium text-white">{{ $stats['totalAppointments'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-green-700 dark:bg-green-800 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('patient.appointments.index') }}" class="font-medium text-green-100 hover:text-white">
                        View appointments <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shopping-cart text-white text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-indigo-100 truncate">Pending Payments</dt>
                            <dd class="text-lg font-medium text-white">{{ $stats['pendingPaymentOrders'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-indigo-700 dark:bg-indigo-800 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('patient.pharmacy-orders.index', ['payment_status' => 'pending']) }}" class="font-medium text-indigo-100 hover:text-white">
                        Pay orders <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                    <i class="fas fa-calendar-plus mr-2 text-blue-600 dark:text-blue-400"></i>
                    Book Appointment
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Schedule a consultation with a doctor</p>
                <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Book Now
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                    <i class="fas fa-user-injured mr-2 text-green-600 dark:text-green-400"></i>
                    Health Profile
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    @if(auth()->user()->healthProfile)
                        Update your health information
                    @else
                        Create your health profile
                    @endif
                </p>
                @if(auth()->user()->healthProfile)
                    <a href="{{ route('patient.health-profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Update
                    </a>
                @else
                    <a href="{{ route('patient.health-profile.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Create
                    </a>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                    <i class="fas fa-search mr-2 text-purple-600 dark:text-purple-400"></i>
                    Find Doctors
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Search for specialists and healthcare providers</p>
                <a href="{{ route('patient.appointments.search-doctors') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>Search
                </a>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Cards -->
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Health Management</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">My Appointments</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and book appointments</p>
                <div class="mt-4 flex space-x-2">
                    <a href="{{ route('patient.appointments.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 text-sm">
                        <i class="fas fa-list mr-1"></i>My Appointments
                    </a>
                    <a href="{{ route('patient.appointments.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition-colors duration-200 text-sm">
                        <i class="fas fa-plus mr-1"></i>Book New
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-file-medical mr-2 text-blue-600 dark:text-blue-400"></i>
                    Medical Reports
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View your consultation reports and medical history</p>
                <div class="mt-4">
                    <a href="{{ route('patient.medical-reports.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-200 text-sm inline-flex items-center">
                        <i class="fas fa-eye mr-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-pills mr-2 text-purple-600 dark:text-purple-400"></i>
                    My Prescriptions
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and manage your medication prescriptions</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('patient.prescriptions.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>All Prescriptions
                    </a>
                    <a href="{{ route('patient.prescriptions.index', ['status' => 'active']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-check-circle mr-2"></i>Active Only
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-vial mr-2 text-yellow-600 dark:text-yellow-400"></i>
                    Lab Test Requests
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Track your laboratory test requests and results</p>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('patient.lab-tests.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-list mr-2"></i>All Lab Tests
                    </a>
                    <a href="{{ route('patient.lab-tests.index', ['status' => 'pending']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-clock mr-2"></i>Pending Tests
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-user-injured mr-2 text-red-600 dark:text-red-400"></i>
                    Health Profile
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your comprehensive health information</p>
                @if(auth()->user()->healthProfile)
                    <!-- Health Profile exists -->
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="text-green-600 dark:text-green-400 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>Complete
                            </span>
                        </div>
                        @if(auth()->user()->healthProfile->blood_type)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Blood Type:</span>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->blood_type }}</span>
                            </div>
                        @endif
                        @if(auth()->user()->healthProfile->height && auth()->user()->healthProfile->weight)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">BMI:</span>
                                {{-- <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->getBMI() }}</span> --}}
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ auth()->user()->healthProfile->bmi_category }}</span>

                            </div>
                        @endif
                    </div>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('patient.health-profile.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-eye mr-2"></i>View Profile
                        </a>
                        <a href="{{ route('patient.health-profile.edit') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Update Profile
                        </a>
                    </div>
                @else
                    <!-- No Health Profile -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="text-orange-600 dark:text-orange-400 font-medium flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Incomplete
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Create your health profile to help healthcare providers better assist you.</p>
                        <a href="{{ route('patient.health-profile.create') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Create Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Emergency Contacts</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage emergency contacts</p>
                <div class="mt-4">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition-colors duration-200">
                        Manage Contacts
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Prescriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-pills mr-2 text-purple-600 dark:text-purple-400"></i>
                        Recent Prescriptions
                    </h3>
                </div>
                <div class="px-6 py-4">
                    @if($stats['totalPrescriptions'] > 0)
                        <div class="space-y-3">
                            @php
                                $recentPrescriptions = auth()->user()->prescriptions()->with('medicalReport.doctor')->latest()->take(3)->get();
                            @endphp
                            @forelse($recentPrescriptions as $prescription)
                                <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            Prescription #{{ $prescription->id }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($prescription->medicalReport && $prescription->medicalReport->doctor)
                                                {{ $prescription->medicalReport->doctor->user->name }} • {{ $prescription->created_at->format('M d, Y') }}
                                            @else
                                                {{ $prescription->created_at->format('M d, Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                                        {{ ucfirst($prescription->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent prescriptions</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('patient.prescriptions.index') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 font-medium">
                                View all prescriptions <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-pills text-gray-400 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No prescriptions yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Lab Tests -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-vial mr-2 text-yellow-600 dark:text-yellow-400"></i>
                        Recent Lab Tests
                    </h3>
                </div>
                <div class="px-6 py-4">
                    @if($stats['totalLabTests'] > 0)
                        <div class="space-y-3">
                            @php
                                $recentLabTests = auth()->user()->labTestRequests()->with('medicalReport.doctor')->latest()->take(3)->get();
                            @endphp
                            @forelse($recentLabTests as $labTest)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $labTest->test_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($labTest->medicalReport && $labTest->medicalReport->doctor)
                                                {{ $labTest->medicalReport->doctor->user->name }} • {{ $labTest->created_at->format('M d, Y') }}
                                            @else
                                                {{ $labTest->created_at->format('M d, Y') }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($labTest->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                        @elseif($labTest->status === 'completed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                        @elseif($labTest->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $labTest->status)) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent lab tests</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('patient.lab-tests.index') }}" class="text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-500 font-medium">
                                View all lab tests <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-vial text-gray-400 text-3xl mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No lab tests yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
