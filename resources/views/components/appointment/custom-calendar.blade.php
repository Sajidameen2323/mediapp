{{-- Modern Appointment Calendar Component --}}
<div id="appointment-calendar-container" class="appointment-calendar-wrapper relative bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden">
    {{-- Calendar will be rendered here by JavaScript --}}
    
    {{-- Loading Overlay for Calendar Fetch Requests --}}
    <div id="calendar-loader" class="hidden absolute inset-0 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md flex items-center justify-center z-50 rounded-2xl">
        <div class="flex flex-col items-center space-y-6 p-8">
            {{-- Enhanced Animated Spinner --}}
            <div class="relative">
                {{-- Outer rotating ring --}}
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-100 dark:border-gray-700"></div>
                {{-- Inner rotating ring with gradient --}}
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-transparent border-t-blue-600 border-r-indigo-600 dark:border-t-blue-400 dark:border-r-indigo-400 absolute top-0 left-0" style="animation-duration: 0.8s;"></div>
                {{-- Center pulse dot --}}
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-blue-600 dark:bg-blue-400 rounded-full animate-pulse"></div>
            </div>
            
            {{-- Enhanced Loading Text with Icon --}}
            <div class="text-center max-w-sm">
                <div class="flex items-center justify-center space-x-3 text-gray-700 dark:text-gray-300 mb-2">
                    <div class="relative">
                        <i class="fas fa-calendar-alt text-xl text-blue-600 dark:text-blue-400"></i>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                    </div>
                    <span class="font-semibold text-lg" id="loader-text">Loading calendar...</span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed" id="loader-subtitle">Please wait while we fetch available dates</p>
            </div>
        </div>
    </div>

    {{-- Enhanced Error State --}}
    <div id="calendar-error" class="hidden">
        <div class="bg-gradient-to-br from-red-50 via-red-25 to-orange-50 dark:from-red-900/20 dark:via-red-800/10 dark:to-orange-900/20 border border-red-200 dark:border-red-800/50 rounded-2xl p-8 text-center mx-4 my-6 shadow-lg">
            <div class="flex flex-col items-center space-y-6">
                {{-- Animated Error Icon --}}
                <div class="relative">
                    <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 dark:text-red-400 animate-pulse"></i>
                    </div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 dark:bg-red-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-white text-xs"></i>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <h4 class="text-xl font-bold text-red-900 dark:text-red-100">Unable to load calendar</h4>
                    <p class="text-red-700 dark:text-red-300 max-w-md leading-relaxed" id="error-message">Something went wrong while loading the calendar data.</p>
                    
                    {{-- Enhanced Retry Button --}}
                    <button type="button" id="retry-calendar" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 dark:from-red-500 dark:to-red-600 dark:hover:from-red-600 dark:hover:to-red-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-red-500/50 dark:focus:ring-red-400/50 focus:ring-offset-2 dark:focus:ring-offset-gray-800 mt-4">
                        <i class="fas fa-redo mr-3 text-sm"></i>
                        <span>Try Again</span>
                        <div class="ml-2 w-1 h-1 bg-white/50 rounded-full animate-ping"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden inputs for form submission with enhanced styling --}}
<div class="hidden">
    <input type="hidden" id="appointment_date" name="appointment_date" value="">
    <input type="hidden" id="appointment_time" name="appointment_time" value="">
    
    {{-- Additional hidden inputs for reschedule compatibility --}}
    <input type="hidden" id="selected_date" value="">
    <input type="hidden" id="selected_time" value="">
    <input type="hidden" id="new_date" name="new_date" value="">
    <input type="hidden" id="new_time" name="new_time" value="">
</div>

{{-- Enhanced JavaScript Helper Functions for Loader Control --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Calendar loader control functions with smooth transitions
    window.CalendarLoader = {
        show: function(text = 'Loading calendar...', subtitle = 'Please wait while we fetch available dates') {
            const loader = document.getElementById('calendar-loader');
            const loaderText = document.getElementById('loader-text');
            const loaderSubtitle = document.getElementById('loader-subtitle');
            
            if (loader) {
                if (loaderText) {
                    loaderText.textContent = text;
                    // Add typing animation effect
                    loaderText.style.animation = 'none';
                    setTimeout(() => {
                        loaderText.style.animation = 'fadeInUp 0.5s ease-out';
                    }, 10);
                }
                if (loaderSubtitle) {
                    loaderSubtitle.textContent = subtitle;
                    loaderSubtitle.style.animation = 'fadeInUp 0.7s ease-out';
                }
                
                // Smooth fade in
                loader.classList.remove('hidden');
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.transition = 'opacity 0.3s ease-in-out';
                    loader.style.opacity = '1';
                }, 10);
            }
        },
        
        hide: function() {
            const loader = document.getElementById('calendar-loader');
            if (loader) {
                loader.style.transition = 'opacity 0.3s ease-in-out';
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.classList.add('hidden');
                }, 300);
            }
        },
        
        showError: function(message = 'Something went wrong while loading the calendar data.') {
            this.hide();
            const errorDiv = document.getElementById('calendar-error');
            const errorMessage = document.getElementById('error-message');
            
            if (errorDiv) {
                if (errorMessage) {
                    errorMessage.textContent = message;
                }
                
                // Smooth error appearance with shake animation
                errorDiv.classList.remove('hidden');
                errorDiv.style.opacity = '0';
                errorDiv.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    errorDiv.style.transition = 'all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
                    errorDiv.style.opacity = '1';
                    errorDiv.style.transform = 'translateY(0)';
                }, 10);
            }
        },
        
        hideError: function() {
            const errorDiv = document.getElementById('calendar-error');
            if (errorDiv) {
                errorDiv.style.transition = 'all 0.3s ease-in-out';
                errorDiv.style.opacity = '0';
                errorDiv.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    errorDiv.classList.add('hidden');
                }, 300);
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
        },
        
        showRescheduleLoader: function() {
            this.show('Loading reschedule options...', 'Checking availability for your appointment reschedule');
        }
    };
    
    // Enhanced retry button functionality with improved UX
    const retryButton = document.getElementById('retry-calendar');
    if (retryButton) {
        retryButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add loading state to button
            const originalContent = this.innerHTML;
            this.disabled = true;
            this.innerHTML = `
                <div class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></div>
                    <span>Retrying...</span>
                </div>
            `;
            
            window.CalendarLoader.hideError();
            
            // Simulate retry delay for better UX
            setTimeout(() => {
                window.CalendarLoader.show('Retrying connection...', 'Attempting to reload calendar data');
                
                // Trigger a reload of calendar data
                if (window.AppointmentCalendar && window.calendarInstance) {
                    window.calendarInstance.loadAvailableDates();
                } else if (window.AppointmentRescheduleCalendar && window.rescheduleCalendar) {
                    window.rescheduleCalendar.loadAvailableDates();
                } else {
                    // Fallback: reload the page
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
                
                // Reset button after delay
                setTimeout(() => {
                    this.disabled = false;
                    this.innerHTML = originalContent;
                }, 3000);
            }, 500);
        });
    }
    
    // Add global error handler for calendar operations
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('calendar')) {
            console.error('Calendar error detected:', e);
            window.CalendarLoader.showError('A technical error occurred. Please try refreshing the page.');
        }
    });
});
</script>

