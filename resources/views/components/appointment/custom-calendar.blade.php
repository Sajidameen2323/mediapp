{{-- Modern Appointment Calendar Component --}}
<div id="appointment-calendar-container" class="appointment-calendar-wrapper relative">
    {{-- Calendar will be rendered here by JavaScript --}}
    
    {{-- Loading Overlay for Calendar Fetch Requests --}}
    <div id="calendar-loader" class="hidden absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 backdrop-blur-sm flex items-center justify-center z-10 rounded-xl">
        <div class="flex flex-col items-center space-y-4">
            {{-- Animated Spinner --}}
            <div class="relative">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 dark:border-gray-600"></div>
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 dark:border-blue-400 border-t-transparent absolute top-0 left-0"></div>
            </div>
            
            {{-- Loading Text with Icon --}}
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 text-gray-600 dark:text-gray-400">
                    <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400"></i>
                    <span class="font-medium" id="loader-text">Loading calendar...</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1" id="loader-subtitle">Please wait while we fetch available dates</p>
            </div>
        </div>
    </div>

    {{-- Error State --}}
    <div id="calendar-error" class="hidden">
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 text-center">
            <div class="flex flex-col items-center space-y-4">
                <i class="fas fa-exclamation-triangle text-4xl text-red-500 dark:text-red-400"></i>
                <div>
                    <h4 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Unable to load calendar</h4>
                    <p class="text-red-700 dark:text-red-300 mb-4" id="error-message">Something went wrong while loading the calendar data.</p>
                    <button type="button" id="retry-calendar" class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <i class="fas fa-redo mr-2"></i>
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden inputs for form submission --}}
<input type="hidden" id="appointment_date" name="appointment_date" value="">
<input type="hidden" id="appointment_time" name="appointment_time" value="">

{{-- JavaScript Helper Functions for Loader Control --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calendar loader control functions
    window.CalendarLoader = {
        show: function(text = 'Loading calendar...', subtitle = 'Please wait while we fetch available dates') {
            const loader = document.getElementById('calendar-loader');
            const loaderText = document.getElementById('loader-text');
            const loaderSubtitle = document.getElementById('loader-subtitle');
            
            if (loader) {
                if (loaderText) loaderText.textContent = text;
                if (loaderSubtitle) loaderSubtitle.textContent = subtitle;
                loader.classList.remove('hidden');
            }
        },
        
        hide: function() {
            const loader = document.getElementById('calendar-loader');
            if (loader) {
                loader.classList.add('hidden');
            }
        },
        
        showError: function(message = 'Something went wrong while loading the calendar data.') {
            this.hide();
            const errorDiv = document.getElementById('calendar-error');
            const errorMessage = document.getElementById('error-message');
            
            if (errorDiv) {
                if (errorMessage) errorMessage.textContent = message;
                errorDiv.classList.remove('hidden');
            }
        },
        
        hideError: function() {
            const errorDiv = document.getElementById('calendar-error');
            if (errorDiv) {
                errorDiv.classList.add('hidden');
            }
        },
        
        showDatesLoader: function() {
            this.show('Loading available dates...', 'Fetching appointment slots for selected doctor and service');
        },
        
        showSlotsLoader: function() {
            this.show('Loading time slots...', 'Getting available appointment times for selected date');
        },
        
        showDoctorLoader: function() {
            this.show('Loading doctor information...', 'Fetching doctor details and availability');
        }
    };
    
    // Retry button functionality
    const retryButton = document.getElementById('retry-calendar');
    if (retryButton) {
        retryButton.addEventListener('click', function() {
            window.CalendarLoader.hideError();
            // Trigger a reload of calendar data
            if (window.AppointmentCalendar && window.calendarInstance) {
                window.CalendarLoader.show();
                window.calendarInstance.loadAvailableDates();
            }
        });
    }
});
</script>

{{-- Styles for enhanced loader appearance --}}
<style>
.appointment-calendar-wrapper {
    min-height: 400px;
}

#calendar-loader {
    min-height: 400px;
}

/* Pulse animation for loading state */
@keyframes pulse-glow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.calendar-loading-pulse {
    animation: pulse-glow 2s infinite;
}

/* Enhanced spinner animation */
.spinner-enhanced {
    position: relative;
}

.spinner-enhanced::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: 50%;
    border: 2px solid transparent;
    border-top-color: #3b82f6;
    animation: spin 1.5s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Dark mode optimizations */
@media (prefers-color-scheme: dark) {
    #calendar-loader {
        backdrop-filter: blur(8px);
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    #calendar-loader .space-y-4 {
        space-y: 3;
    }
    
    #calendar-loader h4 {
        font-size: 1rem;
    }
    
    #calendar-loader .h-12.w-12 {
        height: 2.5rem;
        width: 2.5rem;
    }
}
</style>


