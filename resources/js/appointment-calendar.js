/**
 * Modern Appointment Calendar System
 * A complete redesign for better functionality and user experience
 */
class AppointmentCalendar {

    constructor(containerId, options = {}) {

        this.container = document.getElementById(containerId);

        if (!this.container) {
            throw new Error(`Container with ID "${containerId}" not found`);
        }

        this.options = {
            doctorId: null,
            serviceId: null,
            onDateSelect: null,
            onTimeSelect: null,
            theme: 'light',
            ...options
        };

        this.currentDate = new Date();
        this.selectedDate = null;
        this.selectedTime = null;
        this.availableDates = new Map();
        this.availableSlots = [];
        this.isLoading = false;

        // Calendar navigation restrictions
        this.config = {
            minAdvanceDays: 0,
            maxAdvanceDays: 30
        };
        this.minDate = null;
        this.maxDate = null;

        this.init();
    }

    init() {
        // Calculate initial date limits
        this.calculateDateLimits();

        this.render();
        this.bindEvents();

        if (this.options.doctorId && this.options.serviceId) {
            this.loadAvailableDates();
        }
    }

    render() {
        this.container.innerHTML = this.getTemplate();
        this.updateCalendarHeader();
        this.renderCalendarDays();
    }

    getTemplate() {
        return `
            <div class="appointment-calendar bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600">
                <!-- Calendar Header -->
                <div class="calendar-header flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <i class="fas fa-calendar mr-2 text-blue-600 dark:text-blue-400"></i>
                        Select Date & Time
                    </h3>
                </div>

                <!-- Calendar Navigation -->
                <div class="calendar-nav flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700">
                    <button type="button" class="nav-btn prev-btn flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div class="month-year text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <!-- Will be populated by JavaScript -->
                    </div>
                    
                    <button type="button" class="nav-btn next-btn flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Main Content Grid -->
                <div class="calendar-content grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
                    <!-- Calendar Grid -->
                    <div class="calendar-section">
                        <!-- Days of Week Header -->
                        <div class="days-header grid grid-cols-7 gap-1 mb-2">
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Sun</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Mon</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Tue</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Wed</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Thu</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Fri</div>
                            <div class="day-name text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-2">Sat</div>
                        </div>

                        <!-- Calendar Days Grid -->
                        <div class="calendar-grid grid grid-cols-7 gap-1 mb-4">
                            <!-- Will be populated by JavaScript -->
                        </div>

                        <!-- Calendar Legend -->
                        <div class="calendar-legend">
                            <div class="flex flex-wrap items-center gap-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded bg-blue-500 mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Selected</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded bg-green-500 mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Available</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded bg-yellow-500 mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Limited</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded bg-gray-400 mr-2"></div>
                                    <span class="text-gray-600 dark:text-gray-400">Unavailable</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                    <div class="timeslots-section">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            <i class="fas fa-clock mr-2 text-blue-600 dark:text-blue-400"></i>
                            Available Times
                        </h4>

                        <!-- Loading State -->
                        <div class="loading-state hidden">
                            <div class="flex items-center justify-center py-12">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                                <span class="ml-3 text-gray-600 dark:text-gray-400">Loading times...</span>
                            </div>
                        </div>

                        <!-- No Date Selected -->
                        <div class="no-date-selected">
                            <div class="text-center py-12">
                                <i class="fas fa-calendar-day text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">Please select a date to view available times</p>
                            </div>
                        </div>

                        <!-- No Slots Available -->
                        <div class="no-slots-available hidden">
                            <div class="text-center py-12">
                                <i class="fas fa-calendar-times text-4xl text-red-400 dark:text-red-500 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">No available time slots for this date</p>
                                <button type="button" class="suggest-dates-btn mt-4 text-blue-600 dark:text-blue-400 hover:underline">
                                    Suggest alternative dates
                                </button>
                            </div>
                        </div>

                        <!-- Time Slots Grid -->
                        <div class="timeslots-grid hidden">
                            <div class="selected-date-info bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                                <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-1">Selected Date</h5>
                                <p class="text-blue-700 dark:text-blue-300 text-sm selected-date-text"></p>
                            </div>
                            
                            <div class="slots-container grid grid-cols-2 gap-2 max-h-80 overflow-y-auto">
                                <!-- Time slot buttons will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading Overlay -->
                <div class="calendar-loading hidden absolute inset-0 bg-white dark:bg-gray-800 bg-opacity-75 flex items-center justify-center">
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 dark:border-blue-400"></div>
                        <span class="ml-3 text-gray-600 dark:text-gray-400">Loading calendar...</span>
                    </div>
                </div>

                <!-- Error State -->
                <div class="calendar-error hidden">
                    <div class="p-6 text-center">
                        <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                        <h4 class="text-lg font-medium text-red-900 dark:text-red-100 mb-2">Unable to load calendar</h4>
                        <p class="text-red-700 dark:text-red-300 mb-4 error-message"></p>
                        <button type="button" class="retry-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        `;
    } bindEvents() {
        // Navigation buttons
        this.container.querySelector('.prev-btn').addEventListener('click', () => this.previousMonth());
        this.container.querySelector('.next-btn').addEventListener('click', () => this.nextMonth());

        // Retry button
        this.container.querySelector('.retry-btn').addEventListener('click', () => this.loadAvailableDates());

        // Suggest dates button
        this.container.querySelector('.suggest-dates-btn').addEventListener('click', () => this.suggestAlternativeDates());
        // Update navigation buttons state initially
        this.updateNavigationButtons();

        // Keyboard navigation support
        this.bindKeyboardEvents();
    }

