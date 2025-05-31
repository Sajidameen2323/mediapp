/**
 * Debug extension for the AppointmentCalendar class
 * Import this file after the main appointment-calendar.js to add debug functionality
 */

// Add debug methods to AppointmentCalendar prototype
if (window.AppointmentCalendar) {
    // Debug mode toggle
    AppointmentCalendar.prototype.debug = function(enabled = true) {
        this._debug = enabled;
        if (enabled) {
            console.log('Calendar debug mode enabled');
            this.logState();
        }
        return this;
    };
    
    // Log current state of the calendar
    AppointmentCalendar.prototype.logState = function() {
        if (!this._debug) return;
        
        console.log('Calendar State:', {
            currentDate: new Date(this.currentDate),
            selectedDate: this.selectedDate ? new Date(this.selectedDate) : null,
            selectedTime: this.selectedTime,
            minDate: this.minDate ? new Date(this.minDate) : null,
            maxDate: this.maxDate ? new Date(this.maxDate) : null,
            availableDatesCount: this.availableDates ? this.availableDates.size : 0,
            monthYear: this.currentDate ? `${this.currentDate.getMonth() + 1}/${this.currentDate.getFullYear()}` : 'Not set'
        });
    };

    // Log date calculations for debugging
    AppointmentCalendar.prototype.debugDateCalculations = function() {
        if (!this._debug) return;
        
        // Test several date calculations
        const testDate = new Date();
        console.group('Calendar Date Calculations Debug');
        console.log('Test date:', testDate);
        console.log('Formatted test date:', this.formatDate(testDate));
        console.log('Is today?', this.isToday(testDate));
        
        // Test month navigation
        const nextMonth = new Date(testDate);
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        console.log('Next month:', nextMonth);
        
        // Test date formatting with different inputs
        const dateStr = this.formatDate(testDate);
        const parsedDate = new Date(dateStr);
        console.log('Date string to Date object:', { 
            original: testDate,
            dateString: dateStr,
            parsed: parsedDate,
            matches: this.isSameDate(testDate, parsedDate)
        });
        
        console.groupEnd();
    };
    
    // Check if calendar displays dates in correct columns
    AppointmentCalendar.prototype.verifyDayOfWeekAlignment = function() {
        if (!this._debug) return;
        
        const dayElements = this.container.querySelectorAll('.calendar-day');
        const issues = [];
        
        dayElements.forEach((el, index) => {
            if (el.dataset.date) {
                const date = new Date(el.dataset.date);
                const dayOfWeek = date.getDay(); // 0 = Sunday, 1 = Monday, etc.
                const expectedColumn = index % 7;
                
                if (dayOfWeek !== expectedColumn) {
                    issues.push({
                        date: el.dataset.date,
                        element: index,
                        expectedColumn: dayOfWeek,
                        actualColumn: expectedColumn
                    });
                }
            }
        });
        
        if (issues.length > 0) {
            console.error('Calendar day alignment issues detected:', issues);
            return false;
        } else {
            console.log('Calendar day alignment verified: OK');
            return true;
        }
    };
    
    console.log('AppointmentCalendar debug methods added');
} else {
    console.error('AppointmentCalendar class not found! Make sure to load appointment-calendar.js first.');
}
