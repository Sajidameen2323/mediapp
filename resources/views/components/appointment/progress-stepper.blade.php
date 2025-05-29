{{-- Multi-step Progress Indicator Component --}}
@props(['currentStep' => 1, 'totalSteps' => 4])

<div class="progress-stepper w-full max-w-4xl mx-auto mb-8">
    <div class="flex items-center justify-between">
        @for ($step = 1; $step <= $totalSteps; $step++)
            <div class="step flex items-center {{ $step < $totalSteps ? 'flex-1' : '' }}" data-step="{{ $step }}">
                {{-- Step Circle --}}
                <div class="step-circle relative flex items-center justify-center w-10 h-10 rounded-full border-2 
                    {{ $step <= $currentStep 
                        ? 'bg-blue-600 border-blue-600 text-white dark:bg-blue-500 dark:border-blue-500' 
                        : 'bg-gray-200 border-gray-300 text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400' 
                    }}
                    transition-all duration-300">
                    @if ($step < $currentStep)
                        <i class="fas fa-check text-sm"></i>
                    @else
                        <span class="text-sm font-semibold">{{ $step }}</span>
                    @endif
                </div>
                
                {{-- Step Label --}}
                <div class="step-label ml-3 text-sm font-medium 
                    {{ $step <= $currentStep 
                        ? 'text-blue-600 dark:text-blue-400' 
                        : 'text-gray-500 dark:text-gray-400' 
                    }}">
                    @switch($step)
                        @case(1) Find Doctor @break
                        @case(2) Choose Service @break
                        @case(3) Select Time @break
                        @case(4) Confirm @break
                    @endswitch
                </div>
                
                {{-- Connector Line --}}
                @if ($step < $totalSteps)
                    <div class="step-connector flex-1 h-0.5 mx-4 
                        {{ $step < $currentStep 
                            ? 'bg-blue-600 dark:bg-blue-500' 
                            : 'bg-gray-300 dark:bg-gray-600' 
                        }}
                        transition-all duration-300">
                    </div>
                @endif
            </div>
        @endfor
    </div>
</div>
