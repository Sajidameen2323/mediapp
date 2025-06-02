/**
 * Appointment Reschedule Calendar System
 * Specialized calendar for rescheduling existing appointments
 * Extends the base AppointmentCalendar with reschedule-specific functionality
 */
class AppointmentRescheduleCalendar extends AppointmentCalendar {
    constructor(containerId, options = {}) {
        // Default reschedule-specific options
        const defaultOptions = {
            // Reschedule specific settings
            currentAppointmentDate: null,
            currentAppointmentTime: null,
            minRescheduleHours: 24, // Minimum hours before appointment to allow reschedule

            // Inherited options from base class
            doctorId: null,
            serviceId: null,
            onDateSelect: null,
            onTimeSelect: null,
            ...options
        };

        super(containerId, defaultOptions);

        // Reschedule-specific properties
        this.currentAppointmentDate = this.options.currentAppointmentDate;
        this.currentAppointmentTime = this.options.currentAppointmentTime;
        this.minRescheduleHours = this.options.minRescheduleHours || 24;

        // Calculate reschedule deadline
        this.rescheduleDeadline = this.calculateRescheduleDeadline();
    }

    /**
     * Calculate the deadline for rescheduling based on current appointment
     */
    calculateRescheduleDeadline() {
        if (!this.currentAppointmentDate || !this.currentAppointmentTime) {
            return null;
        }

        const appointmentDateTime = new Date(`${this.currentAppointmentDate}T${this.currentAppointmentTime}`);
        const deadline = new Date(appointmentDateTime.getTime() - (this.minRescheduleHours * 60 * 60 * 1000));
        return deadline;
    }

    /**
     * Override date validation to include reschedule-specific rules
     */
    isDateSelectable(date) {
        // First check base class validation
        if (!super.isDateSelectable || !super.isDateSelectable(date)) {
            return false;
        }

        // Check if we're past the reschedule deadline
        if (this.rescheduleDeadline && new Date() > this.rescheduleDeadline) {
            return false;
        }

        // Don't allow selecting the same date as current appointment
        // (unless different time slots are available)
        const dateString = this.formatDate(date);
        if (dateString === this.currentAppointmentDate) {
            // Allow same date only if different time slots are available
            return true; // We'll validate the time separately
        }

        return true;
    }

    /**
     * Override time slot validation for reschedule-specific rules
     */
    isTimeSlotSelectable(timeSlot, selectedDate) {
        // Check if it's the same date and time as current appointment
        const selectedDateString = this.formatDate(selectedDate);
        if (selectedDateString === this.currentAppointmentDate &&
            timeSlot.start_time === this.currentAppointmentTime) {
            return false; // Can't reschedule to same date/time
        }

        return true;
    }

    /**
     * Override time slot rendering to exclude current appointment slot
     */
    renderTimeSlots() {
        const container = this.container.querySelector('.slots-container');
        if (!container) return;

        container.innerHTML = '';

        this.availableSlots.forEach(slot => {
            // Check if this slot is selectable for reschedule
            if (!this.isTimeSlotSelectable(slot, this.selectedDate)) {
                return; // Skip current appointment slot
            }

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'time-slot-btn px-4 py-3 text-sm font-medium border rounded-lg transition-all duration-200 hover:scale-105';
            button.textContent = this.formatTime(slot.start_time);
            button.dataset.time = slot.start_time;

            // Check if this is the currently selected time
            if (this.selectedTime === this.formatTimeForSubmission(slot.start_time)) {
                button.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
            } else {
                button.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100', 'border-gray-300', 'dark:border-gray-600', 'hover:border-blue-300', 'hover:bg-blue-50', 'dark:hover:bg-blue-900/20');
            }

            button.addEventListener('click', () => this.selectTime(slot.start_time));
            container.appendChild(button);
        });

        // Show message if no slots available due to current appointment
        if (container.children.length === 0 && this.availableSlots.length > 0) {
            const message = document.createElement('div');
            message.className = 'text-center py-4 text-gray-500 dark:text-gray-400 text-sm';
            message.innerHTML = `
                <i class="fas fa-info-circle mb-2"></i>
                <p>Please select a different date. The selected date only has your current appointment time available.</p>
            `;
            container.appendChild(message);
        }
    }

