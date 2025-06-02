{{-- 
    Appointment Action Buttons Component
    Provides consistent action buttons for appointments based on permissions
    
    Props:
    - appointment: Appointment model instance
    - config: AppointmentConfig model instance
    - layout: 'horizontal' or 'vertical' (default: horizontal)
    - size: 'sm', 'md', 'lg' (default: md)
    - showView: boolean (default: true)
    - showPrint: boolean (default: true for confirmed/completed)
    - showRating: boolean (default: true for completed without rating)
    - cancelAction: string - custom action for cancel button (default: form submission)
    - ratingAction: string - custom action for rating button
--}}

@props([
    'appointment',
    'config' => null,
    'layout' => 'horizontal',
    'size' => 'md',
    'showView' => true,
    'showPrint' => null,
    'showRating' => null,
    'cancelAction' => null,
    'ratingAction' => null
])

@php
    // Get config if not provided
    if (!$config) {
        $config = \App\Models\AppointmentConfig::getActive();
    }
    
    // Set default values for optional props
    $showPrint = $showPrint ?? in_array($appointment->status, ['confirmed', 'completed']);
    $showRating = $showRating ?? ($appointment->status === 'completed' && !$appointment->rating);
    
    // Define size classes
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    
    $buttonSize = $sizeClasses[$size] ?? $sizeClasses['md'];
    $iconSize = $size === 'lg' ? 'text-base' : 'text-sm';
    
    // Define layout classes
    $layoutClasses = [
        'horizontal' => 'flex flex-wrap gap-2',
        'vertical' => 'flex flex-col gap-2'
    ];
    
    $containerClass = $layoutClasses[$layout] ?? $layoutClasses['horizontal'];
    
    // Full width class for vertical layout
    $buttonWidth = $layout === 'vertical' ? 'w-full' : '';
@endphp

<div class="{{ $containerClass }}">
    {{-- View Details Button --}}
    @if ($showView)
        <a href="{{ route('patient.appointments.show', $appointment) }}"
           class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            <i class="fas fa-eye mr-2 {{ $iconSize }}"></i>
            <span>View Details</span>
        </a>
    @endif

    {{-- Reschedule Button --}}
    @if ($appointment->canBeRescheduled())
        <a href="{{ route('patient.appointments.reschedule', $appointment) }}"
           class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            <i class="fas fa-calendar-alt mr-2 {{ $iconSize }}"></i>
            <span>Reschedule</span>
        </a>
    @elseif ($appointment->status === 'pending')
        {{-- Disabled Reschedule Button with Tooltip --}}
        <div class="relative group {{ $buttonWidth }}">
            <button type="button" disabled
                    class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-gray-400 dark:bg-gray-600 text-gray-200 dark:text-gray-400 font-medium rounded-lg cursor-not-allowed opacity-50">
                <i class="fas fa-calendar-alt mr-2 {{ $iconSize }}"></i>
                <span>Reschedule</span>
            </button>
            {{-- Tooltip --}}
            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                Cannot reschedule: Less than {{ $config->reschedule_hours_limit ?? 24 }} hours before appointment
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
            </div>
        </div>
    @endif

    {{-- Cancel Button --}}
    @if ($appointment->canBeCancelled())
        @if ($cancelAction)
            {{-- Custom Cancel Action --}}
            <button type="button" onclick="{{ $cancelAction }}"
                    class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-times mr-2 {{ $iconSize }}"></i>
                <span>Cancel</span>
            </button>
        @else
            {{-- Default Cancel Form --}}
            <form action="{{ route('patient.appointments.cancel', $appointment) }}"
                  method="POST" class="inline-block {{ $buttonWidth }}"
                  onsubmit="return confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')">
                @csrf
                @method('PATCH')
                <input type="hidden" name="cancellation_reason" value="user_cancelled">
                <button type="submit"
                        class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    <i class="fas fa-times mr-2 {{ $iconSize }}"></i>
                    <span>Cancel</span>
                </button>
            </form>
        @endif
    @elseif ($appointment->status === 'pending')
        {{-- Disabled Cancel Button with Tooltip --}}
        <div class="relative group {{ $buttonWidth }}">
            <button type="button" disabled
                    class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-gray-400 dark:bg-gray-600 text-gray-200 dark:text-gray-400 font-medium rounded-lg cursor-not-allowed opacity-50">
                <i class="fas fa-times mr-2 {{ $iconSize }}"></i>
                <span>Cancel</span>
            </button>
            {{-- Tooltip --}}
            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 dark:bg-gray-700 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                Cannot cancel: Less than {{ $config->cancellation_hours_limit ?? 24 }} hours before appointment
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
            </div>
        </div>
    @endif

    {{-- Print/Download Button --}}
    @if ($showPrint)
        <button type="button" onclick="window.print()"
                class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-gray-600 hover:bg-gray-700 dark:bg-gray-500 dark:hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            <i class="fas fa-print mr-2 {{ $iconSize }}"></i>
            <span>Print</span>
        </button>
    @endif

    {{-- Rating Button --}}
    @if ($showRating)
        @if ($ratingAction)
            {{-- Custom Rating Action --}}
            <button type="button" onclick="{{ $ratingAction }}"
                    class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-star mr-2 {{ $iconSize }}"></i>
                <span>Rate Appointment</span>
            </button>
        @else
            {{-- Default Rating Link --}}
            <a href="{{ route('patient.appointments.rate', $appointment) }}"
               class="inline-flex items-center justify-center {{ $buttonSize }} {{ $buttonWidth }} bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-500 dark:hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <i class="fas fa-star mr-2 {{ $iconSize }}"></i>
                <span>Rate Appointment</span>
            </a>
        @endif
    @endif
</div>

{{-- Status Information Badge (optional) --}}
@if (in_array($appointment->status, ['cancelled', 'completed', 'no_show']) && !$showRating && !$showPrint)
    <div class="mt-2 text-center">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
            {{ $appointment->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' :
               ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' :
               'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400') }}">
            <i class="fas fa-info-circle mr-1"></i>
            @if ($appointment->status === 'completed')
                Appointment completed
            @elseif ($appointment->status === 'cancelled')
                Appointment cancelled
            @else
                No actions available
            @endif
        </span>
    </div>
@endif
