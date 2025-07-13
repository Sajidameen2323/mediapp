@extends('layouts.patient')

@section('title', 'Manage Access - ' . $labTest->test_name)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('patient.dashboard') }}" 
                           class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors duration-200">
                            <i class="fas fa-home"></i>
                            <span class="sr-only">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.lab-tests.index') }}" 
                               class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                Lab Tests
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
                               class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-200">
                                {{ $labTest->test_name }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Manage Access</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manage Test Access</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Control which doctors can view your lab test results</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <x-lab-test.status-badge :status="$labTest->status" class="text-base px-4 py-2" />
                </div>
            </div>
        </div>

        <!-- Test Overview Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                    <i class="fas fa-vial text-blue-500 dark:text-blue-400 mr-3"></i>
                    Test Information
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Test Name</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $labTest->test_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Requested By</label>
                        <p class="text-gray-900 dark:text-white">
                            @if($labTest->doctor)
                                Dr. {{ $labTest->doctor->user->name }}
                            @else
                                <span class="text-gray-400 dark:text-gray-500">No doctor assigned</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Request Number</label>
                        <p class="text-gray-900 dark:text-white font-mono">{{ $labTest->request_number }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($labTest->status === 'completed')
            <!-- Grant New Access Card -->
            @if($availableDoctors->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-plus text-green-500 dark:text-green-400 mr-3"></i>
                            Grant Access to Doctor
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Allow a doctor to view your test results</p>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('patient.lab-tests.access.grant', $labTest) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Doctor</label>
                                    <select name="doctor_id" required 
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <option value="">Choose a doctor...</option>
                                        @foreach($availableDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                Dr. {{ $doctor->user->name }} 
                                                @if($doctor->specialization)
                                                    - {{ $doctor->specialization }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Access Expires (Optional)</label>
                                    <input type="date" name="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                                <textarea name="notes" rows="3" placeholder="Add any notes about this access grant..."
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                    <i class="fas fa-check mr-2"></i>
                                    Grant Access
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Current Access List -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-users text-blue-500 dark:text-blue-400 mr-3"></i>
                            Doctors with Access
                        </h2>
                        @if($labTest->accessRecords->where('status', '!=', 'revoked')->count() > 1)
                            <button type="button" onclick="toggleBulkActions()" 
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                <i class="fas fa-edit mr-1"></i>
                                Bulk Actions
                            </button>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($labTest->accessRecords->count() > 0)
                        <!-- Bulk Actions Form (hidden by default) -->
                        <form id="bulkActionsForm" action="{{ route('patient.lab-tests.access.bulk', $labTest) }}" method="POST" style="display: none;" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action</label>
                                    <select name="action" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                        <option value="">Select action...</option>
                                        <option value="revoke">Revoke Access</option>
                                        <option value="extend">Extend Access</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Expiry (for extend)</label>
                                    <input type="date" name="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                    <input type="text" name="notes" placeholder="Optional notes..."
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        Apply
                                    </button>
                                    <button type="button" onclick="toggleBulkActions()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="space-y-4">
                            @foreach($labTest->accessRecords as $access)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 {{ $access->status === 'revoked' ? 'opacity-50' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            @if($access->status !== 'revoked' && $access->access_type !== 'author')
                                                <input type="checkbox" name="access_ids[]" value="{{ $access->id }}" form="bulkActionsForm"
                                                       class="bulk-checkbox mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" style="display: none;">
                                            @endif
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-md text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                        Dr. {{ $access->doctor->user->name }}
                                                    </h4>
                                                    @if($access->access_type === 'author')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            <i class="fas fa-star mr-1"></i>
                                                            Ordering Doctor
                                                        </span>
                                                    @endif
                                                    @if($access->status === 'active')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            Active
                                                        </span>
                                                    @elseif($access->status === 'revoked')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            Revoked
                                                        </span>
                                                    @endif
                                                    @if($access->isExpired())
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            Expired
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($access->doctor->specialization)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $access->doctor->specialization }}</p>
                                                @endif
                                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                                    <div class="flex items-center space-x-4">
                                                        <span>
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            Granted: {{ $access->granted_at ? $access->granted_at->format('M d, Y g:i A') : 'N/A' }}
                                                        </span>
                                                        @if($access->expires_at)
                                                            <span>
                                                                <i class="fas fa-clock mr-1"></i>
                                                                Expires: {{ $access->expires_at->format('M d, Y') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if($access->notes)
                                                        <p><i class="fas fa-note-sticky mr-1"></i>{{ $access->notes }}</p>
                                                    @endif
                                                    @if($access->status === 'revoked' && $access->revoked_at)
                                                        <p class="text-red-600 dark:text-red-400">
                                                            <i class="fas fa-ban mr-1"></i>
                                                            Revoked: {{ $access->revoked_at->format('M d, Y g:i A') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($access->status === 'active' && $access->access_type !== 'author')
                                            <div class="flex items-center space-x-2">
                                                <button type="button" onclick="editAccess('{{ $access->id }}')" 
                                                        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Edit
                                                </button>
                                                <form action="{{ route('patient.lab-tests.access.revoke', [$labTest, $access]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to revoke access for this doctor?')"
                                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium">
                                                        <i class="fas fa-ban mr-1"></i>
                                                        Revoke
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Access Granted</h3>
                            <p class="text-gray-600 dark:text-gray-400">No doctors currently have access to this lab test.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Test Not Completed Message -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-yellow-600 dark:text-yellow-400 text-xl mr-3"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100">Test Not Completed</h3>
                        <p class="text-yellow-800 dark:text-yellow-200 mt-1">
                            Access management will be available once your test is completed and results are available.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('patient.lab-tests.show', $labTest) }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Test Details
            </a>
        </div>
    </div>
</div>

<script>
function toggleBulkActions() {
    const form = document.getElementById('bulkActionsForm');
    const checkboxes = document.querySelectorAll('.bulk-checkbox');
    
    if (form.style.display === 'none') {
        form.style.display = 'block';
        checkboxes.forEach(cb => cb.style.display = 'inline-block');
    } else {
        form.style.display = 'none';
        checkboxes.forEach(cb => {
            cb.style.display = 'none';
            cb.checked = false;
        });
    }
}

function editAccess(accessId) {
    // You can implement a modal here for editing access
    // For now, this is a placeholder
    alert('Edit functionality can be implemented with a modal');
}
</script>
@endsection
