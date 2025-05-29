/**
 * Custom Calendar for Appointment Booking
 * Handles date selection with availability constraints
 */
class CustomCalendar {
    constructor(options = {}) {
        this.options = {
            doctorId: null,
            serviceId: null,
            onDateSelect: null,
            minDate: null,
            maxDate: null,
            ...options
        };
        
        this.currentDate = new Date();
        this.selectedDate = null;
        this.selectableDates = [];
        this.unavailableDates = [];
        
        this.monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.render();
        
        // Load initial data if doctor is set
        if (this.options.doctorId) {
            this.loadSelectableDates();
        }
    }

    bindEvents() {
        // Month navigation
        document.getElementById('prevMonth')?.addEventListener('click', () => this.previousMonth());
        document.getElementById('nextMonth')?.addEventListener('click', () => this.nextMonth());
    }

    setDoctor(doctorId) {
        this.options.doctorId = doctorId;
        this.clearSelection();
        this.loadSelectableDates();
    }

    setService(serviceId) {
        this.options.serviceId = serviceId;
        if (this.options.doctorId) {
            this.clearSelection();
            this.loadSelectableDates();
        }
    }

    clearSelection() {
        this.selectedDate = null;
        this.hideSelectedDateInfo();
        this.render();
    }

    async loadSelectableDates() {
        if (!this.options.doctorId) return;

        this.showLoading();
        this.hideError();

        try {
            const params = new URLSearchParams({
                doctor_id: this.options.doctorId,
                ...(this.options.serviceId && { service_id: this.options.serviceId })
            });

            const response = await fetch(`/api/appointments/selectable-dates?${params}`);
            const data = await response.json();

            if (data.success) {
                this.selectableDates = data.selectable_dates || [];
                this.unavailableDates = data.unavailable_dates || [];
                this.config = data.config || {};
                
                // Update min/max dates from config
                if (data.date_range) {
                    this.options.minDate = new Date(data.date_range.start);
                    this.options.maxDate = new Date(data.date_range.end);
                }
                
                this.render();
            } else {
                this.showError(data.message || 'Failed to load available dates');
            }
        } catch (error) {
            console.error('Error loading selectable dates:', error);
            this.showError('Failed to load calendar data. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    previousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    }

    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    }

    render() {
        this.renderHeader();
        this.renderDays();
    }

    renderHeader() {
        const monthYear = document.getElementById('monthYear');
        if (monthYear) {
            const month = this.monthNames[this.currentDate.getMonth()];
            const year = this.currentDate.getFullYear();
            monthYear.textContent = `${month} ${year}`;
        }
    }

    renderDays() {
        const container = document.getElementById('calendar-days');
        if (!container) return;

        container.innerHTML = '';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        // Add empty cells for days from previous month
        for (let i = 0; i < startingDayOfWeek; i++) {
            const prevMonthDay = new Date(year, month, -startingDayOfWeek + i + 1);
            container.appendChild(this.createDayElement(prevMonthDay, true));
        }

        // Add days of current month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            container.appendChild(this.createDayElement(date, false));
        }

        // Add empty cells for days from next month
        const totalCells = container.children.length;
        const remainingCells = 42 - totalCells; // 6 rows * 7 days
        for (let i = 1; i <= remainingCells && i <= 14; i++) {
            const nextMonthDay = new Date(year, month + 1, i);
            container.appendChild(this.createDayElement(nextMonthDay, true));
        }
    }    createDayElement(date, isOtherMonth) {
        const dayElement = document.createElement('div');
        dayElement.className = 'calendar-day';
        dayElement.textContent = date.getDate();

        const dateString = this.formatDateString(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to start of day for accurate comparison
        const dateToCheck = new Date(date);
        dateToCheck.setHours(0, 0, 0, 0);
        
        const isToday = this.isSameDate(date, today);
        const isSelected = this.selectedDate && this.isSameDate(date, this.selectedDate);
        const isPast = dateToCheck < today;

        // Add base classes
        if (isOtherMonth) {
            dayElement.classList.add('other-month', 'disabled');
            return dayElement; // Early return for other month dates
        }

        dayElement.classList.add('current-month');

        if (isToday) {
            dayElement.classList.add('today');
        }

        if (isSelected) {
            dayElement.classList.add('selected');
        }

        // Check availability for current month dates
        const selectableDate = this.selectableDates.find(d => d.date === dateString);
        const unavailableDate = this.unavailableDates.find(d => d.date === dateString);

        if (isPast && !selectableDate) {
            // Past dates that are not explicitly selectable
            dayElement.classList.add('past', 'disabled');
            dayElement.title = 'Past date - no longer available';
        } else if (selectableDate) {
            // Available dates with slots
            dayElement.classList.add('available', 'clickable');
            dayElement.addEventListener('click', () => this.selectDate(date));
            dayElement.title = `${selectableDate.slots_count} slots available`;
            
            // Add slot indicator
            if (selectableDate.slots_count) {
                const indicator = document.createElement('div');
                indicator.className = 'slot-indicator';
                indicator.textContent = selectableDate.slots_count > 9 ? '9+' : selectableDate.slots_count;
                
                if (selectableDate.slots_count <= 3) {
                    dayElement.classList.add('limited-slots');
                    indicator.classList.add('low-slots');
                }
                
                dayElement.appendChild(indicator);
            }
        } else if (unavailableDate) {
            // Explicitly unavailable dates
            dayElement.classList.add('unavailable', 'disabled');
            dayElement.title = unavailableDate.reason || 'Not available';
        } else {
            // Future dates without defined availability
            dayElement.classList.add('no-data', 'disabled');
            dayElement.title = 'No appointment slots defined for this date';
        }

        return dayElement;
    }

    selectDate(date) {
        const dateString = this.formatDateString(date);
        const selectableDate = this.selectableDates.find(d => d.date === dateString);
        
        if (!selectableDate) return;

        this.selectedDate = date;
        this.render();
        this.showSelectedDateInfo(selectableDate);

        // Callback for date selection
        if (this.options.onDateSelect) {
            this.options.onDateSelect(dateString, selectableDate);
        }
    }

    showSelectedDateInfo(selectableDate) {
        const container = document.getElementById('selected-date-info');
        const title = document.getElementById('selected-date-title');
        const slots = document.getElementById('selected-date-slots');

        if (container && title && slots) {
            const dateStr = this.selectedDate.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            title.textContent = dateStr;
            
            let slotsText = `${selectableDate.slots_count} appointment slots available`;
            if (selectableDate.earliest_slot && selectableDate.latest_slot) {
                slotsText += ` from ${this.formatTime(selectableDate.earliest_slot)} to ${this.formatTime(selectableDate.latest_slot)}`;
            }
            
            slots.textContent = slotsText;
            
            container.classList.remove('hidden');
        }
    }

    hideSelectedDateInfo() {
        const container = document.getElementById('selected-date-info');
        if (container) {
            container.classList.add('hidden');
        }
    }

    showLoading() {
        document.getElementById('calendar-loading')?.classList.remove('hidden');
        document.getElementById('calendar-grid')?.classList.add('hidden');
    }

    hideLoading() {
        document.getElementById('calendar-loading')?.classList.add('hidden');
        document.getElementById('calendar-grid')?.classList.remove('hidden');
    }

    showError(message) {
        const container = document.getElementById('calendar-error');
        const messageEl = document.getElementById('calendar-error-message');
        
        if (container && messageEl) {
            messageEl.textContent = message;
            container.classList.remove('hidden');
        }
    }

    hideError() {
        document.getElementById('calendar-error')?.classList.add('hidden');
    }

    formatDateString(date) {
        return date.toISOString().split('T')[0];
    }

    formatTime(timeString) {
        if (!timeString) return '';
        
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        
        return `${hour12}:${minutes} ${ampm}`;
    }

    isSameDate(date1, date2) {
        return date1.toDateString() === date2.toDateString();
    }

    getSelectedDate() {
        return this.selectedDate ? this.formatDateString(this.selectedDate) : null;
    }

    getSelectedDateData() {
        if (!this.selectedDate) return null;
        
        const dateString = this.formatDateString(this.selectedDate);
        return this.selectableDates.find(d => d.date === dateString) || null;
    }
}

// Export for use in other scripts
window.CustomCalendar = CustomCalendar;
