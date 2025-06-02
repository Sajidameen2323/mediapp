# Appointment Booking Feature: Criteria & Strategy

## Criteria for Dynamic Appointment Slot Preparation
1. **Already Booked Appointments**  
   Exclude time slots that are already reserved by other patients.

2. **Doctor’s Work Schedule**  
   Only show slots within the doctor’s available working hours (`DoctorSchedule` model).

3. **Blocked Time Slots**  
   Exclude any slots that are marked as blocked (`BlockedTimeSlot` model).

4. **Doctor Breaks**  
   Remove slots that overlap with the doctor’s scheduled breaks (`DoctorBreak` model).

5. **Doctor Holidays**  
   Exclude dates/times when the doctor is on holiday (`DoctorHoliday` model).

6. **General Holidays**  
   Exclude slots that fall on public or clinic-wide holidays (`Holiday` model).

7. **Appointment Configurations**  
   Respect appointment duration, buffer times, and other settings (`AppointmentConfig` model).

8. **Maximum Appointments per Slot**  
   Ensure the number of appointments per slot does not exceed the allowed maximum.

9. **Service Availability**  
   Only show slots for services the doctor is available for (`Service` and `Doctor` models).

10. **Time Zone Handling**  
   Ensure all times are displayed and booked in the correct time zone.

11. **Lead Time and Cut-off**  
   Enforce minimum lead time before booking and cut-off times for same-day appointments.

12. **Overlapping Appointments**  
   Prevent double-booking or overlapping appointments for the same doctor.

---

## Strategy to Build the Appointment Booking Feature

1. **Model Relationships & Data Preparation**
   - Define and use Eloquent relationships between Doctor, Appointment, DoctorSchedule, BlockedTimeSlot, DoctorBreak, DoctorHoliday, Holiday, Service, and AppointmentConfig.
   - Fetch all relevant data for the selected doctor and date.

2. **Slot Generation Logic**
   - Generate all possible slots within the doctor’s working hours for the selected date.
   - Apply appointment duration and buffer time from `AppointmentConfig`.

3. **Slot Filtering**
   - Remove slots that:
     - Are already booked (check existing appointments).
     - Overlap with blocked times, breaks, holidays, or doctor holidays.
     - Exceed the maximum allowed appointments per slot.
     - Are outside the doctor’s available services.
     - Do not meet lead time or cut-off requirements.
   - Ensure no overlapping appointments.

4. **Validation**
   - Use Laravel Request classes for validating booking requests (patient info, slot availability, etc.).

5. **User Interface**
   - Build the UI using Tailwind CSS, supporting both light and dark modes.
   - Use FontAwesome for icons.
   - Ensure the design is responsive and follows THEME.md.
   - Break down UI into reusable components and use layouting for consistency.

6. **Booking Process**
   - On slot selection, validate again on the backend before confirming the booking.
   - Save the appointment and update the slot’s status.

7. **Testing**
   - Write feature and unit tests to cover slot generation, filtering, and booking logic.

8. **Slot Duration by Service**
   - When generating slots, always use the duration defined by the selected service (e.g., 15 or 30 minutes).
   - If a doctor offers multiple services, the slot duration should match the service chosen by the patient for that booking.

9. **Doctor-Specific and Global Blocked Slots**
   - If a blocked slot is assigned to a specific doctor, only deny slots for that doctor.
   - If a blocked slot is global (doctor_id is null), deny slots for all doctors during that time.
   - Exclude any slot that overlaps with an active blocked slot, using the logic in BlockedTimeSlot::isTimeSlotBlocked.

10. **Efficient Slot Preparation**
   - Generate slots by incrementing time using the selected service’s duration and any buffer time.
   - For each slot, check all rules: booked, blocked, breaks, holidays, max appointments, and service availability.
   - Only add slots that pass all checks to the available list.

---
