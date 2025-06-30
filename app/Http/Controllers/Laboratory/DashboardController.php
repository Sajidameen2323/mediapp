<?php

namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the laboratory dashboard.
     */
    public function index()
    {
        Gate::authorize('laboratory-staff-access');

        $user = auth()->user();
        $laboratory = $user->laboratory()->first();

        if (!$laboratory) {
            return redirect()->route('dashboard')
                ->with('error', 'Laboratory profile not found.');
        }

        // Get dashboard statistics
        $stats = $this->getDashboardStats($laboratory);

        // Get recent appointments
        $recentAppointments = $laboratory->labAppointments()
            ->with(['patient', 'labTestRequest'])
            ->where('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Get pending appointments requiring attention
        $pendingAppointments = $laboratory->labAppointments()
            ->with(['patient', 'labTestRequest'])
            ->where('status', 'pending')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(10)
            ->get();

        return view('dashboard.laboratory.index', compact(
            'laboratory',
            'stats',
            'recentAppointments',
            'pendingAppointments'
        ));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats(Laboratory $laboratory): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today' => [
                'total' => $laboratory->labAppointments()
                    ->where('appointment_date', $today)
                    ->count(),
                'pending' => $laboratory->labAppointments()
                    ->where('appointment_date', $today)
                    ->where('status', 'pending')
                    ->count(),
                'confirmed' => $laboratory->labAppointments()
                    ->where('appointment_date', $today)
                    ->where('status', 'confirmed')
                    ->count(),
                'completed' => $laboratory->labAppointments()
                    ->where('appointment_date', $today)
                    ->where('status', 'completed')
                    ->count(),
            ],
            'this_week' => [
                'total' => $laboratory->labAppointments()
                    ->where('appointment_date', '>=', $thisWeek)
                    ->count(),
                'revenue' => $laboratory->labAppointments()
                    ->where('appointment_date', '>=', $thisWeek)
                    ->where('status', 'completed')
                    ->sum('final_cost'),
            ],
            'this_month' => [
                'total' => $laboratory->labAppointments()
                    ->where('appointment_date', '>=', $thisMonth)
                    ->count(),
                'revenue' => $laboratory->labAppointments()
                    ->where('appointment_date', '>=', $thisMonth)
                    ->where('status', 'completed')
                    ->sum('final_cost'),
            ],
            'pending_results' => $laboratory->labAppointments()
                ->where('status', 'confirmed')
                ->where('appointment_date', '<=', $today)
                ->count(),
        ];
    }
}
