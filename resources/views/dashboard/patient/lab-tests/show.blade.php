@extends('layouts.patient')

@section('title', 'Lab Test Details - ' . $labTest->test_name)

@section('content')


<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <!-- Header Content -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $labTest->test_name }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Lab test details and information</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <x-lab-test.status-badge :status="$labTest->status" class="text-base px-4 py-2" />
                    @if($labTest->priority && $labTest->priority !== 'normal')
                        <x-lab-test.priority-badge :priority="$labTest->priority" class="text-base px-3 py-2" />
                    @endif
                </div>
            </div>
        </div>

        <!-- Lab Test Details -->
        <x-lab-test.details :lab-test="$labTest" />

        <!-- Danger Zone -->
        <div class="mt-12">
            <div class="border border-red-200 dark:border-red-900 rounded-lg overflow-hidden">
                <div class="bg-red-50 dark:bg-red-900/30 px-6 py-4 border-b border-red-200 dark:border-red-900">
                    <h3 class="text-lg font-semibold text-red-700 dark:text-red-400 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
                        Danger Zone
                    </h3>
                </div>
                <div class="bg-white dark:bg-gray-800 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Delete this lab test</h4>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Once you delete this lab test, it cannot be recovered. 
                                Note: You cannot delete lab tests that are currently in progress. Completed tests can be deleted after 30 days.
                            </p>
                        </div>
                        @if($labTest->status !== 'in_progress' && (!($labTest->status === 'completed' && $labTest->completed_at && now()->diffInDays($labTest->completed_at) < 30)))
                        <button type="button" 
                                onclick="openDeleteModal()"
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete Lab Test
                        </button>
                        @else
                        <button type="button" 
                                disabled
                                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 dark:bg-gray-600 cursor-not-allowed">
                            <i class="fas fa-lock mr-2"></i>
                            @if($labTest->status === 'in_progress')
                                In Progress
                            @elseif($labTest->status === 'completed' && $labTest->completed_at)
                                Available in {{ max(0, 30 - (int)now()->diffInDays($labTest->completed_at)) }} days
                            @else
                                Cannot Delete
                            @endif
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Delete Lab Test
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to delete this lab test? This action cannot be undone.
                                All data associated with this lab test will be permanently removed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form action="{{ route('patient.lab-tests.destroy', $labTest) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endpush

@endsection
