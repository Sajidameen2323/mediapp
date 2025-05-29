@extends('layouts.app')

@section('title', 'Enhanced Appointment Booking Demo - MediCare')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                Enhanced Appointment Booking Demo
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Testing the enhanced booking flow with modern UI (Demo Mode)
            </p>
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                <p class="text-blue-800 dark:text-blue-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    This is a demo version. To complete actual bookings, please <a href="{{ route('login') }}" class="underline font-semibold">login as a patient</a>.
                </p>
            </div>
        </div>

        {{-- Progress Stepper --}}
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-4">
                    <!-- Step 1: Doctor Selection -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">Doctor</span>
                    </div>
                    
                    <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    
                    <!-- Step 2: Service Selection -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Service</span>
                    </div>
                    
                    <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    
                    <!-- Step 3: Date & Time -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</span>
                    </div>
                    
                    <div class="w-8 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    
                    <!-- Step 4: Confirmation -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-full text-sm font-medium">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Confirm</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 1: Doctor Selection --}}
        <div id="step_1" class="step-content bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-user-md text-blue-600 mr-2"></i>
                    Select a Doctor
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Choose from our available medical professionals</p>
            </div>

            {{-- Search and Filters --}}
            <div class="mb-6 space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" id="doctor_search" placeholder="Search doctors by name or specialization..." 
                                   class="w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" id="clear_doctor_search" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Doctors Grid --}}
            <div id="doctors_loading" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Loading doctors...</span>
                </div>
            </div>

            <div id="doctors_grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Doctors will be loaded here -->
            </div>
        </div>

        {{-- Step 2: Service Selection --}}
        <div id="step_2" class="step-content bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-stethoscope text-blue-600 mr-2"></i>
                    Select a Service
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Choose the medical service you need</p>
            </div>

            <div id="services_loading" class="hidden">
                <div class="flex items-center justify-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600 dark:text-gray-400">Loading services...</span>
                </div>
            </div>

            <div id="services_grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Services will be loaded here -->
            </div>
        </div>

        {{-- Step 3: Date & Time Selection --}}
        <div id="step_3" class="step-content bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Select Date & Time
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Pick your preferred appointment date and time</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Date Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Date</label>
                    <input type="date" id="appointment_date" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Time Slots --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Time Slots</label>
                    
                    <div id="timeslots_loading" class="hidden">
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">Loading slots...</span>
                        </div>
                    </div>

                    <div id="no_date_selected" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar text-2xl mb-2"></i>
                        <p>Please select a date to view available time slots</p>
                    </div>

                    <div id="no_slots_available" class="hidden text-center py-8 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar-times text-2xl mb-2"></i>
                        <p>No time slots available for this date</p>
                    </div>

                    <div id="timeslots_grid" class="hidden">
                        <div id="timeslots_container" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <!-- Time slots will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 4: Confirmation --}}
        <div id="step_4" class="step-content bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6 hidden">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Confirm Your Appointment
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Review your appointment details</p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Appointment Summary</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Doctor</label>
                                <p id="confirm_doctor_name" class="text-gray-900 dark:text-gray-100">-</p>
                                <p id="confirm_doctor_specialization" class="text-sm text-gray-600 dark:text-gray-400">-</p>
                            </div>
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Service</label>
                                <p id="confirm_service_name" class="text-gray-900 dark:text-gray-100">-</p>
                                <p id="confirm_service_duration" class="text-sm text-gray-600 dark:text-gray-400">-</p>
                            </div>
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Date & Time</label>
                                <p id="confirm_date" class="text-gray-900 dark:text-gray-100">-</p>
                                <p id="confirm_time" class="text-sm text-gray-600 dark:text-gray-400">-</p>
                            </div>
                            <div class="mb-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Consultation Fee</label>
                                <p id="confirm_fee" class="text-xl font-semibold text-green-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-yellow-800 dark:text-yellow-200">Demo Mode Notice</h4>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                This is a demonstration of the enhanced booking flow. To complete an actual appointment booking, please log in as a patient.
                            </p>
                            <a href="{{ route('login') }}" class="inline-block mt-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                Go to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex justify-between items-center bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <button type="button" id="back_btn" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed hidden">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </button>
            
            <div class="flex-1"></div>
            
            <button type="button" id="next_btn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Next<i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

{{-- Templates --}}
<template id="doctor_card_template">
    <div class="doctor-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow cursor-pointer">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <img class="w-16 h-16 rounded-full object-cover doctor-image" 
                     src="/images/default-doctor.png" 
                     alt="Doctor">
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="doctor-name text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Dr. Name</h3>
                <p class="doctor-specialization text-sm text-gray-600 dark:text-gray-400 mb-2">Specialization</p>
                <div class="doctor-services text-xs text-gray-500 dark:text-gray-500 mb-3">
                    <!-- Services tags will be added here -->
                </div>
                <button type="button" class="select-doctor-btn w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                    Select Doctor
                </button>
            </div>
        </div>
    </div>
