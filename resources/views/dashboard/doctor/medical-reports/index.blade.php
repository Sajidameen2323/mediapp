@extends('layouts.app')

@section('title', 'Medical Reports - Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Medical Reports</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Create and manage patient medical reports</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('doctor.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('doctor.medical-reports.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>New Report
                </a>
            </div>
        </div>    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-8 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 dark:border-green-600 p-6 rounded-r-xl shadow-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="bg-green-500 p-2 rounded-lg">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-2">
                        <i class="fas fa-thumbs-up mr-2"></i>Success!
                    </h3>
                    <div class="text-green-700 dark:text-green-400">
                        <p class="mb-2">{{ session('success') }}</p>
                        @if(session('report_details'))
                            <div class="mt-3 text-sm bg-green-100 dark:bg-green-800/30 p-3 rounded-lg">
                                <p><strong>Patient:</strong> {{ session('report_details.patient_name') }}</p>
                                <p><strong>Date:</strong> {{ session('report_details.consultation_date') }}</p>
                                <p><strong>Status:</strong> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ session('report_details.status') === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' }}">
                                        {{ ucfirst(session('report_details.status')) }}
                                    </span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'" class="text-green-400 hover:text-green-600 dark:text-green-500 dark:hover:text-green-300 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Report Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-medical text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Reports</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $reports->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Completed</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $reports->where('status', 'completed')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-2xl text-orange-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Draft</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $reports->where('status', 'draft')->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-day text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">This Month</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $reports->where('consultation_date', '>=', now()->startOfMonth())->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('doctor.medical-reports.index') }}">
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <div class="flex flex-wrap items-center space-x-4">
                    <div class="flex items-center">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Status:</label>
                        <select name="status" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Report Type:</label>
                        <select name="report_type" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="" {{ request('report_type') == '' ? 'selected' : '' }}>All</option>
                            <option value="Consultation" {{ request('report_type') == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="Follow-up" {{ request('report_type') == 'Follow-up' ? 'selected' : '' }}>Follow-up</option>
                            <option value="Diagnosis" {{ request('report_type') == 'Diagnosis' ? 'selected' : '' }}>Diagnosis</option>
                            <option value="Treatment" {{ request('report_type') == 'Treatment' ? 'selected' : '' }}>Treatment</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Date Range:</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white mr-2">
                        <span class="text-gray-500">to</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 dark:border-gray-600 rounded-md px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white ml-2">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-md text-sm transition-colors duration-200">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Reports List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Medical Reports</h3>
        </div>
        
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Report Title & Patient Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Consultation Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                                <i class="fas fa-file-medical text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            @if($report->title)
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                                    <i class="fas fa-heading text-indigo-500 mr-1"></i>{{ $report->title }}
                                                </div>
                                            @endif
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <i class="fas fa-user text-blue-500 mr-1"></i>{{ $report->patient->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $report->patient->email }}
                                            </div>
                                            @if($report->chief_complaint)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    <i class="fas fa-comment-medical text-gray-400 mr-1"></i>{{ Str::limit($report->chief_complaint, 40) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <div>
                                        <div class="font-medium">{{ $report->consultation_date->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Created: {{ $report->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $report->report_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($report->status == 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($report->status == 'draft') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @endif">
                                        <i class="fas fa-circle w-2 h-2 mr-1"></i>
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('doctor.medical-reports.show', $report) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" title="View Report">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($report->status == 'draft')
                                            <a href="{{ route('doctor.medical-reports.edit', $report) }}" class="text-orange-600 hover:text-orange-900 dark:hover:text-orange-400" title="Edit Report">
                                                <i class="fas fa-edit"></i>
                                            </a>                                        @endif
                                        <a href="{{ route('doctor.reports.pdf', $report) }}" target="_blank" class="text-green-600 hover:text-green-900 dark:hover:text-green-400" title="Export PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <button class="text-purple-600 hover:text-purple-900 dark:hover:text-purple-400" title="Send to Patient">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                        @if($report->status == 'draft')
                                            <form method="POST" action="{{ route('doctor.medical-reports.destroy', $report) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:hover:text-red-400" title="Delete Report" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-medical text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No medical reports yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating your first medical report for a patient consultation.</p>
                <a href="{{ route('doctor.medical-reports.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Create Report
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    {{-- <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200">
                        <i class="fas fa-file-medical-alt mr-2"></i>
                        New Consultation
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-redo mr-2"></i>
                        Follow-up Report
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Export Reports
                    </button>
                    <button class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200">
                        <i class="fas fa-chart-bar mr-2"></i>
                        View Analytics
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
