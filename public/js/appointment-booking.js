
class AppointmentBooking {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 5; // Updated to 5 steps
        this.validatedSteps = new Set(); // Track which steps have been validated
        this.selectedData = {
            doctor: null,
            service: null,
            date: null,
            time: null,
            details: {}
        };

        // Initialize custom calendar
        this.calendar = null;

        this.init();
    }
    init() {
        this.setupEventListeners();
        this.checkQueryParameters();
        this.updateStepDisplay();
        this.loadInitialDoctors();
        this.initializeCustomCalendar();
    }

    checkQueryParameters() {
        const urlParams = new URLSearchParams(window.location.search);
        const doctorId = urlParams.get('doctor_id');
        const serviceId = urlParams.get('service_id');

        // If doctor_id is provided, preselect the doctor and skip to service selection
        if (doctorId) {
            this.preselectDoctor(doctorId);

            // If service_id is also provided, preselect service and skip to date/time
            if (serviceId) {
                this.preselectService(serviceId);
            }
        }
    }

    async preselectDoctor(doctorId) {
        try {
            const response = await fetch(`/api/doctors/${doctorId}`);
            const data = await response.json();

            if (data.success && data.doctor) {
                this.selectedData.doctor = data.doctor;
                document.getElementById('selected_doctor_id').value = doctorId;

                // Update UI to show selected doctor
                this.updateSelectedDoctorInfo(data.doctor);

                // Load services for this doctor
                await this.loadDoctorServices(doctorId);

                // Skip to step 2 (service selection)
                this.currentStep = 2;
                this.updateStepDisplay();
            }
        } catch (error) {
            console.error('Error preselecting doctor:', error);
        }
    }

    async preselectService(serviceId) {
        try {
            const response = await fetch(`/api/services/${serviceId}`);
            const data = await response.json();

            if (data.success && data.service) {
                this.selectedData.service = data.service;
                document.getElementById('selected_service_id').value = serviceId;

                // Update UI to show selected service
                this.updateSelectedServiceInfo(data.service);

                // Update calendar with service
                if (this.calendar) {
                    this.calendar.setService(serviceId);
                }

                // Skip to step 3 (date/time selection)
                this.currentStep = 3;
                this.updateStepDisplay();
            }
        } catch (error) {
            console.error('Error preselecting service:', error);
        }
    } goToStep(targetStep) {
        if (targetStep < 1 || targetStep > this.totalSteps) {
            return;
        }

        // Check if step is accessible
        if (!this.isStepAccessible(targetStep)) {
            this.showError('Please complete the previous steps to access this step.');
            return;
        }

        // Allow going back to any accessible step
        if (targetStep < this.currentStep || this.validatedSteps.has(targetStep)) {
            this.currentStep = targetStep;
            this.updateStepDisplay();
            return;
        }

        // For forward navigation, validate each step in sequence
        let canProceed = true;
        for (let step = this.currentStep; step < targetStep; step++) {
            const originalStep = this.currentStep;
            this.currentStep = step;

            if (!this.validateCurrentStep()) {
                canProceed = false;
                this.currentStep = originalStep;
                break;
            } else {
                this.validatedSteps.add(step);
            }
        }

        if (canProceed) {
            this.currentStep = targetStep;
            this.updateStepDisplay();
        } else {
            this.showError('Please complete the current step before proceeding.');
        }
    }

    isStepAccessible(stepNumber) {
        // Step 1 is always accessible
        if (stepNumber === 1) return true;

        // For subsequent steps, check if previous step is validated
        return this.validatedSteps.has(stepNumber - 1) || stepNumber <= this.currentStep;
    }
    initializeCustomCalendar() {
        // Wait for DOM to be ready, then initialize the new calendar
        setTimeout(() => {
            this.calendar = new AppointmentCalendar('appointment-calendar-container', {
                onDateSelect: (dateString, dateData) => {
                    this.handleDateSelection(dateString, dateData);
                },
                onTimeSelect: (timeString, timeData) => {
                    this.handleTimeSelection(timeString, timeData);
                }
            });
        }, 100);
    }
    handleDateSelection(dateString, dateData) {
        this.selectedData.date = dateString;

        // Update hidden form inputs
        const dateInput = document.getElementById('appointment_date');
        if (dateInput) {
            dateInput.value = dateString;
        }

        // Show success message or UI feedback
        this.showDateSelectionFeedback(dateString, dateData);
    }

    handleTimeSelection(timeString, timeData) {
        this.selectedData.time = timeString;

        // Update hidden form inputs
        const timeInput = document.getElementById('appointment_time');
        if (timeInput) {
            timeInput.value = timeString;
        }

        // Show success message or UI feedback
        this.showTimeSelectionFeedback(timeString, timeData);
    }

    showTimeSelectionFeedback(timeString, timeData) {
        // You can add visual feedback here
        console.log(`Time selected: ${timeString}`, timeData);
    }

    showDateSelectionFeedback(dateString, dateData) {
        // You can add visual feedback here
        console.log(`Date selected: ${dateString}`, dateData);
    }

    clearTimeSlotSelection() {
        // Clear visual selection
        document.querySelectorAll('.timeslot-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
            btn.classList.add('border-gray-300', 'dark:border-gray-600');
        });

        // Hide time slots and show "no date selected" state
        document.getElementById('timeslots_grid')?.classList.add('hidden');
        document.getElementById('no_date_selected')?.classList.remove('hidden');
        document.getElementById('no_slots_available')?.classList.add('hidden');
        document.getElementById('timeslots_loading')?.classList.add('hidden');
    }
    setupEventListeners() {
        // Navigation
        document.getElementById('next_btn').addEventListener('click', () => this.nextStep());
        document.getElementById('back_btn').addEventListener('click', () => this.prevStep());

        // Doctor search and filters
        this.setupDoctorSearch();
        this.setupFilters();

        // Form inputs
        this.setupFormInputs();

        // Clickable stepper functionality
        this.setupStepperNavigation();
    }

    setupStepperNavigation() {
        // Add click event listeners to stepper circles
        document.querySelectorAll('.step-circle').forEach((circle, index) => {
            const stepNumber = index + 1;
            circle.style.cursor = 'pointer';

            circle.addEventListener('click', () => {
                this.goToStep(stepNumber);
            });
        });
    }

    setupDoctorSearch() {
        const searchInput = document.getElementById('doctor_search');
        const clearBtn = document.getElementById('clear_doctor_search');
        let searchTimeout;

        searchInput?.addEventListener('input', (e) => {
            const query = e.target.value.trim();

            if (query.length > 0) {
                clearBtn.style.display = 'flex';
            } else {
                clearBtn.style.display = 'none';
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.searchDoctors(query);
            }, 300);
        });

        clearBtn?.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            this.loadInitialDoctors();
        });
    }

    setupFilters() {
        const serviceFilter = document.getElementById('service_filter');
        const specializationFilter = document.getElementById('specialization_filter');

        [serviceFilter, specializationFilter].forEach(filter => {
            filter?.addEventListener('change', () => {
                this.applyFilters();
            });
        });
    } setupFormInputs() {
        // Priority and appointment type change handlers
        ['reason', 'symptoms', 'priority', 'appointment_type'].forEach(field => {
            const element = document.getElementById(field);
            element?.addEventListener('change', () => {
                // Ensure details object exists
                if (!this.selectedData.details || typeof this.selectedData.details !== 'object') {
                    this.selectedData.details = {};
                }
                this.selectedData.details[field] = element.value;
                // Update hidden form fields
                const hiddenField = document.getElementById(`hidden_${field}`);
                if (hiddenField) {
                    hiddenField.value = element.value;
                }
            });            // Also listen for input events for text fields
            element?.addEventListener('input', () => {
                // Ensure details object exists
                if (!this.selectedData.details || typeof this.selectedData.details !== 'object') {
                    this.selectedData.details = {};
                }
                this.selectedData.details[field] = element.value;
                // Update hidden form fields
                const hiddenField = document.getElementById(`hidden_${field}`);
                if (hiddenField) {
                    hiddenField.value = element.value;
                }
            });
        });        // Form submission handler
        const form = document.getElementById('appointmentBookingForm');
        form?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleFormSubmission();
        });

        // Debug button handler
        const debugBtn = document.getElementById('debug_form_state');
        debugBtn?.addEventListener('click', () => {
            this.logCurrentFormState();
        });
    }

    handleFormSubmission() {
        // Final validation before submission
        if (!this.validateCurrentStep()) {
            return;
        }

        // Update all hidden fields with current data
        this.updateHiddenFormFields();

        // Show loading state
        const submitBtn = document.getElementById('submit_btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Booking Appointment...';
        }

        // Submit the form
        const form = document.getElementById('appointmentBookingForm');
        if (form) {

            // set form fields before submission
            document.getElementById('selected_doctor_id').value = this.selectedData.doctor?.id || '';
            document.getElementById('selected_service_id').value = this.selectedData.service?.id || '';
            document.getElementById('appointment_date').value = this.selectedData.date || '';
            document.getElementById('appointment_time').value = this.selectedData.time || '';
            document.getElementById('reason').value = this.selectedData.details.reason || '';
            document.getElementById('symptoms').value = this.selectedData.details.symptoms || '';
            document.getElementById('priority').value = this.selectedData.details.priority || '';
            document.getElementById('appointment_type').value = this.selectedData.details.appointment_type || '';
            document.getElementById('terms_agreement').checked = true; // Ensure terms are agreed
            // Submit the form
            // log form fields before submission
            console.log('Submitting form with data:', {
                selected_doctor_id: this.selectedData.doctor?.id || '',
                selected_service_id: this.selectedData.service?.id || '',
                appointment_date: this.selectedData.date || '',
                appointment_time: this.selectedData.time || '',
                reason: this.selectedData.details.reason || '',
                symptoms: this.selectedData.details.symptoms || '',
                priority: this.selectedData.details.priority || '',
                appointment_type: this.selectedData.details.appointment_type || '',
                terms_agreement: document.getElementById('terms_agreement')?.checked || false
            });

            form.submit();
        }
    }
    updateHiddenFormFields() {
        // Ensure details object exists
        if (!this.selectedData.details || typeof this.selectedData.details !== 'object') {
            this.selectedData.details = {};
        }

        // Update patient details
        document.getElementById('hidden_reason').value = this.selectedData.details.reason || '';
        document.getElementById('hidden_symptoms').value = this.selectedData.details.symptoms || '';
        document.getElementById('hidden_priority').value = this.selectedData.details.priority || '';
        document.getElementById('hidden_appointment_type').value = this.selectedData.details.appointment_type ||
            '';

        // Update appointment details
        document.getElementById('selected_date').value = this.selectedData.date || '';
        document.getElementById('selected_time').value = this.selectedData.time || '';
    }

    // Debug method to log current form state
    logCurrentFormState() {
        console.group('🐛 APPOINTMENT BOOKING DEBUG - CURRENT FORM STATE');

        // 1. Basic Info
        console.log('📊 Basic Information:');
        console.log('  • Current Step:', this.currentStep);
        console.log('  • Total Steps:', this.totalSteps);
        console.log('  • Validated Steps:', Array.from(this.validatedSteps));

        // 2. Selected Data
        console.log('\n📋 Selected Data:');
        console.log('  • Doctor:', this.selectedData.doctor);
        console.log('  • Service:', this.selectedData.service);
        console.log('  • Date:', this.selectedData.date);
        console.log('  • Time:', this.selectedData.time);
        console.log('  • Details:', this.selectedData.details);

        // 3. Form Fields
        console.log('\n📝 Form Field Values:');
        const formFields = [
            'selected_doctor_id', 'selected_service_id', 'appointment_date', 'appointment_time',
            'reason', 'symptoms', 'priority', 'appointment_type', 'terms_agreement'
        ];

        formFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const value = field.type === 'checkbox' ? field.checked : field.value;
                console.log(`  • ${fieldId}:`, value);
            } else {
                console.log(`  • ${fieldId}: [FIELD NOT FOUND]`);
            }
        });

        // 4. Hidden Fields
        console.log('\n🔒 Hidden Form Fields:');
        const hiddenFields = [
            'hidden_reason', 'hidden_symptoms', 'hidden_priority', 'hidden_appointment_type',
            'selected_date', 'selected_time'
        ];

        hiddenFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                console.log(`  • ${fieldId}:`, field.value);
            } else {
                console.log(`  • ${fieldId}: [FIELD NOT FOUND]`);
            }
        });

        // 5. Validation Status
        console.log('\n✅ Validation Status:');
        console.log('  • Current Step Valid:', this.validateCurrentStep());
        console.log('  • Doctor Selected:', !!this.selectedData.doctor);
        console.log('  • Service Selected:', !!this.selectedData.service); console.log('  • Date Selected:', !!this.selectedData.date);
        console.log('  • Time Selected:', !!this.selectedData.time);
        console.log('  • Reason Provided:', !!(this.selectedData.details && this.selectedData.details.reason || document.getElementById('reason')?.value));
        console.log('  • Priority Selected:', !!(this.selectedData.details && this.selectedData.details.priority || document.getElementById('priority')?.value));
        console.log('  • Terms Agreed:', document.getElementById('terms_agreement')?.checked);

        // 6. Browser/DOM Info
        console.log('\n🌐 Browser/DOM Info:');
        console.log('  • User Agent:', navigator.userAgent);
        console.log('  • URL:', window.location.href);
        console.log('  • Timestamp:', new Date().toISOString());

        // 7. Complete form data as would be submitted
        console.log('\n📤 Complete Form Data (as would be submitted):');
        const form = document.getElementById('appointmentBookingForm');
        if (form) {
            const formData = new FormData(form);
            const formObject = {};
            for (let [key, value] of formData.entries()) {
                formObject[key] = value;
            }
            console.log(formObject);
        } else {
            console.log('  [FORM NOT FOUND]');
        }

        console.groupEnd();

    }
    async loadInitialDoctors() {
        console.log('loadInitialDoctors called');
        this.showDoctorsLoading(true);

        try {
            console.log('Fetching initial doctors...');
            const response = await fetch('/api/appointments/search-doctors?initial=1');
            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('API response data:', data);

            if (data.success) {
                console.log('Displaying doctors:', data.doctors.length);
                this.displayDoctors(data.doctors);
            } else {
                console.error('API returned error:', data);
                this.showDoctorsError();
            }
        } catch (error) {
            console.error('Error loading doctors:', error);
            this.showDoctorsError();
        } finally {
            this.showDoctorsLoading(false);
        }
    }

    async searchDoctors(query) {
        if (!query && !this.hasActiveFilters()) {
            this.loadInitialDoctors();
            return;
        }

        this.showDoctorsLoading(true);

        try {
            const params = new URLSearchParams();
            if (query) params.append('q', query);

            const serviceId = document.getElementById('service_filter')?.value;
            const specialization = document.getElementById('specialization_filter')?.value;

            if (serviceId) params.append('service_id', serviceId);
            if (specialization) params.append('specialization', specialization);

            const response = await fetch(`/api/appointments/search-doctors?${params}`);
            const data = await response.json();

            if (data.success) {
                this.displayDoctors(data.doctors);
            }
        } catch (error) {
            console.error('Error searching doctors:', error);
            this.showDoctorsError();
        } finally {
            this.showDoctorsLoading(false);
        }
    }

    applyFilters() {
        const query = document.getElementById('doctor_search')?.value || '';
        this.searchDoctors(query);
    }

    hasActiveFilters() {
        const serviceFilter = document.getElementById('service_filter')?.value;
        const specializationFilter = document.getElementById('specialization_filter')?.value;
        return serviceFilter || specializationFilter;
    }
    displayDoctors(doctors) {
        console.log('displayDoctors called with:', doctors);
        const container = document.getElementById('doctors_grid');
        const template = document.getElementById('doctor_card_template');
        const noResults = document.getElementById('no_doctors_found');

        console.log('Container found:', !!container);
        console.log('Template found:', !!template);

        if (!container || !template) {
            console.error('Missing container or template elements');
            return;
        }

        container.innerHTML = '';

        if (doctors.length === 0) {
            console.log('No doctors found, showing no results');
            noResults?.classList.remove('hidden');
            return;
        }

        noResults?.classList.add('hidden');
        console.log('Processing', doctors.length, 'doctors');

        doctors.forEach((doctor, index) => {
            console.log(`Processing doctor ${index}:`, doctor);
            const card = template.content.cloneNode(true);

            // Populate card data
            card.querySelector('.doctor-initials').textContent = this.getInitials(doctor.user?.name ||
                doctor.name);
            card.querySelector('.doctor-name').textContent = `Dr. ${doctor.user?.name || doctor.name}`;
            card.querySelector('.doctor-specialization').textContent = doctor.specialization ||
                'General Practice';
            card.querySelector('.doctor-experience').textContent =
                `${doctor.experience_years || 0} years experience`;
            card.querySelector('.doctor-fee').textContent = `$${doctor.consultation_fee || '0'}`;
            card.querySelector('.doctor-availability').textContent = doctor.is_available ?
                'Available today' : 'Schedule in advance';

            // Services tags
            const servicesContainer = card.querySelector('.doctor-services');
            if (doctor.services && doctor.services.length > 0) {
                const tags = doctor.services.slice(0, 3).map(service =>
                    `<span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">${service.name}</span>`
                ).join('');
                servicesContainer.innerHTML = `<div class="flex flex-wrap gap-1">${tags}</div>`;
            }

            // Select button
            const selectBtn = card.querySelector('.select-doctor-btn');
            selectBtn.addEventListener('click', () => this.selectDoctor(doctor));

            container.appendChild(card);
        });
    }
    selectDoctor(doctor) {
        this.selectedData.doctor = doctor;
        document.getElementById('selected_doctor_id').value = doctor.id;

        // Invalidate all subsequent steps when doctor changes
        this.invalidateStepsFrom(2);

        // Update selected doctor info in step 2
        this.updateSelectedDoctorInfo(doctor);

        // Update calendar with new doctor
        if (this.calendar) {
            this.calendar.setDoctor(doctor.id);
        }

        // Load services for the selected doctor
        this.loadDoctorServices(doctor.id);

        // Move to next step (service selection)
        this.nextStep();
    }

    // Method to invalidate steps from a given step number onwards
    invalidateStepsFrom(fromStep) {
        // Remove validated steps from the specified step onwards
        for (let step = fromStep; step <= this.totalSteps; step++) {
            this.validatedSteps.delete(step);
        }
        // Clear related data based on which step we're invalidating from
        if (fromStep <= 2) {
            this.selectedData.service = null;
            this.selectedData.date = null;
            this.selectedData.time = null;
            this.selectedData.details = {};
        } else if (fromStep <= 3) {
            this.selectedData.date = null;
            this.selectedData.time = null;
            this.selectedData.details = {};
        } else if (fromStep <= 4) {
            this.selectedData.details = {};
        }

        // Update the stepper display
        this.updateProgressStepper();
    }

    updateSelectedDoctorInfo(doctor) {
        const doctorInitials = document.getElementById('selected_doctor_initials');
        const doctorName = document.getElementById('selected_doctor_name');
        const doctorSpecialization = document.getElementById('selected_doctor_specialization');

        if (doctorInitials) doctorInitials.textContent = this.getInitials(doctor.user?.name || doctor.name);
        if (doctorName) doctorName.textContent = `Dr. ${doctor.user?.name || doctor.name}`;
        if (doctorSpecialization) doctorSpecialization.textContent = doctor.specialization ||
            'General Practice';
    }

    async loadDoctorServices(doctorId) {
        const servicesGrid = document.getElementById('services_grid');
        const loading = document.getElementById('services_loading');

        if (!servicesGrid || !loading) return;

        loading.classList.remove('hidden');
        servicesGrid.innerHTML = '';

        try {
            const response = await fetch(`/api/doctors/${doctorId}/services`);
            const data = await response.json();

            if (data.success && data.services) {
                this.displayServices(data.services);
            }
        } catch (error) {
            console.error('Error loading services:', error);
        } finally {
            loading.classList.add('hidden');
        }
    }

    displayServices(services) {
        const container = document.getElementById('services_grid');
        const template = document.getElementById('service_card_template');

        if (!container || !template) return;

        container.innerHTML = '';

        services.forEach(service => {
            const card = template.content.cloneNode(true);

            card.querySelector('.service-name').textContent = service.name;
            card.querySelector('.service-description').textContent = service.description ||
                'Professional medical service';
            card.querySelector('.service-duration').textContent = `${service.duration || 30} minutes`;
            card.querySelector('.service-price').textContent =
                `$${service.price || service.consultation_fee || '0'}`;

            const radioInput = card.querySelector('.service-radio-input');
            radioInput.value = service.id;
            radioInput.addEventListener('change', () => this.selectService(service));

            const serviceCard = card.querySelector('.service-card');
            serviceCard.addEventListener('click', () => {
                radioInput.checked = true;
                radioInput.dispatchEvent(new Event('change'));
            });

            container.appendChild(card);
        });
    }

    selectService(service) {
        this.selectedData.service = service;
        document.getElementById('selected_service_id').value = service.id;

        // Invalidate subsequent steps when service changes (keep doctor selection)
        this.invalidateStepsFrom(3);

        // Update calendar with new service
        if (this.calendar) {
            this.calendar.setService(service.id);
        }

        // Remove selection from other cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        });

        // Highlight selected card
        const selectedCard = event.target.closest('.service-card');
        selectedCard?.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');

        // Update selected service info display for step 3
        this.updateSelectedServiceInfo(service);

        // Move to next step (date/time selection)
        this.nextStep();
    }
    // this.updateSelectedServiceInfo(service);

    updateSelectedServiceInfo(service) {
        const serviceIcon = document.getElementById('selected_service_icon');
        const serviceName = document.getElementById('selected_service_name');
        const serviceDetails = document.getElementById('selected_service_details');
        const servicePrice = document.getElementById('selected_service_price');

        if (serviceName) serviceName.textContent = service.name;
        if (serviceDetails) serviceDetails.textContent =
            `${service.duration || 30} minutes • ${service.description || 'Professional medical service'}`;
        if (servicePrice) servicePrice.textContent = `$${service.price || service.consultation_fee || '0'}`;

        // Update icon based on service type
        if (serviceIcon) {
            const iconMap = {
                'consultation': 'fa-stethoscope',
                'checkup': 'fa-heartbeat',
                'vaccination': 'fa-syringe',
                'therapy': 'fa-hands-helping',
                'surgery': 'fa-procedures',
                'diagnostic': 'fa-microscope'
            };

            const serviceType = service.type || 'consultation';
            const iconClass = iconMap[serviceType] || 'fa-stethoscope';

            serviceIcon.className = `fas ${iconClass}`;
        } // Show the service info section
        const serviceInfoSection = document.getElementById('selected_service_info');
        if (serviceInfoSection) {
            serviceInfoSection.classList.remove('hidden');
        }
    }

    async loadTimeSlots(date) {
        if (!date || !this.selectedData.doctor || !this.selectedData.service) return;

        const container = document.getElementById('timeslots_container');
        const loading = document.getElementById('timeslots_loading');
        const noSlots = document.getElementById('no_slots_available');
        const timeslotsGrid = document.getElementById('timeslots_grid');
        const noDateSelected = document.getElementById('no_date_selected');

        if (!container) return;

        // Show loading state
        loading?.classList.remove('hidden');
        timeslotsGrid?.classList.add('hidden');
        noSlots?.classList.add('hidden');
        noDateSelected?.classList.add('hidden');

        try {
            const params = new URLSearchParams({
                doctor_id: this.selectedData.doctor.id,
                date: date,
                service_id: this.selectedData.service.id
            });

            const response = await fetch(`/api/appointments/available-slots?${params}`);
            const data = await response.json();

            if (data.success && data.slots && data.slots.length > 0) {
                this.displayTimeSlots(data.slots);
                timeslotsGrid?.classList.remove('hidden');
            } else {
                noSlots?.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error loading time slots:', error);
            noSlots?.classList.remove('hidden');
        } finally {
            loading?.classList.add('hidden');
        }
    }

    displayTimeSlots(slots) {
        const container = document.getElementById('timeslots_grid');
        if (!container) return;

        container.innerHTML = '';

        slots.forEach(slot => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className =
                'timeslot-btn p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors text-sm font-medium text-gray-700 dark:text-gray-300';
            button.textContent = slot.formatted_start || slot.start_time;
            button.setAttribute('data-time', slot.start_time);

            button.addEventListener('click', () => {
                this.selectTimeSlot(slot.start_time, button);
            });

            container.appendChild(button);
        });
    }
    selectTimeSlot(time, button) {
        this.selectedData.time = time;
        document.getElementById('selected_time').value = time;

        // Remove selection from other buttons
        document.querySelectorAll('.timeslot-btn').forEach(btn => {
            btn.classList.remove('border-blue-500', 'bg-blue-500', 'text-white');
            btn.classList.add('border-gray-300', 'dark:border-gray-600');
        });

        // Highlight selected button
        button.classList.remove('border-gray-300', 'dark:border-gray-600');
        button.classList.add('border-blue-500', 'bg-blue-500', 'text-white');

        // Move to step 4 (patient details)
        this.nextStep();
    }


    updateConfirmationDetails() {

        if (!this.selectedData.doctor || !this.selectedData.service || !this.selectedData.date || !this
            .selectedData.time) {
            return;
        }

        // Update doctor info in confirmation
        const doctorName = document.getElementById('confirm_doctor_name');
        const doctorSpecialization = document.getElementById('confirm_doctor_specialization');

        if (doctorName) doctorName.textContent =
            `Dr. ${this.selectedData.doctor.user?.name || this.selectedData.doctor.name}`;
        if (doctorSpecialization) doctorSpecialization.textContent = this.selectedData.doctor.specialization;

        // Update service info
        const serviceName = document.getElementById('confirm_service_name');
        const servicePrice = document.getElementById('confirm_service_price');

        if (serviceName) serviceName.textContent = this.selectedData.service.name;
        if (servicePrice) servicePrice.textContent =
            `$${this.selectedData.service.price || this.selectedData.service.consultation_fee || '0'}`;

        // Update date and time
        const appointmentDate = document.getElementById('confirm_appointment_date');
        const appointmentTime = document.getElementById('confirm_appointment_time');

        if (appointmentDate) appointmentDate.textContent = new Date(this.selectedData.date)
            .toLocaleDateString();
        if (appointmentTime) appointmentTime.textContent = this.selectedData.time;        // Update patient details in confirmation
        const confirmReason = document.getElementById('confirm_reason');
        const confirmSymptoms = document.getElementById('confirm_symptoms');
        const confirmPriority = document.getElementById('confirm_priority');
        const confirmAppointmentType = document.getElementById('confirm_appointment_type');

        // Ensure details object exists
        if (!this.selectedData.details || typeof this.selectedData.details !== 'object') {
            this.selectedData.details = {};
        }

        if (confirmReason) confirmReason.textContent = this.selectedData.details.reason || 'Not specified';
        if (confirmSymptoms) confirmSymptoms.textContent = this.selectedData.details.symptoms ||
            'None specified';
        if (confirmPriority) {
            const priorityLabels = {
                'low': 'Low Priority',
                'medium': 'Medium Priority',
                'high': 'High Priority',
                'urgent': 'Urgent'
            };
            confirmPriority.textContent = priorityLabels[this.selectedData.details.priority] || 'Not specified';
        }
        if (confirmAppointmentType) {
            const typeLabels = {
                'consultation': 'Consultation',
                'checkup': 'Check-up',
                'follow_up': 'Follow-up',
                'emergency': 'Emergency'
            };
            confirmAppointmentType.textContent = typeLabels[this.selectedData.details.appointment_type] ||
                'Consultation';
        }
    }

    nextStep() {
        if (!this.validateCurrentStep()) {
            return;
        }

        // Mark current step as validated
        this.validatedSteps.add(this.currentStep);

        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.updateStepDisplay();

            // Update confirmation details when reaching step 5
            if (this.currentStep === 5) {
                this.updateConfirmationDetails();
            }
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStepDisplay();
        }
    }
    validateCurrentStep() {
        switch (this.currentStep) {
            case 1:
                if (!this.selectedData.doctor) {
                    this.showError('Please select a doctor to continue.');
                    return false;
                }
                break;
            case 2:
                if (!this.selectedData.service) {
                    this.showError('Please select a service to continue.');
                    return false;
                }
                break;
            case 3:
                if (!this.selectedData.date) {
                    this.showError('Please select an appointment date.');
                    return false;
                }
                if (!this.selectedData.time) {
                    this.showError('Please select an appointment time.');
                    return false;
                }
                break;
            case 4:
                // Validate patient details
                const reason = document.getElementById('reason')?.value;
                const priority = document.getElementById('priority')?.value;

                if (!reason || reason.trim() === '') {
                    this.showError('Please provide a reason for your appointment.');
                    return false;
                } if (!priority) {
                    this.showError('Please select the priority level for your appointment.');
                    return false;
                }

                // Ensure details object exists
                if (!this.selectedData.details || typeof this.selectedData.details !== 'object') {
                    this.selectedData.details = {};
                }

                // Update selected data
                this.selectedData.details.reason = reason;
                this.selectedData.details.priority = priority;
                this.selectedData.details.symptoms = document.getElementById('symptoms')?.value || '';
                this.selectedData.details.appointment_type = document.getElementById('appointment_type')?.value || 'consultation';
                break;
        }
        return true;
    }

    updateStepDisplay() {
        // Hide all steps
        for (let i = 1; i <= this.totalSteps; i++) {
            const stepElement = document.getElementById(`step_${i}`);
            if (stepElement) {
                stepElement.classList.add('hidden');
            }
        }

        // Show current step
        const currentStepElement = document.getElementById(`step_${this.currentStep}`);
        if (currentStepElement) {
            currentStepElement.classList.remove('hidden');
        }

        // Update progress stepper
        this.updateProgressStepper();

        // Update step display counter
        const stepDisplay = document.getElementById('current_step_display');
        if (stepDisplay) {
            stepDisplay.textContent = this.currentStep;
        }

        // Update step title
        const stepTitle = document.getElementById('step_title');
        if (stepTitle) {
            const titles = {
                1: 'Find Your Doctor',
                2: 'Choose Service',
                3: 'Select Date & Time',
                4: 'Patient Details',
                5: 'Confirm Booking'
            };
            stepTitle.textContent = titles[this.currentStep] || 'Find Your Doctor';
        }

        // Update navigation buttons
        this.updateNavigationButtons();
    }

    updateProgressStepper() {
        const stepper = document.querySelector('.progress-stepper');
        if (!stepper) return;

        // Update progress bar
        const progressBar = stepper.querySelector('.bg-gradient-to-r');
        if (progressBar) {
            const progressWidth = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
            progressBar.style.width = `${progressWidth}%`;
        }

        // Update step circles and labels
        stepper.querySelectorAll('.step').forEach((stepElement, index) => {
            const stepNumber = index + 1;
            const stepCircle = stepElement.querySelector('.step-circle');
            const stepLabel = stepElement.querySelector('.step-label');
            const isAccessible = this.isStepAccessible(stepNumber);
            const isCompleted = this.validatedSteps.has(stepNumber) && stepNumber < this.currentStep;
            const isCurrent = stepNumber === this.currentStep;

            // Reset all classes
            if (stepCircle) {
                stepCircle.className = 'step-circle relative flex items-center justify-center w-10 h-10 rounded-full border-3 transition-all duration-300 transform';
                if (isCompleted) {
                    // Completed step - green with step icon instead of checkmark
                    stepCircle.classList.add(
                        'bg-gradient-to-r', 'from-green-500', 'to-green-600',
                        'border-green-500', 'text-white', 'shadow-lg'
                    );
                    const stepIcons = [
                        'fas fa-user-md',     // Doctor
                        'fas fa-stethoscope', // Service  
                        'fas fa-calendar',    // Date/Time
                        'fas fa-edit',        // Details
                        'fas fa-check'        // Confirm
                    ];
                    stepCircle.innerHTML = `<i class="${stepIcons[stepNumber - 1]} text-sm"></i>`;
                } else if (isCurrent) {
                    // Current step - blue with icon and pulse
                    stepCircle.classList.add(
                        'bg-gradient-to-r', 'from-blue-500', 'to-indigo-600',
                        'border-blue-500', 'text-white', 'shadow-lg',
                        'ring-4', 'ring-blue-200', 'dark:ring-blue-800'
                    );
                    const stepIcons = [
                        'fas fa-user-md',     // Doctor
                        'fas fa-stethoscope', // Service  
                        'fas fa-calendar',    // Date/Time
                        'fas fa-edit',        // Details
                        'fas fa-check'        // Confirm
                    ];
                    stepCircle.innerHTML = `<i class="${stepIcons[stepNumber - 1]} text-sm"></i>`;

                    // Add pulse animation
                    const existingPulse = stepCircle.querySelector('.animate-ping');
                    if (!existingPulse) {
                        const pulseDiv = document.createElement('div');
                        pulseDiv.className = 'absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-25';
                        stepCircle.appendChild(pulseDiv);
                    }
                } else if (isAccessible) {
                    // Accessible future step - white with number
                    stepCircle.classList.add(
                        'bg-white', 'dark:bg-gray-800', 'border-gray-300',
                        'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300',
                        'shadow-md', 'hover:border-blue-400', 'hover:text-blue-600'
                    );
                    stepCircle.innerHTML = `<span class="text-sm font-semibold">${stepNumber}</span>`;
                } else {
                    // Inaccessible step - gray and disabled
                    stepCircle.classList.add(
                        'bg-gray-100', 'dark:bg-gray-700', 'border-gray-200',
                        'dark:border-gray-600', 'text-gray-400', 'dark:text-gray-500',
                        'opacity-50', 'cursor-not-allowed'
                    );
                    stepCircle.innerHTML = `<span class="text-sm font-semibold">${stepNumber}</span>`;
                }

                // Remove existing pulse animations if not current step
                if (!isCurrent) {
                    const pulseElements = stepCircle.querySelectorAll('.animate-ping');
                    pulseElements.forEach(pulse => pulse.remove());
                }
            }

            // Update step labels
            if (stepLabel) {
                const labelText = stepLabel.querySelector('.text-xs');
                if (labelText) {
                    labelText.className = 'text-xs font-semibold leading-tight transition-all duration-300';

                    if (isCompleted) {
                        labelText.classList.add('text-green-600', 'dark:text-green-400');
                    } else if (isCurrent) {
                        labelText.classList.add('text-blue-600', 'dark:text-blue-400');
                    } else if (isAccessible) {
                        labelText.classList.add('text-gray-600', 'dark:text-gray-400');
                    } else {
                        labelText.classList.add('text-gray-400', 'dark:text-gray-500', 'opacity-50');
                    }
                }
            }

            // Update cursor and click behavior
            stepElement.style.cursor = isAccessible ? 'pointer' : 'not-allowed';
            if (!isAccessible) {
                stepElement.onclick = null;
            }
        });

        // Update the step info at the bottom
        const stepCounter = stepper.querySelector('span.font-semibold');
        if (stepCounter) {
            stepCounter.textContent = this.currentStep;
        }

        const stepTitle = stepper.querySelector('.text-lg.font-semibold');
        if (stepTitle) {
            const titles = {
                1: 'Find Your Preferred Doctor',
                2: 'Choose the Right Service',
                3: 'Select Your Appointment Time',
                4: 'Provide Additional Details',
                5: 'Confirm Your Appointment'
            };
            stepTitle.textContent = titles[this.currentStep] || `Step ${this.currentStep}`;
        }
    }

    updateNavigationButtons() {
        const backBtn = document.getElementById('back_btn');
        const nextBtn = document.getElementById('next_btn');
        const submitBtn = document.getElementById('submit_btn');

        if (backBtn) {
            if (this.currentStep > 1) {
                backBtn.disabled = false;
                backBtn.style.display = 'block';
            } else {
                backBtn.disabled = true;
                backBtn.style.display = 'block'; // Keep visible but disabled
            }
        }

        if (nextBtn && submitBtn) {
            const nextBtnText = document.getElementById('next_btn_text');
            if (this.currentStep === this.totalSteps) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            } else {
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';

                // Update next button text
                if (nextBtnText) {
                    if (this.currentStep < this.totalSteps) {
                        nextBtnText.innerHTML = 'Next <i class="fas fa-arrow-right ml-2"></i>';
                    } else {
                        nextBtnText.innerHTML = '<i class="fas fa-calendar-check mr-2"></i> Book Appointment';
                    }
                }
            }
        }
    }

    showError(message) {
        // Create or update error alert
        let errorAlert = document.getElementById('step_error_alert');

        if (!errorAlert) {
            errorAlert = document.createElement('div');
            errorAlert.id = 'step_error_alert';
            errorAlert.className =
                'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-4';

            const currentStepElement = document.getElementById(`step_${this.currentStep}`);
            if (currentStepElement) {
                currentStepElement.insertBefore(errorAlert, currentStepElement.firstChild);
            }
        }

        errorAlert.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (errorAlert && errorAlert.parentNode) {
                errorAlert.remove();
            }
        }, 5000);
    }

    showDoctorsLoading(show) {
        const loading = document.getElementById('doctors_loading');
        const grid = document.getElementById('doctors_grid');

        if (show) {
            loading?.classList.remove('hidden');
            grid?.classList.add('opacity-50');
        } else {
            loading?.classList.add('hidden');
            grid?.classList.remove('opacity-50');
        }
    }

    showDoctorsError() {
        const container = document.getElementById('doctors_grid');
        if (container) {
            container.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Error loading doctors. Please try again.</p>
                    <button onclick="window.appointmentBooking.loadInitialDoctors()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Retry
                    </button>
                </div>
            `;
        }
    }

    getInitials(name) {
        if (!name) return '??';
        return name.split(' ')
            .map(word => word.charAt(0).toUpperCase())
            .slice(0, 2)
            .join('');
    }

}

