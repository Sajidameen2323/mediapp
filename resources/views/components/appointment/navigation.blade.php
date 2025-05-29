{{-- Navigation Controls Component --}}
@props(['currentStep' => 1])

<div class="flex flex-col sm:flex-row justify-between items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
    {{-- Back Button --}}
    <button type="button" 
            id="back_btn" 
            class="order-2 sm:order-1 w-full sm:w-auto px-6 py-3 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
            {{ $currentStep <= 1 ? 'disabled' : '' }}>
        <i class="fas fa-arrow-left mr-2"></i>
        Back
    </button>

    {{-- Step Information --}}
    <div class="order-1 sm:order-2 text-center">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Step <span id="current_step_display">{{ $currentStep }}</span> of 4
        </div>
        <div id="step_title" class="font-semibold text-gray-900 dark:text-gray-100">
            @switch($currentStep)
                @case(1) Find Your Doctor @break
                @case(2) Choose Service @break
                @case(3) Select Date & Time @break
                @case(4) Confirm Booking @break
                @default Find Your Doctor
            @endswitch
        </div>
    </div>

    {{-- Next/Submit Button --}}
    <div class="order-3 w-full sm:w-auto">
        <button type="button" 
                id="next_btn" 
                class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
            <span id="next_btn_text">
                @if($currentStep < 4)
                    Next
                    <i class="fas fa-arrow-right ml-2"></i>
                @else
                    <i class="fas fa-calendar-check mr-2"></i>
                    Book Appointment
                @endif
            </span>
        </button>

        {{-- Submit Button (Hidden by default) --}}
        <button type="submit" 
                id="submit_btn" 
                class="hidden w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 dark:from-green-500 dark:to-green-600 dark:hover:from-green-600 dark:hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-calendar-check mr-2"></i>
            <span id="submit_btn_text">Confirm Appointment</span>
        </button>
    </div>
</div>
