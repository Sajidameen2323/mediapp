@extends('layouts.app')

@section('title', 'Laboratory Details - Medi App')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Laboratory Details</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">View complete laboratory information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.laboratories.edit', $laboratory) }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-edit"></i>
                Edit Laboratory
            </a>
            <a href="{{ route('admin.laboratories.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Laboratory Overview -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center">
                                <i class="fas fa-flask text-white text-2xl"></i>
                            </div>
                        </div>                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $laboratory->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $laboratory->description }}</p>
                            <div class="mt-4">
                                <span id="availability-status" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $laboratory->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <i id="availability-icon" class="fas {{ $laboratory->is_available ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                    <span id="availability-text">{{ $laboratory->is_available ? 'Available' : 'Unavailable' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">License Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->license_number }}</dd>
                        </div>
                        @if($laboratory->accreditation)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Accreditation</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->accreditation }}</dd>
                            </div>
                        @endif
                        @if($laboratory->website)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                                <dd class="mt-1 text-sm">
                                    <a href="{{ $laboratory->website }}" target="_blank" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $laboratory->website }}
                                        <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Registered</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->created_at->format('M d, Y') }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Offered -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Services Offered</h3>
                </div>
                <div class="p-6">
                    @if(is_array($laboratory->services_offered) && count($laboratory->services_offered) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($laboratory->services_offered as $service)
                                <div class="flex items-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $service }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-list-ul text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400">No services specified</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Working Hours -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Working Hours</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Operating Hours</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($laboratory->opening_time)->format('h:i A') }} - 
                                {{ \Carbon\Carbon::parse($laboratory->closing_time)->format('h:i A') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Working Days</dt>
                            <dd class="mt-1">
                                @if(is_array($laboratory->working_days) && count($laboratory->working_days) > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($laboratory->working_days as $day)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ ucfirst($day) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Not specified</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Location -->
        <div class="space-y-6">
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contact Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Person</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1">
                            <a href="mailto:{{ $laboratory->user->email }}" 
                               class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $laboratory->user->email }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="mt-1">
                            <a href="tel:{{ $laboratory->user->phone_number }}" 
                               class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                {{ $laboratory->user->phone_number }}
                            </a>
                        </dd>
                    </div>
                    @if($laboratory->emergency_contact)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact</dt>
                            <dd class="mt-1">
                                <a href="tel:{{ $laboratory->emergency_contact }}" 
                                   class="text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                    {{ $laboratory->emergency_contact }}
                                </a>
                            </dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Location</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">City</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->city }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">State</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->state }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Postal Code</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->postal_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Country</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $laboratory->country }}</dd>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                </div>                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.laboratories.edit', $laboratory) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Information
                    </a>
                    
                    <button id="toggle-availability-btn" 
                            data-laboratory-id="{{ $laboratory->id }}"
                            data-current-status="{{ $laboratory->is_available ? 'true' : 'false' }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 {{ $laboratory->is_available ? 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <i id="toggle-icon" class="fas {{ $laboratory->is_available ? 'fa-pause' : 'fa-play' }} mr-2"></i>
                        <span id="toggle-text">{{ $laboratory->is_available ? 'Disable' : 'Enable' }} Laboratory</span>
                    </button>
                    
                    <form action="{{ route('admin.laboratories.destroy', $laboratory) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this laboratory? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/30 transition-all duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Laboratory
                        </button>
                    </form>
                </div>
            </div>        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-availability-btn');
    const statusSpan = document.getElementById('availability-status');
    const statusIcon = document.getElementById('availability-icon');
    const statusText = document.getElementById('availability-text');
    const toggleText = document.getElementById('toggle-text');
    const toggleIcon = document.getElementById('toggle-icon');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const laboratoryId = this.dataset.laboratoryId;
            const currentStatus = this.dataset.currentStatus === 'true';
            
            // Disable button during request
            toggleBtn.disabled = true;
            toggleBtn.style.opacity = '0.6';
            
            // Set up CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`/admin/laboratories/${laboratoryId}/toggle-availability`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newStatus = data.is_available;
                    
                    // Update data attribute
                    toggleBtn.dataset.currentStatus = newStatus ? 'true' : 'false';
                    
                    // Update status display
                    if (newStatus) {
                        statusSpan.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                        statusIcon.className = 'fas fa-check-circle mr-2';
                        statusText.textContent = 'Available';
                        toggleText.textContent = 'Disable Laboratory';
                        toggleIcon.className = 'fas fa-pause mr-2';
                        toggleBtn.className = 'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 bg-orange-600 hover:bg-orange-700 focus:ring-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2';
                    } else {
                        statusSpan.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                        statusIcon.className = 'fas fa-times-circle mr-2';
                        statusText.textContent = 'Unavailable';
                        toggleText.textContent = 'Enable Laboratory';
                        toggleIcon.className = 'fas fa-play mr-2';
                        toggleBtn.className = 'w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white transition-all duration-200 bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2';
                    }
                    
                    // Show success message (optional)
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Failed to update laboratory status', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while updating laboratory status', 'error');
            })
            .finally(() => {
                // Re-enable button
                toggleBtn.disabled = false;
                toggleBtn.style.opacity = '1';
            });
        });
    }
    
    // Simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
});
</script>
@endpush
@endsection