    /**
     * Override the template to include reschedule-specific UI elements
     */
    getTemplate() {
        const baseTemplate = super.getTemplate();

        // Add reschedule warning if we're close to deadline
        let rescheduleWarning = '';
        if (this.rescheduleDeadline) {
            const now = new Date();
            const hoursUntilDeadline = Math.max(0, (this.rescheduleDeadline - now) / (1000 * 60 * 60));

            if (hoursUntilDeadline < 48) { // Show warning if less than 48 hours
                rescheduleWarning = `
                    <div class="reschedule-warning bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Reschedule Deadline Approaching</h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    You have ${Math.floor(hoursUntilDeadline)} hours remaining to reschedule this appointment.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        // Insert the warning after the calendar header
        return baseTemplate.replace(
            '<!-- Calendar Navigation -->',
            rescheduleWarning + '<!-- Calendar Navigation -->'
        );
    }

    /**
     * Initialize with appointment data
     */
    initializeWithAppointment(appointmentData) {
        this.currentAppointmentDate = appointmentData.date;
        this.currentAppointmentTime = appointmentData.time;
        this.options.doctorId = appointmentData.doctorId;
        this.options.serviceId = appointmentData.serviceId;

        // Recalculate deadline
        this.rescheduleDeadline = this.calculateRescheduleDeadline();

        // Re-render with new data
        this.render();
        this.loadAvailableDates();
    }    /**
     * Override time selection to ensure proper format for Laravel validation
     */
    selectTime(time) {
        // Convert time to H:i format if needed (Laravel validation expects this format)
        const formattedTime = this.formatTimeForSubmission(time);
        this.selectedTime = formattedTime;
        this.renderTimeSlots();

        if (this.options.onTimeSelect) {
            this.options.onTimeSelect(formattedTime, this.formatDate(this.selectedDate));
        }
    }

    /**
     * Format time for submission to ensure H:i format (24-hour format without seconds)
     * Laravel validation expects H:i format like "14:30", not "14:30:00"
     */
    formatTimeForSubmission(timeString) {
        if (!timeString || typeof timeString !== 'string') {
            return timeString;
        }

        // If time includes seconds (H:i:s), convert to H:i
        const timeParts = timeString.split(':');
        if (timeParts.length >= 2) {
            return `${timeParts[0]}:${timeParts[1]}`;
        }

        return timeString;
    }
    getRescheduleValidation() {
        const validation = {
            isValid: false,
            errors: [],
            warnings: []
        };

        // Check if past deadline
        // if (this.rescheduleDeadline && new Date() > this.rescheduleDeadline) {
        //     validation.errors.push('Reschedule deadline has passed. Contact support for assistance.');
        //     return validation;
        // }

        // Check if date and time are selected
        if (!this.selectedDate || !this.selectedTime) {
            validation.errors.push('Please select both a new date and time.');
            return validation;
        }

        // Check if different from current appointment
        const selectedDateString = this.formatDate(this.selectedDate);
        if (selectedDateString === this.currentAppointmentDate &&
            this.selectedTime === this.currentAppointmentTime) {
            validation.errors.push('Please select a different date or time from your current appointment.');
            return validation;
        }

        // Add warnings
        const hoursUntilDeadline = this.rescheduleDeadline ?
            (this.rescheduleDeadline - new Date()) / (1000 * 60 * 60) : Infinity;

        if (hoursUntilDeadline < 24) {
            validation.warnings.push('Rescheduling close to appointment time may incur fees.');
        }

        validation.isValid = true;
        return validation;
    }
}

// Export for use in other scripts
window.AppointmentRescheduleCalendar = AppointmentRescheduleCalendar;