    bindKeyboardEvents() {
        // Add keyboard navigation for calendar
        this.container.addEventListener('keydown', (event) => {
            // Only handle keyboard events when calendar has focus
            if (!this.container.contains(document.activeElement)) return;

            switch (event.key) {
                case 'ArrowLeft':
                    event.preventDefault();
                    if (!this.container.querySelector('.prev-btn').disabled) {
                        this.previousMonth();
                    }
                    break;

                case 'ArrowRight':
                    event.preventDefault();
                    if (!this.container.querySelector('.next-btn').disabled) {
                        this.nextMonth();
                    }
                    break;

                case 'Home':
                    event.preventDefault();
                    // Navigate to current month
                    const today = new Date();
                    this.navigateToMonth(today.getFullYear(), today.getMonth());
                    break;

                case 'Escape':
                    event.preventDefault();
                    // Clear selection
                    this.clearSelection();
                    break;
            }
        });

        // Make navigation buttons focusable and add ARIA labels
        const prevBtn = this.container.querySelector('.prev-btn');
        const nextBtn = this.container.querySelector('.next-btn');

        prevBtn.setAttribute('aria-label', 'Previous month');
        nextBtn.setAttribute('aria-label', 'Next month');
        prevBtn.setAttribute('tabindex', '0');
        nextBtn.setAttribute('tabindex', '0');
    }