</template>

<template id="service_card_template">
    <div class="service-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow cursor-pointer">
        <div class="mb-4">
            <h3 class="service-name text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Service Name</h3>
            <p class="service-description text-sm text-gray-600 dark:text-gray-400 mb-3">Service description</p>
            <div class="flex justify-between items-center text-sm">
                <span class="service-duration text-gray-500 dark:text-gray-500">30 minutes</span>
                <span class="service-price text-lg font-semibold text-green-600">$50</span>
            </div>
        </div>
        <button type="button" class="select-service-btn w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
            Select Service
        </button>
    </div>
</template>

<template id="timeslot_template">
    <button type="button" class="timeslot-btn w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-500 transition-colors">
        <span class="slot-time">9:00 AM</span>
    </button>
</template>

<script>
class AppointmentBookingDemo {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.selectedData = {
            doctor: null,
            service: null,
            date: null,
            time: null
        };
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateStepDisplay();
        this.loadInitialDoctors();
    }

    setupEventListeners() {
        document.getElementById('next_btn').addEventListener('click', () => this.nextStep());
        document.getElementById('back_btn').addEventListener('click', () => this.prevStep());
        
        this.setupDoctorSearch();
        
        document.getElementById('appointment_date')?.addEventListener('change', (e) => {
            this.selectedData.date = e.target.value;
            this.loadTimeSlots(e.target.value);
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

    async loadInitialDoctors() {
        this.showDoctorsLoading(true);
        
        try {
            const response = await fetch('/public/appointments/search-doctors?initial=1');
            const data = await response.json();
            
            if (data.success) {
                this.displayDoctors(data.doctors);
            }
        } catch (error) {
            console.error('Error loading doctors:', error);
            this.showDoctorsError();
        } finally {
            this.showDoctorsLoading(false);
        }
    }

    async searchDoctors(query) {
        if (!query) {
            this.loadInitialDoctors();
            return;
        }

        this.showDoctorsLoading(true);

        try {
            const params = new URLSearchParams();
            if (query) params.append('q', query);

            const response = await fetch(`/public/appointments/search-doctors?${params}`);
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

    displayDoctors(doctors) {
        const container = document.getElementById('doctors_grid');
        const template = document.getElementById('doctor_card_template');

        if (!container || !template) return;

        container.innerHTML = '';

        if (!doctors || doctors.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-user-md text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">No doctors found matching your criteria.</p>
                </div>
            `;
            return;
        }

        doctors.forEach(doctor => {
            const card = template.content.cloneNode(true);
            
            card.querySelector('.doctor-name').textContent = `Dr. ${doctor.user?.name || doctor.name}`;
            card.querySelector('.doctor-specialization').textContent = doctor.specialization || 'General Practice';

            if (doctor.services && doctor.services.length > 0) {
                const servicesContainer = card.querySelector('.doctor-services');
                const tags = doctor.services.slice(0, 3).map(service => 
                    `<span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full">${service.name}</span>`
                ).join('');
                servicesContainer.innerHTML = `<div class="flex flex-wrap gap-1">${tags}</div>`;
            }

            const selectBtn = card.querySelector('.select-doctor-btn');
            selectBtn.addEventListener('click', () => this.selectDoctor(doctor));

            container.appendChild(card);
        });
    }

    selectDoctor(doctor) {
        this.selectedData.doctor = doctor;
        this.nextStep();
        this.loadDoctorServices(doctor.id);
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
            card.querySelector('.service-description').textContent = service.description || 'Professional medical service';
            card.querySelector('.service-duration').textContent = `${service.duration || 30} minutes`;
            card.querySelector('.service-price').textContent = `$${service.price || '50'}`;

            const selectBtn = card.querySelector('.select-service-btn');
            selectBtn.addEventListener('click', () => this.selectService(service));

            container.appendChild(card);
        });
    }

    selectService(service) {
        this.selectedData.service = service;
        this.nextStep();
        this.setupDateInput();
    }

    setupDateInput() {
        const dateInput = document.getElementById('appointment_date');
        if (dateInput) {
            const today = new Date();
            const minDate = new Date(today.getTime() + 24 * 60 * 60 * 1000); // Tomorrow
            dateInput.min = minDate.toISOString().split('T')[0];
            
            const maxDate = new Date(today.getTime() + 30 * 24 * 60 * 60 * 1000); // 30 days from now
            dateInput.max = maxDate.toISOString().split('T')[0];
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

            const response = await fetch(`/public/appointments/available-slots?${params}`);
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
        const container = document.getElementById('timeslots_container');
        const template = document.getElementById('timeslot_template');

        if (!container || !template) return;

        container.innerHTML = '';

        slots.forEach(slot => {
            const button = template.content.cloneNode(true);
            const timeBtn = button.querySelector('.timeslot-btn');
            const timeSpan = button.querySelector('.slot-time');
            
            timeSpan.textContent = slot.formatted_start || slot.start_time;
            timeBtn.addEventListener('click', () => this.selectTimeSlot(slot));

            container.appendChild(button);
        });
    }

    selectTimeSlot(slot) {
        this.selectedData.time = slot;
        
        // Remove previous selection
        document.querySelectorAll('.timeslot-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('border-gray-300', 'dark:border-gray-600');
        });
        
        // Add selection to clicked button
        event.target.closest('.timeslot-btn').classList.add('bg-blue-600', 'text-white', 'border-blue-600');
        
        // Enable next button
        document.getElementById('next_btn').disabled = false;
    }

    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.updateStepDisplay();
            
            if (this.currentStep === 4) {
                this.updateConfirmationSummary();
            }
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStepDisplay();
        }
    }

    updateStepDisplay() {
        // Hide all steps
        for (let i = 1; i <= this.totalSteps; i++) {
            const step = document.getElementById(`step_${i}`);
            if (step) {
                step.classList.add('hidden');
            }
        }

        // Show current step
        const currentStepEl = document.getElementById(`step_${this.currentStep}`);
        if (currentStepEl) {
            currentStepEl.classList.remove('hidden');
        }

        // Update navigation buttons
        const backBtn = document.getElementById('back_btn');
        const nextBtn = document.getElementById('next_btn');

        if (backBtn) {
            if (this.currentStep === 1) {
                backBtn.classList.add('hidden');
            } else {
                backBtn.classList.remove('hidden');
            }
        }

        if (nextBtn) {
            if (this.currentStep === this.totalSteps) {
                nextBtn.textContent = 'Complete Demo';
                nextBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Complete Demo';
            } else {
                nextBtn.innerHTML = 'Next<i class="fas fa-arrow-right ml-2"></i>';
            }
        }

        this.updateProgressStepper();
    }

    updateProgressStepper() {
        // Update progress stepper visual state (if needed)
        // This would update the step indicator at the top
    }

    updateConfirmationSummary() {
        if (!this.selectedData.doctor || !this.selectedData.service) return;

        // Update doctor info
        const doctorName = document.getElementById('confirm_doctor_name');
        const doctorSpecialization = document.getElementById('confirm_doctor_specialization');

        if (doctorName) doctorName.textContent = `Dr. ${this.selectedData.doctor.user?.name || this.selectedData.doctor.name}`;
        if (doctorSpecialization) doctorSpecialization.textContent = this.selectedData.doctor.specialization || 'General Practice';

        // Update service info
        const serviceName = document.getElementById('confirm_service_name');
        const serviceDuration = document.getElementById('confirm_service_duration');

        if (serviceName) serviceName.textContent = this.selectedData.service.name;
        if (serviceDuration) serviceDuration.textContent = `${this.selectedData.service.duration || 30} minutes`;

        // Update date and time
        const confirmDate = document.getElementById('confirm_date');
        const confirmTime = document.getElementById('confirm_time');
        const confirmFee = document.getElementById('confirm_fee');

        if (this.selectedData.date && confirmDate) {
            const date = new Date(this.selectedData.date);
            confirmDate.textContent = date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        if (this.selectedData.time && confirmTime) {
            confirmTime.textContent = this.selectedData.time.formatted_start || this.selectedData.time.start_time;
        }

        if (confirmFee) {
            confirmFee.textContent = `$${this.selectedData.service.price || '50'}`;
        }
    }

    showDoctorsLoading(show) {
        const loading = document.getElementById('doctors_loading');
        const grid = document.getElementById('doctors_grid');
        
        if (show) {
            loading?.classList.remove('hidden');
            grid?.classList.add('hidden');
        } else {
            loading?.classList.add('hidden');
            grid?.classList.remove('hidden');
        }
    }

    showDoctorsError() {
        const container = document.getElementById('doctors_grid');
        if (container) {
            container.innerHTML = `
                <div class="col-span-full bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-xl p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                    <p class="text-red-700 dark:text-red-300">Error loading doctors. Please try again.</p>
                </div>
            `;
        }
    }
}

// Initialize the booking demo when the page loads
document.addEventListener('DOMContentLoaded', function() {
    new AppointmentBookingDemo();
});
</script>
@endsection
