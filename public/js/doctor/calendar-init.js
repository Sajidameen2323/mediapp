/**
 * Calendar Initialization
 * Initialize the appointment calendar when the page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar
    window.calendar = new SimpleAppointmentCalendar();
    
    // Add keyboard event listeners
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            AppointmentModal.close();
        }
    });
});