    updateCalendarHeader() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        const monthYear = this.container.querySelector('.month-year');
        monthYear.textContent = `${monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;

        // Update navigation buttons state
        this.updateNavigationButtons();
    }

    updateNavigationButtons() {
        const prevBtn = this.container.querySelector('.prev-btn');
        const nextBtn = this.container.querySelector('.next-btn');

        if (!prevBtn || !nextBtn) return;

        const currentYear = this.currentDate.getFullYear();
        const currentMonth = this.currentDate.getMonth();
        const currentMonthDate = new Date(currentYear, currentMonth, 1);

        // Check if previous month is allowed
        let canGoPrevious = true;
        if (this.minDate) {
            const minYear = this.minDate.getFullYear();
            const minMonth = this.minDate.getMonth();
            const minMonthDate = new Date(minYear, minMonth, 1);

            // Compare year and month to handle year boundaries properly
            canGoPrevious = currentYear > minYear ||
                (currentYear === minYear && currentMonth > minMonth);
        }

        // Check if next month is allowed
        let canGoNext = true;
        if (this.maxDate) {
            const maxYear = this.maxDate.getFullYear();
            const maxMonth = this.maxDate.getMonth();
            const maxMonthDate = new Date(maxYear, maxMonth, 1);

            // Compare year and month to handle year boundaries properly
            canGoNext = currentYear < maxYear ||
                (currentYear === maxYear && currentMonth < maxMonth);
        }

        // Update button states
        prevBtn.disabled = !canGoPrevious;
        nextBtn.disabled = !canGoNext;

        // Update visual styles for better UX
        this.updateButtonVisualState(prevBtn, canGoPrevious);
        this.updateButtonVisualState(nextBtn, canGoNext);

        // Add tooltips for better user feedback
        if (!canGoPrevious) {
            prevBtn.title = 'Cannot navigate to earlier months - outside booking range';
        } else {
            prevBtn.title = 'Previous month';
        }

        if (!canGoNext) {
            nextBtn.title = 'Cannot navigate to later months - outside booking range';
        } else {
            nextBtn.title = 'Next month';
        }
    }

    updateButtonVisualState(button, isEnabled) {
        if (!isEnabled) {
            button.classList.add('opacity-50', 'cursor-not-allowed');
            button.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-600');
        } else {
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            button.classList.add('hover:bg-gray-200', 'dark:hover:bg-gray-600');
        }
    }

    previousMonth() {
        const prevBtn = this.container.querySelector('.prev-btn');
        if (prevBtn && prevBtn.disabled) return;

        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.updateCalendarHeader();
        this.renderCalendarDays();

        // Load available dates for the new month if doctor is selected
        if (this.options.doctorId) {
            this.loadAvailableDates();
        }
    }

    nextMonth() {
        const nextBtn = this.container.querySelector('.next-btn');
        if (nextBtn && nextBtn.disabled) return;

        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.updateCalendarHeader();
        this.renderCalendarDays();

        // Load available dates for the new month if doctor is selected
        if (this.options.doctorId) {
            this.loadAvailableDates();
        }
    }

    calculateDateLimits() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Calculate minimum date
        this.minDate = new Date(today);
        this.minDate.setDate(today.getDate() + this.config.minAdvanceDays);

        // Calculate maximum date
        this.maxDate = new Date(today);
        this.maxDate.setDate(today.getDate() + this.config.maxAdvanceDays);
        this.maxDate.setHours(23, 59, 59, 999); // Ensure max date includes the whole day


    }

    renderCalendarDays() {
        const grid = this.container.querySelector('.calendar-grid');
        grid.innerHTML = '';

        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        // Generate 42 days (6 weeks)
        for (let i = 0; i < 42; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);

            const dayElement = this.createDayElement(date, month);
            grid.appendChild(dayElement);
        }
    }

    createDayElement(date, currentMonth) {
        const dayElement = document.createElement('div');
        const dateString = this.formatDate(date);

        // create a date element from dateString
        const dateObj = new Date(dateString);

        const isCurrentMonth = dateObj.getMonth() === currentMonth;
        const isToday = this.isToday(dateObj);
        const isSelected = this.selectedDate && this.formatDate(this.selectedDate) === dateString;
        const isPast = dateObj < new Date().setHours(0, 0, 0, 0);

        // Check if date is outside allowed range
        const isOutsideRange = (this.minDate && dateObj < this.minDate) || (this.maxDate && dateObj > this.maxDate);

        dayElement.className = 'calendar-day';
        dayElement.textContent = dateObj.getDate();
        dayElement.dataset.date = dateString;

        // Apply base styles
        let classes = ['calendar-day', 'w-10', 'h-10', 'rounded-lg', 'flex', 'items-center', 'justify-center', 'text-sm', 'font-medium', 'transition-all', 'duration-200', 'relative', 'cursor-pointer'];

        if (!isCurrentMonth) {
            classes.push('text-gray-400', 'dark:text-gray-600');
        } else {
            classes.push('text-gray-900', 'dark:text-gray-100');
        }

        if (isToday) {
            classes.push('ring-2', 'ring-blue-500', 'dark:ring-blue-400');
        }

        if (isSelected) {
            classes.push('bg-blue-500', 'text-white', 'ring-2', 'ring-blue-300', 'dark:ring-blue-600');
        }
        if (isPast || !isCurrentMonth || isOutsideRange) {

            classes.push('cursor-not-allowed', 'opacity-50');

            if (isOutsideRange && isCurrentMonth && !isPast) {
                dayElement.title = 'Date outside allowed booking range';
            }
        }
        else {
            // Check availability for current month future dates
            // console.log(this.availableDates);
            const availableDate = this.availableDates.get(dateString);

            if (availableDate) {

                // console.log('Available date found:', dateObj);
                // console.log('Creating day element for date:', dateString);
                // console.log('Available date data:', availableDate);

                if (availableDate.slots_count > 0) {
                    if (availableDate.slots_count <= 3) {
                        classes.push('bg-yellow-100', 'dark:bg-yellow-900/30', 'text-yellow-800', 'dark:text-yellow-200', 'hover:bg-yellow-200', 'dark:hover:bg-yellow-900/50');
                    } else {
                        classes.push('bg-green-100', 'dark:bg-green-900/30', 'text-green-800', 'dark:text-green-200', 'hover:bg-green-200', 'dark:hover:bg-green-900/50');
                    }

                    // Add slot indicator
                    const indicator = document.createElement('div');
                    indicator.className = 'slot-indicator absolute -top-1 -right-1 w-5 h-5 rounded-full text-xs flex items-center justify-center font-bold';
                    indicator.textContent = availableDate.slots_count > 9 ? '9+' : availableDate.slots_count;

                    if (availableDate.slots_count <= 3) {
                        indicator.classList.add('bg-yellow-600', 'dark:bg-yellow-500', 'text-white');
                    } else {
                        indicator.classList.add('bg-green-600', 'dark:bg-green-500', 'text-white');
                    }
                    dayElement.appendChild(indicator);

                    // Make clickable only if date is not restricted
                    if (!isOutsideRange) {
                        dayElement.addEventListener('click', () => this.selectDate(dateObj));
                    }
                    dayElement.title = `${availableDate.slots_count} slots available`;
                } else {
                    classes.push('bg-gray-100', 'dark:bg-gray-700', 'text-gray-400', 'dark:text-gray-600', 'cursor-not-allowed');
                    dayElement.title = 'No slots available';
                }
            } else {
                classes.push('bg-gray-50', 'dark:bg-gray-800', 'text-gray-400', 'dark:text-gray-500', 'cursor-not-allowed');
                dayElement.title = 'Date not available for booking';
            }
        }

        dayElement.className = classes.join(' ');
        return dayElement;
    }
    
    selectDate(date) {
        // Check if date is within allowed range
        if ((this.minDate && date < this.minDate) || (this.maxDate && date > this.maxDate)) {
            console.warn('Cannot select date outside allowed range');
            return;
        }

        this.selectedDate = date;
        const dateString = this.formatDate(date);

        // Update visual selection
        this.renderCalendarDays();

        // Update selected date info
        this.showSelectedDateInfo(date);

        // Load time slots
        this.loadTimeSlots(dateString);

        // Callback
        if (this.options.onDateSelect) {
            this.options.onDateSelect(dateString, this.availableDates.get(dateString));
        }
    }

    showSelectedDateInfo(date) {
        const selectedDateText = this.container.querySelector('.selected-date-text');
        const dateString = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        selectedDateText.textContent = dateString;
    }

    async loadAvailableDates() {
        if (!this.options.doctorId) return;

        this.showLoading();
        this.hideError();

        try {
            const params = new URLSearchParams({
                doctor_id: this.options.doctorId,
                ...(this.options.serviceId && { service_id: this.options.serviceId })
            });

            const response = await fetch(`/api/appointments/selectable-dates?${params}`);
            const data = await response.json(); if (data.success) {
                // Update config from API response
                if (data.config) {
                    this.config.minAdvanceDays = data.config.min_advance_days || 0;
                    this.config.maxAdvanceDays = data.config.max_advance_days || 30;

                    // Calculate date limits based on new config
                    this.calculateDateLimits();
                }

                this.availableDates.clear();
                data.selectable_dates.forEach(date => {
                    this.availableDates.set(date.date, date);
                });

                this.renderCalendarDays();
                this.updateNavigationButtons();
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

    async loadTimeSlots(date) {
        if (!this.options.doctorId || !date) return;

        this.showTimeSlotsLoading();

        try {
            const params = new URLSearchParams({
                doctor_id: this.options.doctorId,
                date: date,
                ...(this.options.serviceId && { service_id: this.options.serviceId })
            });

            const response = await fetch(`/api/appointments/available-slots?${params}`);
            const data = await response.json();

            console.log('Response data:', data);
            console.log('condition - ', data.success && data.slots ? 'passed' : 'failed');
            if (data.success && data.slots && data.slots.length > 0) {

                this.availableSlots = data.slots;
                console.log('Available slots:', this.availableSlots);
                this.renderTimeSlots();
                this.showTimeSlotsGrid();
            }
            else {
                this.showNoSlots();
            }
        } catch (error) {
            console.error('Error loading time slots:', error);
            this.showNoSlots();
        } finally {
            this.hideTimeSlotsLoading();
        }
    }

    renderTimeSlots() {
        const container = this.container.querySelector('.slots-container');
        container.innerHTML = '';

        this.availableSlots.forEach(slot => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'time-slot-btn px-4 py-3 text-sm font-medium border rounded-lg transition-all duration-200 hover:scale-105';
            button.textContent = this.formatTime(slot.start_time);
            button.dataset.time = slot.start_time;

            if (this.selectedTime === slot.start_time) {
                button.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
            } else {
                button.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-900', 'dark:text-gray-100', 'border-gray-300', 'dark:border-gray-600', 'hover:border-blue-300', 'hover:bg-blue-50', 'dark:hover:bg-blue-900/20');
            }

            button.addEventListener('click', () => this.selectTime(slot.start_time));
            container.appendChild(button);
        });
    } selectTime(time) {
        this.selectedTime = time;
        this.renderTimeSlots();

        if (this.options.onTimeSelect) {
            this.options.onTimeSelect(time, this.formatDate(this.selectedDate));
        }
    }

    previousMonth() {
        const prevBtn = this.container.querySelector('.prev-btn');
        if (prevBtn && prevBtn.disabled) return;

        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.updateCalendarHeader();
        this.renderCalendarDays();
        // Load available dates for the new month if doctor is selected
        if (this.options.doctorId) {
            this.loadAvailableDates();
        }
    }

    nextMonth() {
        const nextBtn = this.container.querySelector('.next-btn');
        if (nextBtn && nextBtn.disabled) return;

        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.updateCalendarHeader();
        this.renderCalendarDays();

        // Load available dates for the new month if doctor is selected
        if (this.options.doctorId) {
            this.loadAvailableDates();
        }
    }

    // Utility methods
    formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    formatTime(timeString) {
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    }

    isToday(date) {
        const today = new Date();
        return date.toDateString() === today.toDateString();
    }

    // State management
    showLoading() {
        this.container.querySelector('.calendar-loading').classList.remove('hidden');
    }

    hideLoading() {
        this.container.querySelector('.calendar-loading').classList.add('hidden');
    }

    showError(message) {
        const errorEl = this.container.querySelector('.calendar-error');
        const messageEl = this.container.querySelector('.error-message');
        messageEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    hideError() {
        this.container.querySelector('.calendar-error').classList.add('hidden');
    }

    showTimeSlotsLoading() {
        this.container.querySelector('.loading-state').classList.remove('hidden');
        this.container.querySelector('.no-date-selected').classList.add('hidden');
        this.container.querySelector('.no-slots-available').classList.add('hidden');
        this.container.querySelector('.timeslots-grid').classList.add('hidden');
    }

    hideTimeSlotsLoading() {
        this.container.querySelector('.loading-state').classList.add('hidden');
    }

    showTimeSlotsGrid() {
        this.container.querySelector('.timeslots-grid').classList.remove('hidden');
        this.container.querySelector('.no-date-selected').classList.add('hidden');
        this.container.querySelector('.no-slots-available').classList.add('hidden');
    }

    showNoSlots() {
        this.container.querySelector('.no-slots-available').classList.remove('hidden');
        this.container.querySelector('.no-date-selected').classList.add('hidden');
        this.container.querySelector('.timeslots-grid').classList.add('hidden');
    }

    // Public API
    setDoctor(doctorId) {
        this.options.doctorId = doctorId;
        this.clearSelection();
        // this.loadAvailableDates();
    }

    setService(serviceId) {
        this.options.serviceId = serviceId;
        if (this.options.doctorId) {
            this.clearSelection();
            this.loadAvailableDates();
        }
    }

    clearSelection() {
        this.selectedDate = null;
        this.selectedTime = null;
        this.availableSlots = [];
        this.container.querySelector('.no-date-selected').classList.remove('hidden');
        this.container.querySelector('.timeslots-grid').classList.add('hidden');
        this.container.querySelector('.no-slots-available').classList.add('hidden');
        this.renderCalendarDays();
    }

    getSelectedDate() {
        return this.selectedDate ? this.formatDate(this.selectedDate) : null;
    }

    getSelectedTime() {
        return this.selectedTime;
    }

    getSelection() {
        return {
            date: this.getSelectedDate(),
            time: this.getSelectedTime(),
            valid: !!(this.selectedDate && this.selectedTime)
        };
    }
    // Public API methods for updating calendar configuration
    updateConfig(newConfig) {
        if (newConfig.minAdvanceDays !== undefined) {
            this.config.minAdvanceDays = Math.max(0, parseInt(newConfig.minAdvanceDays));
        }
        if (newConfig.maxAdvanceDays !== undefined) {
            this.config.maxAdvanceDays = Math.max(1, parseInt(newConfig.maxAdvanceDays));
        }

        // Ensure max is always greater than min
        if (this.config.maxAdvanceDays <= this.config.minAdvanceDays) {
            this.config.maxAdvanceDays = this.config.minAdvanceDays + 1;
        }

        this.calculateDateLimits();
        this.updateNavigationButtons();
        this.renderCalendarDays();
    }

    setDoctor(doctorId, serviceId = null) {
        this.options.doctorId = doctorId;
        if (serviceId) {
            this.options.serviceId = serviceId;
        }

        // Clear existing data
        this.availableDates.clear();
        this.selectedDate = null;
        this.selectedTime = null;
        this.availableSlots = [];

        // Load new data if doctor is set
        // if (doctorId) {
        //     this.loadAvailableDates();
        // } else {
        //     this.renderCalendarDays();
        // }
    }

    // Method to programmatically navigate to a specific month
    navigateToMonth(year, month) {
        const targetDate = new Date(year, month, 1);

        // Check if target month is within allowed range
        if (this.minDate) {
            const minMonth = new Date(this.minDate.getFullYear(), this.minDate.getMonth(), 1);
            if (targetDate < minMonth) {
                console.warn('Cannot navigate to month before minimum allowed date');
                return false;
            }
        }

        if (this.maxDate) {
            const maxMonth = new Date(this.maxDate.getFullYear(), this.maxDate.getMonth(), 1);
            if (targetDate > maxMonth) {
                console.warn('Cannot navigate to month after maximum allowed date');
                return false;
            }
        }

        this.currentDate = targetDate;
        this.updateCalendarHeader();
        this.renderCalendarDays();

        if (this.options.doctorId) {
            this.loadAvailableDates();
        }

        return true;
    }
}

// Export for use in other scripts
window.AppointmentCalendar = AppointmentCalendar;
