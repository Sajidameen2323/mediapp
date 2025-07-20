@extends('layouts.admin')

@section('title', 'Admin Reports & Analytics')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 mr-3"></i>
                        Reports & Analytics
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Comprehensive system analytics and performance insights
                    </p>
                </div>

                <!-- Date Range Filter -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex gap-2">
                            <input type="date" 
                                   name="start_date" 
                                   value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="date" 
                                   name="end_date" 
                                   value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                                   class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Key Metrics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            {{ number_format($activeUsers) }} active
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Appointments</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($appointmentStats['total']) }}</p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            {{ $appointmentStats['completion_rate'] }}% completed
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($revenueStats['total'], 2) }}</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                            ${{ number_format($revenueStats['pending'], 2) }} pending
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>

            <!-- Pharmacy Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pharmacy Orders</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($pharmacyStats['total_orders']) }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            ${{ number_format($pharmacyStats['total_value'], 2) }} value
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-pills text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Doctors</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalDoctors) }}</p>
                    </div>
                    <i class="fas fa-stethoscope text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Patients</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalPatients) }}</p>
                    </div>
                    <i class="fas fa-user-injured text-green-600 dark:text-green-400"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pharmacies</p>
                        <p class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalPharmacies) }}</p>
                    </div>
                    <i class="fas fa-prescription-bottle text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Laboratories</p>
                        <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($totalLaboratories) }}</p>
                    </div>
                    <i class="fas fa-flask text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Appointment Trends -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Appointment Trends</h3>
                    <i class="fas fa-chart-line text-gray-400"></i>
                </div>
                <div class="h-64">
                    <canvas id="appointmentTrendChart"></canvas>
                </div>
            </div>

            <!-- Revenue Trends -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Trends</h3>
                    <i class="fas fa-chart-area text-gray-400"></i>
                </div>
                <div class="h-64">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Appointment Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Appointment Status Distribution</h3>
                <div class="space-y-3">
                    @foreach($appointmentStats['status_distribution'] as $status => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3 
                                @if($status === 'completed') bg-green-500
                                @elseif($status === 'pending') bg-yellow-500
                                @elseif($status === 'cancelled') bg-red-500
                                @else bg-gray-500 @endif"></div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $status }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Methods</h3>
                <div class="space-y-3">
                    @foreach($revenueStats['payment_methods'] as $method => $total)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-blue-600 dark:text-blue-400 text-xs"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $method) }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">${{ number_format($total, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Top Doctors -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top Doctors
                </h3>
                <div class="space-y-3">
                    @foreach($topDoctors as $doctor)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $doctor->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doctor->specialization }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $doctor->total_appointments }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">appointments</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Services -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Popular Services
                </h3>
                <div class="space-y-3">
                    @foreach($topServices as $service)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${{ number_format($service->price, 2) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">{{ $service->total_appointments }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">bookings</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Pharmacies -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-medal text-yellow-500 mr-2"></i>
                    Top Pharmacies
                </h3>
                <div class="space-y-3">
                    @foreach($topPharmacies as $pharmacy)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $pharmacy->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pharmacy->address }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $pharmacy->total_orders }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">orders</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                Recent Appointments
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentAppointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $appointment->patient->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $appointment->doctor->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $appointment->service->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($appointment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                    @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                ${{ number_format($appointment->total_amount, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-download text-green-500 mr-2"></i>
                Export Reports
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.reports.export', ['type' => 'appointments'] + request()->query()) }}" 
                   class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Appointments
                </a>
                <a href="{{ route('admin.reports.export', ['type' => 'revenue'] + request()->query()) }}" 
                   class="flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-dollar-sign mr-2"></i>
                    Revenue
                </a>
                <a href="{{ route('admin.reports.export', ['type' => 'pharmacy'] + request()->query()) }}" 
                   class="flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-pills mr-2"></i>
                    Pharmacy Orders
                </a>
                <a href="{{ route('admin.reports.export', ['type' => 'users'] + request()->query()) }}" 
                   class="flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-users mr-2"></i>
                    Users
                </a>
            </div>
        </div>

        <!-- Management Tools -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-cogs text-blue-500 mr-2"></i>
                Management Tools
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('admin.reports.user-management') }}" 
                   class="group p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users-cog text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            User Management
                        </h4>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Manage user accounts, activate/deactivate users, and view user details
                    </p>
                </a>

                <a href="{{ route('admin.reports.analytics', ['metric' => 'appointments'] + request()->query()) }}" 
                   class="group p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-green-500 dark:hover:border-green-400 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-green-600 dark:text-green-400"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400">
                            Detailed Analytics
                        </h4>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        View detailed analytics with charts and comprehensive breakdowns
                    </p>
                </a>

                <a href="{{ route('admin.appointments.index') }}" 
                   class="group p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition-colors">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400">
                            Appointment Management
                        </h4>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Manage appointments, approve, cancel, and reschedule bookings
                    </p>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Appointment Trend Chart
const appointmentCtx = document.getElementById('appointmentTrendChart').getContext('2d');
const appointmentChart = new Chart(appointmentCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($appointmentStats['daily_trend']->pluck('date')) !!},
        datasets: [{
            label: 'Appointments',
            data: {!! json_encode($appointmentStats['daily_trend']->pluck('count')) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            }
        }
    }
});

// Revenue Trend Chart
const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($revenueStats['daily_trend']->pluck('date')) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($revenueStats['daily_trend']->pluck('total')) !!},
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(107, 114, 128, 0.1)'
                }
            }
        }
    }
});
</script>
@endpush
@endsection
