@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Book New Appointment</h4>
                        <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Appointments
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('patient.appointments.store') }}" method="POST" id="appointmentForm">
                        @csrf

                        <!-- Doctor Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="doctor_search" class="form-label">Search Doctor</label>
                                <div class="input-group">
                                    <input type="text" 
                                           id="doctor_search" 
                                           class="form-control" 
                                           placeholder="Type doctor name or specialization..."
                                           autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="doctor_results" class="mt-2" style="display: none;"></div>
                            </div>
                        </div>

                        <!-- Selected Doctor -->
                        <div id="selected_doctor_section" style="display: none;">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div id="selected_doctor_info"></div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="remove_doctor">
                                        <i class="fas fa-times"></i> Change Doctor
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="doctor_id" id="doctor_id">
                        </div>

                        <!-- Appointment Date & Time -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="appointment_date" 
                                       id="appointment_date" 
                                       class="form-control @error('appointment_date') is-invalid @enderror"
                                       value="{{ old('appointment_date') }}"
                                       min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}"
                                       max="{{ \Carbon\Carbon::now()->addDays(90)->format('Y-m-d') }}"
                                       required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                                <select name="appointment_time" 
                                        id="appointment_time" 
                                        class="form-select @error('appointment_time') is-invalid @enderror"
                                        required disabled>
                                    <option value="">Select a date first</option>
                                </select>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="slot_loading" class="text-muted mt-2" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Loading available slots...
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="appointment_type" class="form-label">Appointment Type <span class="text-danger">*</span></label>
                                <select name="appointment_type" 
                                        id="appointment_type" 
                                        class="form-select @error('appointment_type') is-invalid @enderror"
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="consultation" {{ old('appointment_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="follow_up" {{ old('appointment_type') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                                    <option value="check_up" {{ old('appointment_type') == 'check_up' ? 'selected' : '' }}>Check Up</option>
                                    <option value="emergency" {{ old('appointment_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                </select>
                                @error('appointment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select name="priority" 
                                        id="priority" 
                                        class="form-select @error('priority') is-invalid @enderror"
                                        required>
                                    <option value="">Select Priority</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Reason for Visit -->
                        <div class="mb-4">
                            <label for="reason" class="form-label">Reason for Visit <span class="text-danger">*</span></label>
                            <textarea name="reason" 
                                      id="reason" 
                                      class="form-control @error('reason') is-invalid @enderror"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Please describe the reason for your appointment..."
                                      required>{{ old('reason') }}</textarea>
                            <div class="form-text">Maximum 500 characters</div>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Symptoms -->
                        <div class="mb-4">
                            <label for="symptoms" class="form-label">Symptoms (Optional)</label>
                            <textarea name="symptoms" 
                                      id="symptoms" 
                                      class="form-control @error('symptoms') is-invalid @enderror"
                                      rows="3"
                                      maxlength="1000"
                                      placeholder="Describe any symptoms you're experiencing...">{{ old('symptoms') }}</textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                            @error('symptoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      rows="2"
                                      maxlength="500"
                                      placeholder="Any additional information for the doctor...">{{ old('notes') }}</textarea>
                            <div class="form-text">Maximum 500 characters</div>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-calendar-plus"></i> Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let searchTimeout;
    let selectedDoctorId = null;

    // Doctor search functionality
    $('#doctor_search').on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();
        
        if (query.length < 2) {
            $('#doctor_results').hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            searchDoctors(query);
        }, 300);
    });

    function searchDoctors(query) {
        $.ajax({
            url: '{{ route("patient.appointments.search-doctors") }}',
            method: 'GET',
            data: { q: query },
            success: function(response) {
                displayDoctorResults(response.doctors);
            },
            error: function() {
                $('#doctor_results').html('<div class="alert alert-danger">Error searching doctors</div>').show();
            }
        });
    }

    function displayDoctorResults(doctors) {
        if (doctors.length === 0) {
            $('#doctor_results').html('<div class="alert alert-warning">No doctors found</div>').show();
            return;
        }

        let html = '<div class="list-group">';
        doctors.forEach(function(doctor) {
            html += `
                <a href="#" class="list-group-item list-group-item-action doctor-item" data-doctor-id="${doctor.id}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Dr. ${doctor.name}</h6>
                            <p class="mb-1 text-muted">${doctor.specialization || 'General Practice'}</p>
                            <small class="text-muted">${doctor.email}</small>
                        </div>
                        <span class="badge bg-primary">Select</span>
                    </div>
                </a>
            `;
        });
        html += '</div>';

        $('#doctor_results').html(html).show();
    }

    // Doctor selection
    $(document).on('click', '.doctor-item', function(e) {
        e.preventDefault();
        const doctorId = $(this).data('doctor-id');
        const doctorName = $(this).find('h6').text();
        const doctorSpecialization = $(this).find('p').text();

        selectDoctor(doctorId, doctorName, doctorSpecialization);
    });

    function selectDoctor(id, name, specialization) {
        selectedDoctorId = id;
        $('#doctor_id').val(id);
        $('#selected_doctor_info').html(`
            <strong>${name}</strong><br>
            <small class="text-muted">${specialization}</small>
        `);
        $('#selected_doctor_section').show();
        $('#doctor_search').val('');
        $('#doctor_results').hide();
        
        // Reset date and time selections
        $('#appointment_date').val('');
        $('#appointment_time').html('<option value="">Select a date first</option>').prop('disabled', true);
    }

    // Remove selected doctor
    $('#remove_doctor').click(function() {
        selectedDoctorId = null;
        $('#doctor_id').val('');
        $('#selected_doctor_section').hide();
        $('#appointment_date').val('');
        $('#appointment_time').html('<option value="">Select a date first</option>').prop('disabled', true);
    });

    // Clear search
    $('#clearSearch').click(function() {
        $('#doctor_search').val('');
        $('#doctor_results').hide();
    });

    // Date change - load available slots
    $('#appointment_date').change(function() {
        const date = $(this).val();
        
        if (!date || !selectedDoctorId) {
            $('#appointment_time').html('<option value="">Select a date first</option>').prop('disabled', true);
            return;
        }

        loadAvailableSlots(selectedDoctorId, date);
    });

    function loadAvailableSlots(doctorId, date) {
        $('#slot_loading').show();
        $('#appointment_time').prop('disabled', true);

        $.ajax({
            url: '{{ route("patient.appointments.available-slots") }}',
            method: 'GET',
            data: {
                doctor_id: doctorId,
                date: date
            },
            success: function(response) {
                let html = '<option value="">Select Time</option>';
                
                if (response.slots && response.slots.length > 0) {
                    response.slots.forEach(function(slot) {
                        html += `<option value="${slot.time}">${slot.formatted_time}</option>`;
                    });
                } else {
                    html = '<option value="">No slots available</option>';
                }

                $('#appointment_time').html(html).prop('disabled', false);
                $('#slot_loading').hide();
            },
            error: function() {
                $('#appointment_time').html('<option value="">Error loading slots</option>');
                $('#slot_loading').hide();
            }
        });
    }

    // Form validation
    $('#appointmentForm').submit(function(e) {
        if (!selectedDoctorId) {
            e.preventDefault();
            alert('Please select a doctor');
            return false;
        }

        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Booking...');
    });

    // Character counters
    $('textarea[maxlength]').each(function() {
        const maxLength = $(this).attr('maxlength');
        const $counter = $('<div class="form-text text-end mt-1"></div>');
        $(this).after($counter);
        
        $(this).on('input', function() {
            const remaining = maxLength - $(this).val().length;
            $counter.text(`${remaining} characters remaining`);
            
            if (remaining < 50) {
                $counter.addClass('text-warning');
            } else {
                $counter.removeClass('text-warning');
            }
        }).trigger('input');
    });
});
</script>
@endpush

@push('styles')
<style>
.doctor-item:hover {
    background-color: #f8f9fa;
}

.list-group {
    max-height: 300px;
    overflow-y: auto;
}

.form-label {
    font-weight: 600;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

#slot_loading {
    font-size: 0.9em;
}

.alert {
    border-radius: 0.375rem;
}
</style>
@endpush
