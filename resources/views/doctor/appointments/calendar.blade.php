@extends('layouts.doctor')

@section('title', 'Appointment Calendar')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-calendar3 me-2"></i>
                                Appointment Calendar
                            </h4>
                            <p class="mb-0 opacity-75">View and manage your appointments in calendar format</p>
                        </div>
                        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-light">
                            <i class="bi bi-list-ul me-1"></i>
                            List View
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Controls -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-primary me-2" id="prevMonth">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <h5 class="mb-0 me-2" id="currentMonth"></h5>
                            <button type="button" class="btn btn-outline-primary" id="nextMonth">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="todayBtn">
                                Today
                            </button>
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="viewType" id="monthView" checked>
                                <label class="btn btn-outline-primary btn-sm" for="monthView">Month</label>
                                
                                <input type="radio" class="btn-check" name="viewType" id="weekView">
                                <label class="btn btn-outline-primary btn-sm" for="weekView">Week</label>
                                
                                <input type="radio" class="btn-check" name="viewType" id="dayView">
                                <label class="btn btn-outline-primary btn-sm" for="dayView">Day</label>
                            </div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-warning me-2"></div>
                                    <small>Pending</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-success me-2"></div>
                                    <small>Confirmed</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-info me-2"></div>
                                    <small>Completed</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-danger me-2"></div>
                                    <small>Cancelled</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="legend-color bg-secondary me-2"></div>
                                    <small>No Show</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Container -->
                    <div id="calendar" class="appointment-calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Details Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Appointment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="appointmentModalBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="appointmentActions">
                    <!-- Action buttons will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    display: inline-block;
}

.appointment-calendar {
    min-height: 600px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background-color: #dee2e6;
    border: 1px solid #dee2e6;
}

.calendar-header {
    background-color: #f8f9fa;
    padding: 12px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
}

.calendar-day {
    background-color: white;
    min-height: 120px;
    padding: 8px;
    position: relative;
    cursor: pointer;
    transition: background-color 0.2s;
}

.calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    color: #adb5bd;
}

.calendar-day.today {
    background-color: #e3f2fd;
}

.day-number {
    font-weight: 600;
    margin-bottom: 4px;
}

