# Medi App Feature Implementation Tracker

This document tracks the implementation phases and details for the appointment booking and related features in the Medi App. Each phase outlines the scope, implementation details, and references to existing models and controllers where applicable. The goal is to leverage existing code, keep features standard and easy to implement, and ensure maintainability.

---

## Phase 1: Appointment Configuration (Admin Only) ✅ Completed
- **Access:** Admin panel > Appointment Settings
- **Configurable Options:**
  - Buffer before first bookable date
  - Number of days to show in booking calendar
  - Tax for online booking
  - Hours required for canceling/rescheduling
  - Max appointments per day
  - Max pending appointments
  - Allow delayed payment
  - Auto-approve appointments
  - Set company-wide holidays
  - Set off days
  - Block specific time slots for a date
  - Other appointment-related configs
- **Implementation:**
  - Use a new `AppointmentConfig` model/table for settings
  - Use existing admin controllers for CRUD
  - Add UI in admin panel (refer to THEME.md, use Tailwind, dark mode, responsive)
  - Holiday/off-day/block slot: new models/tables as needed

---

## Phase 2: Laboratory & Pharmacy Management (Admin) ✅ Completed
- **Features:**
  - Admin manages laboratories and pharmacies (register, update, remove).
  - Admin can register partner organizations (labs, pharmacies) and provide login credentials.
- **Implementation:**
  - Use similar registration flow as doctor registration (reuse logic/controllers).
  - Create `Laboratory` and `Pharmacy` models/tables if not present.
  - Admin dashboard for managing partner organizations and credentials.
  - Send login credentials to partner organizations upon registration.

---

## Phase 3: Doctor Registration & Dashboard Enhancements
- **Features:**
  - Doctor registration (already implemented, dont alter it unless absolutely necessary for other dependent features)
  - Doctor dashboard for managing schedule, breaks, holidays, and reports
- **Implementation:**
  - Reuse/extend doctor registration logic if absolutely necessary
  - Add/extend dashboard features for schedule, breaks, holidays, and report form

---

## Phase 4: Doctor Appointment Section
- **Doctor Actions:**
  - Approve/cancel appointments
  - Set recurring lunch breaks
  - Block slots
  - Set doctor-specific holidays
- **Implementation:**
  - Extend `DoctorSchedule` model for breaks/holidays or seprate
  - Use/extend doctor controllers
  - UI for doctors to manage their schedule

---

## Phase 5: Doctor Report Generation
- **Features:**
  - Doctor can fill out a form to generate and submit a medical report for a patient.
- **Implementation:**
  - Add a form in the doctor dashboard for report creation (use Tailwind, dark mode, responsive, FontAwesome icons).
  - Store reports in a `MedicalReport` model/table.
  - Patient can view reports in their dashboard.

---

## Phase 6: Lab Test Booking
- **Features:**
  - Patient books lab test appointment
  - Select laboratory and slot (same slot logic as doctor)
  - Admin can add laboratories and assign lab staff
- **Implementation:**
  - New `Laboratory` and `LabStaff` models
  - Extend appointment logic for labs

---

## Phase 7: Appointment Booking (Patient)
- **Booking Flow:**
  1. Patient selects service and doctor
  2. Selects date from calendar
  3. Time slots shown dynamically adhere to Appoinment configurations (filter unavailable slots: appointments, breaks, blocked slots)
  4. Slot start/end based on doctor availability and service duration
  5. Efficient slot mapping (no overlap, correct duration)
  6. Review & payment (or delay payment)
  7. Booking notification via email
- **Implementation:**
  - Use `Doctor`, `Service`, `DoctorSchedule`, and `Appointment` models
  - Slot logic: filter by existing appointments, breaks, blocks, holidays
  - Payment: integrate Stripe via Laravel Cashier (see below)
  - Use Laravel notifications for emails

---

## Phase 8: Appointment Management (Admin)
- **Admin Actions:**
  - View, approve, cancel, reschedule, block slots, manage holidays
- **Implementation:**
  - Use/extend admin controllers
  - UI for appointment management

---

## Phase 9: Patient Appointment Management & Payment
- **Features:**
  - View, cancel, reschedule appointments
  - Make/delay payments
- **Implementation:**
  - Patient dashboard UI
  - Integrate with payment (Stripe)

---

## Phase 10: Lab Staff Releasing Lab Results
- **Features:**
  - Lab staff upload/release results to patient
- **Implementation:**
  - Lab staff dashboard
  - File upload/notification to patient

---

## Phase 11: Medication Management (Patient)
- **Features:**
  - Patient views and manages medications
- **Implementation:**
  - Medication model/table
  - Patient dashboard UI

---

## Phase 12: Patient Sharing Health Profile
- **Features:**
  - Patient allows doctor/lab staff to view health profile/medications
- **Implementation:**
  - Permission system (use existing or extend)

---

## Phase 13: Doctor Prescription & Pharmacy Integration
- **Features:**
  - Doctor adds prescription for patient
  - Patient views prescription, orders from pharmacy
  - Admin adds partner pharmacies
  - Doctor uploads medical reports
- **Implementation:**
  - Prescription and MedicalReport models
  - Pharmacy model/table
  - Patient dashboard for viewing

---

## Phase 14: Admin Reports, Analytics, User Management
- **Features:**
  - System analytics, reports
  - Admin can block users
- **Implementation:**
  - Reporting UI
  - User management (block/unblock)

---

## Phase 15: Email Notifications
- **Features:**
  - Appointment and other notifications via email
- **Implementation:**
  - Use Laravel Notification system

---

## Phase 16: AI Chatbot (Gemini)
- **Features:**
  - Chatbot for system features, company info
- **Implementation:**
  - Integrate Gemini API
  - UI for chatbot

---

## Payment Integration (Stripe via Laravel Cashier)
- **Guide:**
  1. Install Cashier: `composer require laravel/cashier`
  2. Publish config: `php artisan vendor:publish --provider="Laravel\Cashier\CashierServiceProvider"`
  3. Set Stripe keys in `.env`
  4. Use Cashier methods for payment flows
  5. Test with Stripe test keys

---

## General Implementation Notes
- Use existing models/controllers where possible
- Follow THEME.md for UI
- Use Tailwind CSS, dark mode and light mode, responsive design
- Use FontAwesome for icons
- Break down code into components, use layouts
- Separate JS logic if needed
- Keep features simple and standard

---

## References
- Models: `Doctor`, `Service`, `DoctorSchedule`, `Appointment`, `User`, `HealthProfile`, etc.
- Controllers: Admin, Doctor, Patient, Lab, Pharmacy
- [THEME.md](../THEME.md) for design
- [Laravel Cashier Docs](https://laravel.com/docs/11.x/billing)

---

*Update this file as each phase is implemented or requirements change.*
