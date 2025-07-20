@extends('layouts.admin')

@section('title', 'Detailed Analytics - ' . ucfirst($metric))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Reports
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400 capitalize">{{ $metric }} Analytics</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2 flex items-center capitalize">
                        <i class="fas fa-analytics text-blue-600 dark:text-blue-400 mr-3"></i>
                        {{ $metric }} Analytics
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Detailed insights for {{ $startDate->format('M d, Y') }} to {{ $endDate->format('M d, Y') }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.reports.index', request()->query()) }}" 
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => $metric] + request()->query()) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Export Data
                    </a>
                </div>
            </div>
        </div>

        @if($metric === 'appointments')
            <!-- Appointment Analytics -->
            @if(isset($data['hourly_distribution']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                    Hourly Distribution
                </h3>
                <div class="h-64">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($data['monthly_trend']) && $data['monthly_trend']->count() > 1)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-calendar text-green-500 mr-2"></i>
                    Monthly Trend
                </h3>
                <div class="h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
            @endif

            @if(isset($data['service_popularity']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Service Popularity
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Appointments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Popularity</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($data['service_popularity'] as $service)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $service->description }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($service->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 dark:text-blue-400">
                                    {{ number_format($service->total_appointments) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $service->total_appointments > 0 ? min(($service->total_appointments / $data['service_popularity']->max('total_appointments')) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $service->total_appointments > 0 ? round(($service->total_appointments / $data['service_popularity']->sum('total_appointments')) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        @elseif($metric === 'revenue')
            <!-- Revenue Analytics -->
            @if(isset($data['revenue_by_service']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-chart-pie text-green-500 mr-2"></i>
                    Revenue by Service
                </h3>
                <div class="h-64 mb-4">
                    <canvas id="serviceRevenueChart"></canvas>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($data['revenue_by_service'] as $service)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $service->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($service->total_revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $service->total_revenue > 0 ? min(($service->total_revenue / $data['revenue_by_service']->max('total_revenue')) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $service->total_revenue > 0 ? round(($service->total_revenue / $data['revenue_by_service']->sum('total_revenue')) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(isset($data['revenue_by_doctor']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-user-md text-blue-500 mr-2"></i>
                    Revenue by Doctor
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($data['revenue_by_doctor'] as $doctor)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $doctor->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600 dark:text-blue-400">
                                    ${{ number_format($doctor->total_revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $doctor->total_revenue > 0 ? min(($doctor->total_revenue / $data['revenue_by_doctor']->max('total_revenue')) * 100, 100) : 0 }}%"></div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $doctor->total_revenue > 0 ? round(($doctor->total_revenue / $data['revenue_by_doctor']->sum('total_revenue')) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        @elseif($metric === 'users')
            <!-- User Analytics -->
            @if(isset($data['registration_trend']))
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-users text-purple-500 mr-2"></i>
                    User Registration Trend
                </h3>
                <div class="h-64">
                    <canvas id="registrationChart"></canvas>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($metric === 'appointments')
    @if(isset($data['hourly_distribution']))
    // Hourly Distribution Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($data['hourly_distribution']->toArray())) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode(array_values($data['hourly_distribution']->toArray())) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
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
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    @if(isset($data['monthly_trend']) && $data['monthly_trend']->count() > 1)
    // Monthly Trend Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($data['monthly_trend']->pluck('period')) !!},
            datasets: [{
                label: 'Appointments',
                data: {!! json_encode($data['monthly_trend']->pluck('count')) !!},
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
            }
        }
    });
    @endif

@elseif($metric === 'revenue')
    @if(isset($data['revenue_by_service']))
    // Service Revenue Chart
    const serviceCtx = document.getElementById('serviceRevenueChart').getContext('2d');
    const serviceChart = new Chart(serviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($data['revenue_by_service']->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($data['revenue_by_service']->pluck('total_revenue')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(6, 182, 212, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif

@elseif($metric === 'users')
    @if(isset($data['registration_trend']))
    // Registration Trend Chart
    const regCtx = document.getElementById('registrationChart').getContext('2d');
    const datasets = [];
    const userTypes = ['patient', 'doctor', 'pharmacy', 'laboratory'];
    const colors = {
        'patient': 'rgba(34, 197, 94, 0.8)',
        'doctor': 'rgba(59, 130, 246, 0.8)',
        'pharmacy': 'rgba(168, 85, 247, 0.8)',
        'laboratory': 'rgba(251, 146, 60, 0.8)'
    };

    userTypes.forEach(type => {
        datasets.push({
            label: type.charAt(0).toUpperCase() + type.slice(1),
            data: {!! json_encode($data['registration_trend']->map(function($item) { return $item['types']; })) !!}.map(item => item[type] || 0),
            backgroundColor: colors[type],
            borderColor: colors[type].replace('0.8', '1'),
            borderWidth: 1
        });
    });

    const regChart = new Chart(regCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['registration_trend']->pluck('date')) !!},
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
    @endif
@endif
</script>
@endpush
@endsection
