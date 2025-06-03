/**
 * Simple Appointment Calendar
 * Main calendar functionality for doctor appointments
 */
class SimpleAppointmentCalendar {
    constructor() {
        this.currentDate = new Date();
        this.appointments = [];
        this.monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateHeader();
        this.loadAppointments();
    }
    
    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => this.previousMonth());
        document.getElementById('nextMonth').addEventListener('click', () => this.nextMonth());
        document.getElementById('todayBtn').addEventListener('click', () => this.goToToday());
    }
    
    previousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.updateHeader();
        this.loadAppointments();
    }
    
    nextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.updateHeader();
        this.loadAppointments();
    }
    
    goToToday() {
        this.currentDate = new Date();
        this.updateHeader();
        this.loadAppointments();
    }
    
    updateHeader() {
        const monthYear = `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        document.getElementById('currentMonth').textContent = monthYear;
    }
      async loadAppointments() {
        try {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth() + 1;
            
            const response = await fetch(`${window.appointmentRoutes.calendarData}?year=${year}&month=${month}`);
            const data = await response.json();
            
            this.appointments = data.appointments || [];
            this.renderCalendar();
        } catch (error) {
            console.error('Error loading appointments:', error);
            this.appointments = [];
            this.renderCalendar();
        }
    }
    
    renderCalendar() {
        const grid = document.getElementById('calendar-grid');
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        
        // Start from the beginning of the week
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        let html = '';
        const today = new Date();
        const currentDate = new Date(startDate);
        
        // Generate 42 days (6 weeks)
        for (let i = 0; i < 42; i++) {
            const isCurrentMonth = currentDate.getMonth() === this.currentDate.getMonth();
            const isToday = this.isSameDay(currentDate, today);
            const dayAppointments = this.getAppointmentsForDate(currentDate);
            
            let dayClasses = 'min-h-24 p-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer';
            
            if (!isCurrentMonth) {
                dayClasses += ' text-gray-400 dark:text-gray-600 bg-gray-50 dark:bg-gray-900';
            }
            
            if (isToday) {
                dayClasses += ' bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700';
            }
            
            html += `
                <div class="${dayClasses}" data-date="${this.formatDate(currentDate)}">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">${currentDate.getDate()}</div>
                    ${this.renderAppointmentItems(dayAppointments)}
                </div>
            `;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        grid.innerHTML = html;
        this.attachEventListeners();
    }
    
    getAppointmentsForDate(date) {
        const dateStr = this.formatDate(date);
        return this.appointments.filter(appointment => appointment.appointment_date === dateStr);
    }
    
    renderAppointmentItems(appointments) {
        return appointments.slice(0, 3).map(appointment => {
            const statusColors = {
                'pending': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 border-l-yellow-500',
                'confirmed': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 border-l-green-500',
                'completed': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 border-l-blue-500',
                'cancelled': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 border-l-red-500',
                'no_show': 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-200 border-l-gray-500'
            };
            
            const colorClass = statusColors[appointment.status] || statusColors['pending'];
            
            return `
                <div class="text-xs p-1 mb-1 rounded border-l-2 ${colorClass} cursor-pointer hover:opacity-80 transition-opacity" 
                     data-appointment-id="${appointment.id}"
                     onclick="showAppointmentDetails(${appointment.id})">
                    <div class="font-medium truncate">${appointment.appointment_time}</div>
                    <div class="truncate">${appointment.patient_name}</div>
                </div>
            `;
        }).join('') + (appointments.length > 3 ? `<div class="text-xs text-gray-500 dark:text-gray-400">+${appointments.length - 3} more</div>` : '');
    }
    
    attachEventListeners() {
        // Add click events for appointment items
        document.querySelectorAll('[data-appointment-id]').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const appointmentId = item.dataset.appointmentId;
                this.showAppointmentDetails(appointmentId);
            });
        });
    }
    
    showAppointmentDetails(appointmentId) {
        // Find the appointment data
        const appointment = this.appointments.find(apt => apt.id == appointmentId);
        if (!appointment) return;
        
        AppointmentModal.show(appointment);
    }
    
    formatDate(date) {
        return date.toISOString().split('T')[0];
    }
    
    isSameDay(date1, date2) {
        return date1.toDateString() === date2.toDateString();
    }
}