.appointment-item {
    background-color: #fff;
    border-left: 3px solid #007bff;
    padding: 4px 6px;
    margin-bottom: 2px;
    border-radius: 3px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.appointment-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.appointment-item.pending {
    border-left-color: #ffc107;
    background-color: #fff8e1;
}

.appointment-item.confirmed {
    border-left-color: #28a745;
    background-color: #f1f8e9;
}

.appointment-item.completed {
    border-left-color: #17a2b8;
    background-color: #e0f7fa;
}

.appointment-item.cancelled {
    border-left-color: #dc3545;
    background-color: #ffebee;
}

.appointment-item.no_show {
    border-left-color: #6c757d;
    background-color: #f5f5f5;
}

.appointment-time {
    font-weight: 600;
    color: #495057;
}

.appointment-patient {
    color: #6c757d;
    margin-top: 2px;
}

.week-view {
    display: grid;
    grid-template-columns: 80px repeat(7, 1fr);
    gap: 1px;
    background-color: #dee2e6;
}

.time-slot {
    background-color: #f8f9fa;
    padding: 8px;
    text-align: center;
    font-size: 12px;
    color: #6c757d;
    border-bottom: 1px solid #dee2e6;
}

.day-column {
    background-color: white;
    min-height: 60px;
    padding: 4px;
    position: relative;
    border-bottom: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .calendar-grid {
        grid-template-columns: repeat(1, 1fr);
    }
    
    .calendar-day {
        min-height: 80px;
    }
}
</style>
@endpush

@push('scripts')
<script>
class AppointmentCalendar {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month';
        this.appointments = [];
        
        this.init();
        this.loadAppointments();
    }
    
    init() {
        this.bindEvents();
        this.updateHeader();
        this.renderCalendar();
    }
    
    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() - 1);
            this.updateHeader();
            this.renderCalendar();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            this.currentDate.setMonth(this.currentDate.getMonth() + 1);
            this.updateHeader();
            this.renderCalendar();
        });
        
        document.getElementById('todayBtn').addEventListener('click', () => {
            this.currentDate = new Date();
            this.updateHeader();
            this.renderCalendar();
        });
        
        document.querySelectorAll('input[name="viewType"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.currentView = e.target.id.replace('View', '');
                this.renderCalendar();
            });
        });
    }
    
    updateHeader() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        const monthYear = `${monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        document.getElementById('currentMonth').textContent = monthYear;
    }
    
    renderCalendar() {
        const calendar = document.getElementById('calendar');
        
        if (this.currentView === 'month') {
            this.renderMonthView(calendar);
        } else if (this.currentView === 'week') {
            this.renderWeekView(calendar);
        } else if (this.currentView === 'day') {
            this.renderDayView(calendar);
        }
    }
    
    renderMonthView(container) {
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());
        
        const html = `
            <div class="calendar-grid">
                <div class="calendar-header">Sun</div>
                <div class="calendar-header">Mon</div>
                <div class="calendar-header">Tue</div>
                <div class="calendar-header">Wed</div>
                <div class="calendar-header">Thu</div>
                <div class="calendar-header">Fri</div>
                <div class="calendar-header">Sat</div>
                ${this.generateMonthDays(startDate, lastDay)}
            </div>
        `;
        
        container.innerHTML = html;
        this.attachDayClickEvents();
    }
    
    generateMonthDays(startDate, lastDay) {
        let html = '';
        const currentDate = new Date(startDate);
        const today = new Date();
        
        for (let i = 0; i < 42; i++) {
            const isCurrentMonth = currentDate.getMonth() === this.currentDate.getMonth();
            const isToday = currentDate.toDateString() === today.toDateString();
            
            const dayClass = `calendar-day ${!isCurrentMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}`;
            const dayAppointments = this.getAppointmentsForDate(currentDate);
            
            html += `
                <div class="${dayClass}" data-date="${currentDate.toISOString().split('T')[0]}">
                    <div class="day-number">${currentDate.getDate()}</div>
                    ${this.renderAppointmentItems(dayAppointments)}
                </div>
            `;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        return html;
    }
    
    renderWeekView(container) {
        // Week view implementation
        container.innerHTML = '<div class="text-center p-4"><p>Week view coming soon...</p></div>';
    }
    
    renderDayView(container) {
        // Day view implementation
        container.innerHTML = '<div class="text-center p-4"><p>Day view coming soon...</p></div>';
    }
    
    getAppointmentsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return this.appointments.filter(appointment => 
            appointment.appointment_date === dateStr
        );
    }
    
    renderAppointmentItems(appointments) {
        return appointments.map(appointment => `
            <div class="appointment-item ${appointment.status}" data-appointment-id="${appointment.id}">
                <div class="appointment-time">${appointment.appointment_time}</div>
                <div class="appointment-patient">${appointment.patient_name}</div>
            </div>
        `).join('');
    }
    
    attachDayClickEvents() {
        document.querySelectorAll('.appointment-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const appointmentId = item.dataset.appointmentId;
                this.showAppointmentDetails(appointmentId);
            });
        });
    }
    
    showAppointmentDetails(appointmentId) {
        // Load appointment details in modal
        fetch(`{{ route('doctor.appointments.show', ':id') }}`.replace(':id', appointmentId))
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector('.appointment-details-content');
                
                if (content) {
                    document.getElementById('appointmentModalBody').innerHTML = content.innerHTML;
                    const modal = new bootstrap.Modal(document.getElementById('appointmentModal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error loading appointment details:', error);
                alert('Error loading appointment details. Please try again.');
            });
    }
    
    loadAppointments() {
        // Load appointments for current month
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth() + 1;
        
        fetch(`{{ route('doctor.appointments.index') }}?format=json&year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                this.appointments = data.appointments || [];
                this.renderCalendar();
            })
            .catch(error => {
                console.error('Error loading appointments:', error);
            });
    }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', function() {
    new AppointmentCalendar();
});
</script>
@endpush
