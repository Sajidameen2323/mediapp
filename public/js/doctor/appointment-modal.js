/**
 * Appointment Modal Handler
 * Handles appointment details modal functionality
 */
class AppointmentModal {
    static show(appointment) {
        const statusText = appointment.status.charAt(0).toUpperCase() + appointment.status.slice(1).replace('_', ' ');
        const statusColors = {
            'pending': 'text-yellow-800 bg-yellow-100',
            'confirmed': 'text-green-800 bg-green-100',
            'completed': 'text-blue-800 bg-blue-100',
            'cancelled': 'text-red-800 bg-red-100',
            'no_show': 'text-gray-800 bg-gray-100'
        };
        
        const statusClass = statusColors[appointment.status] || statusColors['pending'];
        
        document.getElementById('appointmentModalBody').innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">Appointment Information</h4>
                    <span class="px-3 py-1 rounded-full text-sm font-medium ${statusClass}">${statusText}</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${appointment.patient_name}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${appointment.service_name}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${appointment.appointment_date}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">${appointment.appointment_time}</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button onclick="closeModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Close
                    </button>
                    <a href="${window.appointmentRoutes.show.replace(':id', appointment.id)}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        View Details
                    </a>
                </div>
            </div>
        `;
        
        document.getElementById('appointmentModal').classList.remove('hidden');
    }
    
    static close() {
        document.getElementById('appointmentModal').classList.add('hidden');
    }
}

// Global functions for compatibility
function showAppointmentDetails(appointmentId) {
    window.calendar.showAppointmentDetails(appointmentId);
}

function closeModal() {
    AppointmentModal.close();
}