{{-- Enhanced Styles for Modern Calendar Appearance --}}
<style>
/* Main calendar wrapper with gradient backgrounds */
.appointment-calendar-wrapper {
    min-height: 500px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.appointment-calendar-wrapper:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

/* Enhanced loader styling */
#calendar-loader {
    min-height: 500px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* Smooth animations for various states */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse-glow {
    0%, 100% { 
        opacity: 1;
        transform: scale(1);
    }
    50% { 
        opacity: 0.8;
        transform: scale(1.05);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

/* Enhanced loading pulse animation */
.calendar-loading-pulse {
    animation: pulse-glow 2.5s infinite ease-in-out;
}

/* Modern spinner with gradient effects */
.spinner-enhanced {
    position: relative;
    filter: drop-shadow(0 4px 8px rgba(59, 130, 246, 0.3));
}

.spinner-enhanced::before {
    content: '';
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top: 3px solid #3b82f6;
    border-right: 3px solid #6366f1;
    animation: spin 2s linear infinite;
    filter: blur(0.5px);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Enhanced error state styling */
#calendar-error .bg-gradient-to-br {
    background-image: linear-gradient(135deg, 
        rgba(254, 242, 242, 0.9) 0%, 
        rgba(254, 226, 226, 0.7) 50%, 
        rgba(255, 237, 213, 0.6) 100%);
}

.dark #calendar-error .bg-gradient-to-br {
    background-image: linear-gradient(135deg, 
        rgba(127, 29, 29, 0.2) 0%, 
        rgba(153, 27, 27, 0.1) 50%, 
        rgba(154, 52, 18, 0.2) 100%);
}

/* Button hover effects with modern transitions */
#retry-calendar {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

#retry-calendar::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

#retry-calendar:hover::before {
    left: 100%;
}

#retry-calendar:active {
    transform: scale(0.98);
}

/* Enhanced focus states for accessibility */
#retry-calendar:focus {
    box-shadow: 
        0 0 0 4px rgba(239, 68, 68, 0.3),
        0 10px 25px -5px rgba(239, 68, 68, 0.4);
}

/* Dark mode optimizations */
@media (prefers-color-scheme: dark) {
    .appointment-calendar-wrapper {
        backdrop-filter: blur(8px);
        border-color: rgba(75, 85, 99, 0.3);
    }
    
    #calendar-loader {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }
}

/* Responsive adjustments for mobile devices */
@media (max-width: 640px) {
    .appointment-calendar-wrapper {
        min-height: 400px;
        margin: 0.5rem;
        border-radius: 1rem;
    }
    
    #calendar-loader .space-y-6 {
        space-y: 1rem;
    }
    
    #calendar-loader h4 {
        font-size: 1rem;
    }
    
    #calendar-loader .h-16.w-16 {
        height: 3rem;
        width: 3rem;
    }
    
    #calendar-error {
        margin: 1rem;
    }
    
    #calendar-error .p-8 {
        padding: 1.5rem;
    }
    
    #retry-calendar {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    .appointment-calendar-wrapper {
        min-height: 450px;
    }
}

/* Large screen optimizations */
@media (min-width: 1025px) {
    .appointment-calendar-wrapper {
        min-height: 550px;
    }
    
    .appointment-calendar-wrapper:hover {
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(59, 130, 246, 0.1);
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .appointment-calendar-wrapper {
        border-width: 2px;
        border-color: currentColor;
    }
    
    #retry-calendar {
        border: 2px solid currentColor;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .appointment-calendar-wrapper,
    #calendar-loader,
    #calendar-error,
    #retry-calendar {
        transition: none;
        animation: none;
    }
    
    .animate-spin,
    .animate-pulse,
    .animate-ping {
        animation: none;
    }
    
    .calendar-loading-pulse {
        animation: none;
    }
}

/* Print styles */
@media print {
    .appointment-calendar-wrapper {
        box-shadow: none;
        border: 1px solid #000;
        background: white;
    }
    
    #calendar-loader,
    #calendar-error {
        display: none !important;
    }
}
</style>


