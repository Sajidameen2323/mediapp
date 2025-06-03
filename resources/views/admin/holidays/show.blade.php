@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.holidays.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-150 flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Holiday Requests
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 mx-2"></i>
                            <span class="text-gray-500 dark:text-gray-400">{{ $holiday->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Holiday Request Details</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and manage this holiday request</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($holiday->status === 'pending')
                        <form action="{{ route('admin.holidays.approve', $holiday) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center justify-center font-medium"
                                    onclick="return confirm('Are you sure you want to approve this holiday request?')">
                                <i class="fas fa-check mr-2"></i>
                                Approve Request
                            </button>
                        </form>
                        
                        <button type="button" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center justify-center font-medium"
                                onclick="openRejectModal()">
                            <i class="fas fa-times mr-2"></i>
                            Reject Request
                        </button>
                    @endif
                </div>
            </div>
        </div>        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-600 dark:text-blue-400"></i>
                            Request Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">{{ $holiday->title }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <div class="flex items-center">
                                    @if($holiday->status === 'pending')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">
                                            <i class="fas fa-clock mr-2"></i>
                                            Pending Review
                                        </span>
                                    @elseif($holiday->status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                                            <i class="fas fa-check mr-2"></i>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                                            <i class="fas fa-times mr-2"></i>
                                            Rejected
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ \Carbon\Carbon::parse($holiday->start_date)->format('l, F j, Y') }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ \Carbon\Carbon::parse($holiday->end_date)->format('l, F j, Y') }}
                                </p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration</label>
                                <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md border border-gray-200 dark:border-gray-600">
                                    <i class="fas fa-calendar-day mr-2 text-blue-600 dark:text-blue-400"></i>
                                    {{ \Carbon\Carbon::parse($holiday->start_date)->diffInDays(\Carbon\Carbon::parse($holiday->end_date)) + 1 }} days
                                    <span class="text-gray-500 dark:text-gray-400 ml-2">
                                        ({{ \Carbon\Carbon::parse($holiday->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($holiday->end_date)->format('M j, Y') }})
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        @if($holiday->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                                    {{ $holiday->description }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>                <!-- Admin Notes/Actions -->
                @if($holiday->status !== 'pending' && ($holiday->admin_notes || $holiday->approved_by || $holiday->rejected_by))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-user-shield mr-2 text-blue-600 dark:text-blue-400"></i>
                                Admin Review
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($holiday->approved_by)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Approved By</label>
                                        <p class="text-sm text-gray-900 dark:text-white bg-green-50 dark:bg-green-900/20 p-3 rounded-md border border-green-200 dark:border-green-800">
                                            <i class="fas fa-user-check mr-2 text-green-600 dark:text-green-400"></i>
                                            {{ $holiday->approvedBy->name ?? 'Unknown Admin' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Approved At</label>
                                        <p class="text-sm text-gray-900 dark:text-white bg-green-50 dark:bg-green-900/20 p-3 rounded-md border border-green-200 dark:border-green-800">
                                            <i class="fas fa-calendar-check mr-2 text-green-600 dark:text-green-400"></i>
                                            {{ $holiday->approved_at ? \Carbon\Carbon::parse($holiday->approved_at)->format('M j, Y g:i A') : 'N/A' }}
                                        </p>
                                    </div>
                                @endif
                                
                                @if($holiday->rejected_by)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejected By</label>
                                        <p class="text-sm text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-3 rounded-md border border-red-200 dark:border-red-800">
                                            <i class="fas fa-user-times mr-2 text-red-600 dark:text-red-400"></i>
                                            {{ $holiday->rejectedBy->name ?? 'Unknown Admin' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejected At</label>
                                        <p class="text-sm text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-3 rounded-md border border-red-200 dark:border-red-800">
                                            <i class="fas fa-calendar-times mr-2 text-red-600 dark:text-red-400"></i>
                                            {{ $holiday->rejected_at ? \Carbon\Carbon::parse($holiday->rejected_at)->format('M j, Y g:i A') : 'N/A' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            
                            @if($holiday->admin_notes)
                                <div class="mt-6">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admin Notes</label>
                                    <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-md border border-gray-200 dark:border-gray-600">
                                        <i class="fas fa-sticky-note mr-2 text-gray-600 dark:text-gray-400"></i>
                                        {{ $holiday->admin_notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Doctor Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-md mr-2 text-blue-600 dark:text-blue-400"></i>
                            Doctor Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                <span class="text-xl font-medium text-blue-600 dark:text-blue-400">
                                    {{ substr($holiday->doctor->user->name, 0, 2) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Dr. {{ $holiday->doctor->user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    {{ $holiday->doctor->user->email }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                                    <i class="fas fa-phone mr-2"></i>
                                    Phone:
                                </span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $holiday->doctor->user->phone ?? 'Not provided' }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                                    <i class="fas fa-stethoscope mr-2"></i>
                                    Specialization:
                                </span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $holiday->doctor->specialization ?? 'Not specified' }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                                    <i class="fas fa-award mr-2"></i>
                                    Experience:
                                </span>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $holiday->doctor->experience ?? 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Request Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 ">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>
                            Request Timeline
                        </h2>
                    </div>
                    <div class="p-8 pb-12">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        @if($holiday->status !== 'pending')
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 dark:bg-blue-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <i class="fas fa-plus text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-900 dark:text-white font-medium">Request Submitted</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $holiday->created_at->format('M j, Y g:i A') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                @if($holiday->status === 'approved')
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 dark:bg-green-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <i class="fas fa-check text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-white font-medium">Request Approved</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $holiday->approved_at ? \Carbon\Carbon::parse($holiday->approved_at)->format('M j, Y g:i A') : 'Recently' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @elseif($holiday->status === 'rejected')
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-red-500 dark:bg-red-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <i class="fas fa-times text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-white font-medium">Request Rejected</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $holiday->rejected_at ? \Carbon\Carbon::parse($holiday->rejected_at)->format('M j, Y g:i A') : 'Recently' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div class="relative">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-yellow-500 dark:bg-yellow-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                        <i class="fas fa-clock text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-900 dark:text-white font-medium">Pending Review</p>
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Awaiting admin approval</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-75 overflow-y-auto h-full w-full hidden z-50 transition-opacity">
    <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-700 w-full max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center mb-6">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50">
                    <i class="fas fa-times text-red-600 dark:text-red-400 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white ml-4">Reject Holiday Request</h3>
            </div>
            
            <form action="{{ route('admin.holidays.reject', $holiday) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-sticky-note mr-2"></i>
                        Reason for Rejection (Optional)
                    </label>
                    <textarea id="admin_notes" name="admin_notes" rows="4" 
                              class="w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 dark:focus:border-red-400 dark:focus:ring-red-400 transition-colors"
                              placeholder="Provide a reason for rejecting this request..."></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center">
                        <i class="fas fa-ban mr-2"></i>
                        Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal() {
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('reject-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endpush
@endsection
