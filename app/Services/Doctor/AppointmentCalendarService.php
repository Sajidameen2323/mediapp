<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentCalendarService
{
    /**
     * Get appointments for calendar view
     */
    public function getCalendarAppointments($doctor, array $filters = [])
    {
        $query = $doctor->appointments()->with(['patient', 'service']);
        
        // Apply filters
        $this->applyFilters($query, $filters);
        
        return $query->orderBy('appointment_date')
                    ->orderBy('start_time')
                    ->get();
    }
    
    /**
     * Get appointments for list view with pagination
     */
    public function getListAppointments($doctor, array $filters = [], int $perPage = 10)
    {
        $query = $doctor->appointments()->with(['patient', 'service']);
        
        // Apply filters
        $this->applyFilters($query, $filters);
        
        return $query->orderBy('appointment_date')
                    ->orderBy('start_time')
                    ->paginate($perPage);
    }
    
    /**
     * Apply filters to the query
     */
    private function applyFilters($query, array $filters)
    {
        // Filter by status
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->byStatus($filters['status']);
        }
        
        // Filter by date range
        if (isset($filters['year']) && isset($filters['month'])) {
            $startDate = Carbon::create($filters['year'], $filters['month'], 1)->startOfMonth();
            $endDate = Carbon::create($filters['year'], $filters['month'], 1)->endOfMonth();
            $query->byDateRange($startDate->toDateString(), $endDate->toDateString());
        } elseif (isset($filters['date'])) {
            $this->applyDateFilter($query, $filters['date']);
        }
        
        // Filter by search term
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('appointment_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('patient', function($patientQuery) use ($search) {
                      $patientQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('email', 'LIKE', "%{$search}%")
                                   ->orWhere('phone', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('service', function($serviceQuery) use ($search) {
                      $serviceQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
    }
    
    /**
     * Apply date filter based on preset
     */
    private function applyDateFilter($query, string $dateFilter)
    {
        switch ($dateFilter) {
            case 'today':
                $query->today();
                break;
            case 'upcoming':
                $query->upcoming();
                break;
            case 'week':
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();
                $query->byDateRange($startOfWeek->toDateString(), $endOfWeek->toDateString());
                break;
            case 'month':
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $query->byDateRange($startOfMonth->toDateString(), $endOfMonth->toDateString());
                break;
        }
    }
    
    /**
     * Get appointment statistics
     */
    public function getAppointmentStats($doctor)
    {
        return [
            'total' => $doctor->appointments()->count(),
            'today' => $doctor->appointments()->today()->count(),
            'upcoming' => $doctor->appointments()->upcoming()->count(),
            'pending' => $doctor->appointments()->byStatus('pending')->count(),
            'confirmed' => $doctor->appointments()->byStatus('confirmed')->count(),
        ];
    }
    
    /**
     * Format appointments for calendar JSON response
     */
    public function formatAppointmentsForCalendar($appointments)
    {
        return $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->start_time,
                'patient_name' => $appointment->patient->name,
                'service_name' => $appointment->service->name,
                'status' => $appointment->status,
            ];
        });
    }
}
