@extends('layouts.patient')

@section('title', 'Lab Test Details - ' . $labTest->test_name)

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="labTestPage">
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
                                    @click="showDeleteModal = true"
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

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" 
             @click="closeDeleteModal()"></div>

        <!-- Modal container -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full mx-auto"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 transform scale-100" 
                 x-transition:leave-end="opacity-0 transform scale-95"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            Delete Lab Test
                        </h3>
                        <button @click="closeDeleteModal()" class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                            <i class="fas fa-trash-alt text-red-600 dark:text-red-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700 dark:text-gray-300 text-lg font-medium mb-2">
                                Are you sure you want to delete this lab test?
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <strong>{{ $labTest->test_name }}</strong> will be permanently deleted and cannot be recovered.
                            </p>
                            <div class="px-4 py-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <p class="text-sm text-red-800 dark:text-red-200">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <strong>Warning:</strong> This action cannot be undone. All data associated with this lab test will be permanently removed.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" @click="closeDeleteModal()" 
                                class="flex-1 px-4 py-3 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white text-base font-medium rounded-xl hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <form action="{{ route('patient.lab-tests.destroy', $labTest) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-red-600 text-white text-base font-medium rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all duration-200">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Delete Lab Test
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Main page component
    Alpine.data('labTestPage', () => ({
        showDeleteModal: false,

        closeDeleteModal() {
            this.showDeleteModal = false;
        },

        init() {
            // Handle escape key to close modal
            this.$watch('showDeleteModal', (value) => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                    
                    // Add escape key listener
                    const escapeHandler = (e) => {
                        if (e.key === 'Escape') {
                            this.closeDeleteModal();
                            document.removeEventListener('keydown', escapeHandler);
                        }
                    };
                    document.addEventListener('keydown', escapeHandler);
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
    }));
});
</script>

<style>
[x-cloak] {
    display: none !important;
}
</style>
@endpush

@endsection
