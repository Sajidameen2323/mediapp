<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\AppointmentConfig;
use App\Models\DoctorSchedule;
use App\Models\DoctorBreak;
use App\Models\DoctorHoliday;
use App\Models\Holiday;
use App\Models\BlockedTimeSlot;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentSlotService
{
    /**
     * Generate available time slots for a doctor on a specific date.
     */
    public function getAvailableSlots(Doctor $doctor, string $date, int $serviceId): Collection
    {
        $config = AppointmentConfig::first();
        $service = $doctor->services()->find($serviceId);
        if (!$service) {
            error_log("Service ID {$serviceId} not found for doctor ID: {$doctor->id}");
            return collect();
        }
        if (!$config) {
            return collect();
        }

        $date = Carbon::parse($date);
        error_log("Getting available slots for doctor ID: {$doctor->id} on date: {$date->toDateString()}");
        // Check if the date is valid for appointments
        if (!$this->isDateAvailableForAppointments($date, $config)) {
            return collect();
        }

        // Check if doctor is available on this date
        if (!$this->isDoctorAvailableOnDate($doctor, $date)) {
            return collect();
        }

        // Get doctor's schedule for this day
        $schedule = $this->getDoctorScheduleForDay($doctor, $date);
        if (!$schedule) {
            return collect();
        }

        // Generate time slots based on schedule
        $slots = $this->generateTimeSlotsFromSchedule($schedule, $config,$service, $date);

        // Remove blocked slots
        $slots = $this->removeBlockedSlots($slots, $doctor, $date);

        // Remove doctor break times
        $slots = $this->removeDoctorBreaks($slots, $doctor, $date);

        // Remove already booked slots
        $slots = $this->removeBookedSlots($slots, $doctor, $date);

        return $slots;
    }

    /**
     * Check if a specific time slot is available for booking.
     */
    public function isSlotAvailable(Doctor $doctor, string $datetime, ?int $serviceId = null): bool
    {
        $datetime = Carbon::parse($datetime);
        $date = $datetime->toDateString();
        $time = $datetime->format('H:i:s');

        $availableSlots = $this->getAvailableSlots($doctor, $date, $serviceId);
        
        return $availableSlots->contains(function ($slot) use ($time) {
            return $slot['start_time'] === $time;
        });
    }

    /**
     * Get the next available appointment slot for a doctor.
     */
    public function getNextAvailableSlot(Doctor $doctor, ?int $serviceId = null): ?array
    {
        $config = AppointmentConfig::first();
        if (!$config) {
            return null;
        }

        $startDate = Carbon::now()->addDays($config->min_advance_booking_days);
        $endDate = Carbon::now()->addDays($config->max_advance_booking_days);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $slots = $this->getAvailableSlots($doctor, $date->toDateString(), $serviceId);
            
            if ($slots->isNotEmpty()) {
                $firstSlot = $slots->first();
                return [
                    'date' => $date->toDateString(),
                    'time' => $firstSlot['start_time'],
                    'datetime' => $date->format('Y-m-d') . ' ' . $firstSlot['start_time']
                ];
            }
        }

        return null;
    }

    /**
     * Get available slots for multiple days.
     */
    public function getAvailableSlotsForDateRange(Doctor $doctor, string $startDate, string $endDate, ?int $serviceId = null): Collection
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $availableSlots = collect();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $daySlots = $this->getAvailableSlots($doctor, $date->toDateString(), $serviceId);
            
            if ($daySlots->isNotEmpty()) {
                $availableSlots->put($date->toDateString(), $daySlots);
            }
        }

        return $availableSlots;
    }

    /**
     * Check if a date is available for appointments based on system configuration.
     */
    private function isDateAvailableForAppointments(Carbon $date, AppointmentConfig $config): bool
    {
        // Check if date is in the past
        if ($date->lt(Carbon::today())) {
            error_log("Date {$date->toDateString()} is in the past.");
            return false;
        }

        if (isset($config->min_booking_hours_ahead)) {
            $config->min_advance_booking_days = ceil($config->min_booking_hours_ahead / 24);
        } else {
            $config->min_advance_booking_days = 1; // Default to 1 day if not set
        }

        // Check minimum advance booking
        if ($date->lt(Carbon::today()->addDays($config->min_advance_booking_days))) {
            error_log("Date {$date->toDateString()} is before the minimum advance booking days.");
            return false;
        }

        // Check maximum advance booking
        if ($date->gt(Carbon::today()->addDays($config->max_booking_days_ahead))) {
            return false;
        }

        // Check if it's a system holiday
        if ($this->isSystemHoliday($date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if doctor is available on a specific date.
     */
    private function isDoctorAvailableOnDate(Doctor $doctor, Carbon $date): bool
    {
        // Check if doctor is active
        if (!$doctor->is_available) {
            return false;
        }

        // Check if doctor is on holiday
        $holiday = DoctorHoliday::where('doctor_id', $doctor->id)
            ->where('start_date', '<=', $date->toDateString())
            ->where('end_date', '>=', $date->toDateString())
            ->where('status', 'approved')
            ->first();

        return !$holiday;
    }

    /**
     * Get doctor's schedule for a specific day.
     */
    private function getDoctorScheduleForDay(Doctor $doctor, Carbon $date): ?DoctorSchedule
    {
        $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 6 = Saturday

        return DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();
    }

    /**
     * Generate time slots based on doctor's schedule.
     */
    private function generateTimeSlotsFromSchedule(DoctorSchedule $schedule, AppointmentConfig $config, Service $service ,Carbon $date): Collection
    {
        $slots = collect();
        $slotDuration = $service->duration_minutes; // in minutes
        error_log("Generating slots for doctor ID: {$schedule->doctor_id} on date: {$date->toDateString()} with slot duration: {$slotDuration} minutes");
        // Validate slot duration to prevent infinite loop
        if (!$slotDuration || $slotDuration <= 0) {
            error_log("Invalid slot duration: {$slotDuration}. Cannot generate slots.");
            return $slots;
        }
        
        $startTime = Carbon::parse($date->toDateString() . ' ' . Carbon::parse($schedule->start_time)->format('H:i:s'));
        $endTime = Carbon::parse($date->toDateString() . ' ' . Carbon::parse($schedule->end_time)->format('H:i:s'));

        // Validate that start time is before end time
        if ($startTime->gte($endTime)) {
            error_log("Invalid schedule: start time {$startTime->format('H:i:s')} is not before end time {$endTime->format('H:i:s')}");
            return $slots;
        }

        // Add safety counter to prevent infinite loops
        $maxSlots = 100; // Reasonable maximum number of slots per day
        $slotCount = 0;
    
        while ($startTime->lt($endTime) && $slotCount < $maxSlots) {
            $slotEndTime = $startTime->copy()->addMinutes($slotDuration);
            
            if ($slotEndTime->lte($endTime)) {
                $slots->push([
                    'start_time' => $startTime->format('H:i:s'),
                    'end_time' => $slotEndTime->format('H:i:s'),
                    'formatted_start' => $startTime->format('g:i A'),
                    'formatted_end' => $slotEndTime->format('g:i A'),
                    'duration' => $slotDuration
                ]);
            }
            
            $startTime->addMinutes($slotDuration);
            $slotCount++;
        }

        if ($slotCount >= $maxSlots) {
            error_log("Reached maximum slot limit ({$maxSlots}) for doctor ID: {$schedule->doctor_id} on date: {$date->toDateString()}");
        }

        error_log("Generated " . $slots->count() . " slots for doctor ID: {$schedule->doctor_id} on date: {$date->toDateString()}");

        return $slots;
    }

    /**
     * Remove blocked time slots.
     */
    private function removeBlockedSlots(Collection $slots, Doctor $doctor, Carbon $date): Collection
    {
        $blockedSlots = BlockedTimeSlot::where('doctor_id', $doctor->id)
            ->where('date', $date->toDateString())
            ->get();

        return $slots->filter(function ($slot) use ($blockedSlots) {
            foreach ($blockedSlots as $blocked) {
                $slotStart = $slot['start_time'];
                $slotEnd = $slot['end_time'];
                
                // Check if slot overlaps with blocked time
                if ($this->timesOverlap($slotStart, $slotEnd, $blocked->start_time, $blocked->end_time)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Remove doctor break times.
     */
    private function removeDoctorBreaks(Collection $slots, Doctor $doctor, Carbon $date): Collection
    {
        $dayOfWeek = $date->dayOfWeek;
        
        $breaks = DoctorBreak::where('doctor_id', $doctor->id)
            ->where(function ($query) use ($dayOfWeek, $date) {
            $query->where(function ($q) use ($dayOfWeek) {
                // Regular recurring breaks
                $q->where('type', 'recurring')
                  ->where('day_of_week', $dayOfWeek);
            })->orWhere(function ($q) use ($date) {
                // One-time breaks - check if break falls on the given date
                $q->where('type', 'one_time')
                  ->whereDate('start_time', $date->toDateString());
            });
            })
            ->get();

        return $slots->filter(function ($slot) use ($breaks) {
            foreach ($breaks as $break) {
                $slotStart = $slot['start_time'];
                $slotEnd = $slot['end_time'];
                
                // Check if slot overlaps with break time
                if ($this->timesOverlap($slotStart, $slotEnd, $break->start_time, $break->end_time)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Remove already booked slots.
     */
    private function removeBookedSlots(Collection $slots, Doctor $doctor, Carbon $date): Collection
    {
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date->toDateString())
            ->whereIn('status', ['confirmed', 'pending'])
            ->get();

        return $slots->filter(function ($slot) use ($bookedAppointments) {
            foreach ($bookedAppointments as $appointment) {
                $slotStart = $slot['start_time'];
                $slotEnd = $slot['end_time'];
                
                // Check if slot overlaps with booked appointment
                if ($this->timesOverlap($slotStart, $slotEnd, $appointment->start_time, $appointment->end_time)) {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Check if two time ranges overlap.
     */
    private function timesOverlap(string $start1, string $end1, string $start2, string $end2): bool
    {
        $start1 = Carbon::parse($start1);
        $end1 = Carbon::parse($end1);
        $start2 = Carbon::parse($start2);
        $end2 = Carbon::parse($end2);

        return $start1->lt($end2) && $start2->lt($end1);
    }

    /**
     * Check if a date is a system holiday.
     */
    private function isSystemHoliday(Carbon $date): bool
    {
        return Holiday::where('date', $date->toDateString())->exists();
    }

    /**
     * Get appointment statistics for a doctor.
     */
    public function getDoctorAppointmentStats(Doctor $doctor, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = $doctor->appointments();

        if ($startDate && $endDate) {
            $query->whereBetween('appointment_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('appointment_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('appointment_date', '<=', $endDate);
        }

        $appointments = $query->get();

        return [
            'total' => $appointments->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'no_show' => $appointments->where('status', 'no_show')->count(),
            'today' => $appointments->where('appointment_date', Carbon::today()->toDateString())->count(),
            'this_week' => $appointments->whereBetween('appointment_date', [
                Carbon::now()->startOfWeek()->toDateString(),
                Carbon::now()->endOfWeek()->toDateString()
            ])->count(),
            'this_month' => $appointments->whereBetween('appointment_date', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString()
            ])->count(),
        ];
    }

    /**
     * Check appointment slot conflicts for a doctor.
     */
    public function checkSlotConflicts(Doctor $doctor, string $date, string $startTime, string $endTime, ?int $excludeAppointmentId = null): Collection
    {
        $conflicts = collect();

        // Check existing appointments
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->when($excludeAppointmentId, function ($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->get();

        foreach ($appointments as $appointment) {
            if ($this->timesOverlap($startTime, $endTime, $appointment->start_time, $appointment->end_time)) {
                $conflicts->push([
                    'type' => 'appointment',
                    'id' => $appointment->id,
                    'title' => 'Existing appointment with ' . $appointment->patient->name,
                    'start_time' => $appointment->start_time,
                    'end_time' => $appointment->end_time
                ]);
            }
        }

        // Check doctor breaks
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $breaks = DoctorBreak::where('doctor_id', $doctor->id)
            ->where(function ($query) use ($dayOfWeek, $date) {
                $query->where(function ($q) use ($dayOfWeek) {
                    $q->where('type', 'recurring')->where('day_of_week', $dayOfWeek);
                })->orWhere(function ($q) use ($date) {
                    $q->where('type', 'one_time')->where('date', $date);
                });
            })
            ->get();

        foreach ($breaks as $break) {
            if ($this->timesOverlap($startTime, $endTime, $break->start_time, $break->end_time)) {
                $conflicts->push([
                    'type' => 'break',
                    'id' => $break->id,
                    'title' => 'Doctor break: ' . ($break->title ?: 'Break time'),
                    'start_time' => $break->start_time,
                    'end_time' => $break->end_time
                ]);
            }
        }

        // Check blocked slots
        $blockedSlots = BlockedTimeSlot::where('doctor_id', $doctor->id)
            ->where('date', $date)
            ->get();

        foreach ($blockedSlots as $slot) {
            if ($this->timesOverlap($startTime, $endTime, $slot->start_time, $slot->end_time)) {
                $conflicts->push([
                    'type' => 'blocked',
                    'id' => $slot->id,
                    'title' => 'Blocked time: ' . ($slot->reason ?: 'Unavailable'),
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time
                ]);
            }
        }

        return $conflicts;
    }
}
