{{-- Enhanced Multi-step Progress Indicator Component --}}
@props(['currentStep' => 1, 'totalSteps' => 5])

<div class="progress-stepper w-full max-w-6xl mx-auto mb-12">
    <div class="relative">
        {{-- Progress Bar Background --}}
        <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
        
        {{-- Progress Bar Fill --}}
        <div class="absolute top-5 left-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-400 dark:to-indigo-500 rounded-full transition-all duration-500 ease-in-out"
             style="width: {{ (($currentStep - 1) / ($totalSteps - 1)) * 100 }}%"></div>
        
        {{-- Steps Container --}}
        <div class="relative flex justify-between items-center">
            @for ($step = 1; $step <= $totalSteps; $step++)
                <div class="step flex flex-col items-center cursor-pointer group" 
                     data-step="{{ $step }}"
                     onclick="window.appointmentBooking?.goToStep?.({{ $step }})">
                    
                    {{-- Step Circle --}}
                    <div class="step-circle relative flex items-center justify-center w-10 h-10 rounded-full border-3 transition-all duration-300 transform group-hover:scale-110
                        {{ $step < $currentStep 
                            ? 'bg-gradient-to-r from-green-500 to-green-600 border-green-500 text-white shadow-lg' 
                            : ($step === $currentStep 
                                ? 'bg-gradient-to-r from-blue-500 to-indigo-600 border-blue-500 text-white shadow-lg ring-4 ring-blue-200 dark:ring-blue-800' 
                                : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 shadow-md'
                            ) 
                        }}">
                        
                        @if ($step < $currentStep)
                            <i class="fas fa-check text-sm font-bold"></i>
                        @elseif ($step === $currentStep)
                            <i class="{{ $stepIcons[$step - 1] ?? 'fas fa-circle' }} text-sm"></i>
                        @else
                            <span class="text-sm font-semibold">{{ $step }}</span>
                        @endif
                        
                        {{-- Pulse Animation for Current Step --}}
                        @if ($step === $currentStep)
                            <div class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-25"></div>
                        @endif
                    </div>
                    
                    {{-- Step Label --}}
                    <div class="step-label mt-3 text-center max-w-24">
                        <div class="text-xs font-semibold leading-tight transition-all duration-300
                            {{ $step <= $currentStep 
                                ? 'text-blue-600 dark:text-blue-400' 
                                : 'text-gray-500 dark:text-gray-400' 
                            }}">
                            @switch($step)
                                @case(1) Find Doctor @break
                                @case(2) Service @break
                                @case(3) Date & Time @break
                                @case(4) Details @break
                                @case(5) Confirm @break
                                @default Step {{ $step }} @break
                            @endswitch
                        </div>
                        
                        {{-- Step Description --}}
                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 hidden sm:block">
                            @switch($step)
                                @case(1) Choose your doctor @break
                                @case(2) Select service @break
                                @case(3) Pick schedule @break
                                @case(4) Add information @break
                                @case(5) Review & book @break
                            @endswitch
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    
    {{-- Current Step Info --}}
    <div class="text-center mt-6">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Step <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $currentStep }}</span> of {{ $totalSteps }}
        </div>
        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">
            @switch($currentStep)
                @case(1) Find Your Preferred Doctor @break
                @case(2) Choose the Right Service @break
                @case(3) Select Your Appointment Time @break
                @case(4) Provide Additional Details @break
                @case(5) Confirm Your Appointment @break
                @default Step {{ $currentStep }} @break
            @endswitch
        </div>
    </div>
</div>

@php
$stepIcons = [
    'fas fa-user-md',     // Doctor
    'fas fa-stethoscope', // Service  
    'fas fa-calendar',    // Date/Time
    'fas fa-edit',        // Details
    'fas fa-check'        // Confirm
];
@endphp
