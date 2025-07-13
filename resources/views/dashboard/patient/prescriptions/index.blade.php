@extends('layouts.patient')

@section('title', 'My Prescriptions')

@section('content')


<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Prescriptions</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">View and manage your medication prescriptions</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        <i class="fas fa-prescription-bottle-alt mr-2"></i>
                        {{ $prescriptions->total() }} Total
                    </span>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('patient.prescriptions.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-1"></i>
                                Search
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search prescriptions, doctors..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                        </div>

                        <!-- Doctor Filter -->
                        <div>
                            <label for="doctor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-user-md mr-1"></i>
                                Doctor
                            </label>
                            <select id="doctor" 
                                    name="doctor" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-flag mr-1"></i>
                                Status
                            </label>                            <select id="status" 
                                    name="status" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">All Status</option>
                                @foreach($availableStatuses as $statusKey => $statusLabel)
                                    <option value="{{ $statusKey }}" {{ request('status') == $statusKey ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Date From
                            </label>
                            <input type="date" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4">
                        <div class="flex items-center space-x-3">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <i class="fas fa-filter mr-2"></i>
                                Apply Filters
                            </button>
                            
                            @if(request()->hasAny(['search', 'doctor', 'status', 'date_from', 'date_to']))
                                <a href="{{ route('patient.prescriptions.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                    <i class="fas fa-times mr-2"></i>
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                        
                        <div class="mt-3 sm:mt-0 text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ $prescriptions->firstItem() ?? 0 }} to {{ $prescriptions->lastItem() ?? 0 }} of {{ $prescriptions->total() }} results
                        </div>                    </div>
                </form>            </div>
        </div>

        @if($prescriptions->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500">
                    <i class="fas fa-prescription-bottle-alt text-6xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No prescriptions found</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    @if(request()->hasAny(['search', 'doctor', 'status', 'date_from', 'date_to']))
                        Try adjusting your filters to find more prescriptions.
                    @else
                        Your prescriptions from medical consultations will appear here.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'doctor', 'status', 'date_from', 'date_to']))
                    <div class="mt-6">
                        <a href="{{ route('patient.prescriptions.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- Prescriptions Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($prescriptions as $prescription)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-lg dark:hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-prescription-bottle-alt text-blue-600 dark:text-blue-300"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Prescription #{{ $prescription->prescription_number ?? $prescription->id }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $prescription->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200' : '' }}
                                    {{ $prescription->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200' : '' }}
                                    {{ $prescription->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200' : '' }}
                                    {{ $prescription->status === 'expired' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200' : '' }}">
                                    <i class="fas fa-circle mr-1.5" style="font-size: 0.5rem;"></i>
                                    {{ ucfirst($prescription->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <!-- Doctor Information -->
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user-md text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Prescribed by:</span>
                                        <p class="text-sm text-gray-900 dark:text-white font-semibold">
                                            @if($prescription->doctor && $prescription->doctor->user)
                                                {{ $prescription->doctor->user->name }}
                                            @elseif($prescription->medicalReport && $prescription->medicalReport->doctor && $prescription->medicalReport->doctor->user)
                                                {{ $prescription->medicalReport->doctor->user->name }}
                                            @else
                                                <span class="text-gray-500 dark:text-gray-400">Doctor information not available</span>
                                            @endif
                                        </p>
                                        @if($prescription->doctor && $prescription->doctor->specialization)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $prescription->doctor->specialization }}</p>
                                        @elseif($prescription->medicalReport && $prescription->medicalReport->doctor && $prescription->medicalReport->doctor->specialization)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $prescription->medicalReport->doctor->specialization }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Medical Report -->
                                @if($prescription->medicalReport)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-file-medical text-gray-600 dark:text-gray-300 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis:</span>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $prescription->medicalReport->diagnosis ?? 'General Consultation' }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Medications Count -->
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-pills text-gray-600 dark:text-gray-300 text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Medications:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $prescription->prescriptionMedications->count() }} prescribed</p>
                                    </div>
                                </div>

                                <!-- Validity Date -->
                                @if($prescription->valid_until)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-gray-600 dark:text-gray-300 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Valid until:</span>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $prescription->valid_until->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($prescription->notes)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-sticky-note text-gray-600 dark:text-gray-300 text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Notes:</span>
                                            <p class="text-sm text-gray-900 dark:text-white">{{ Str::limit($prescription->notes, 80) }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('patient.prescriptions.show', $prescription) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-200 dark:hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                                
                                @if($prescription->canBeOrdered())
                                    <a href="{{ route('patient.prescriptions.order-pharmacy', $prescription) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                        <i class="fas fa-store mr-2"></i>
                                        Order Pharmacy
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($prescriptions->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        {{ $prescriptions->links() }}
                    </div>
                </div>
            @endif
        @endif    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.querySelector('form');
        const applyFiltersBtn = document.querySelector('button[type="submit"]');
        
        // Manual form submission on button click
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Add loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
                this.disabled = true;
                
                // Submit form
                filterForm.submit();
            });
        }
        
        // Enhanced search with Enter key
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterForm.submit();
                }
            });
        }

        // Quick filter on select change (optional - can be removed if not desired)
        const selectElements = filterForm.querySelectorAll('select');
        selectElements.forEach(select => {
            select.addEventListener('change', function() {
                // Optional: Auto-submit on select change
                // filterForm.submit();
            });
        });

        // Date input change handlers
        const dateInputs = filterForm.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Optional: Auto-submit on date change
                // filterForm.submit();
            });
        });

        // Clear individual filters
        document.querySelectorAll('[data-clear-filter]').forEach(button => {
            button.addEventListener('click', function() {
                const filterName = this.dataset.clearFilter;
                const filterInput = document.querySelector(`[name="${filterName}"]`);
                if (filterInput) {
                    filterInput.value = '';
                    filterForm.submit();
                }
            });
        });

        // Show active filters count
        function updateActiveFiltersCount() {
            const activeFilters = [];
            const formData = new FormData(filterForm);
            
            for (let [key, value] of formData.entries()) {
                if (value && key !== '_token' && key !== 'page') {
                    activeFilters.push(key);
                }
            }
              // Update UI to show active filters count if needed
        }

        // Call on page load
        updateActiveFiltersCount();
    });
</script>
@endpush
@endsection
