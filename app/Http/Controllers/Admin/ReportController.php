<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Payment;
use App\Models\PharmacyOrder;
use App\Models\Prescription;
use App\Models\MedicalReport;
use App\Models\LabTestRequest;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display admin reports dashboard.
     */
    public function index(Request $request)
    {
        $this->authorize('admin-access');

        // Get date range from request, default to last 30 days
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ensure dates are Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Overall Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalDoctors = User::byType('doctor')->count();
        $totalPatients = User::byType('patient')->count();
        $totalPharmacies = User::byType('pharmacy')->count();
        $totalLaboratories = User::byType('laboratory')->count();

        // Appointment Analytics
        $appointmentStats = $this->getAppointmentAnalytics($startDate, $endDate);
        
        // Revenue Analytics
        $revenueStats = $this->getRevenueAnalytics($startDate, $endDate);
        
        // Pharmacy Analytics
        $pharmacyStats = $this->getPharmacyAnalytics($startDate, $endDate);
        
        // Medical Reports Analytics
        $medicalReportStats = $this->getMedicalReportAnalytics($startDate, $endDate);
        
        // User Growth Analytics
        $userGrowthStats = $this->getUserGrowthAnalytics($startDate, $endDate);

        // Top Performing Data
        $topDoctors = $this->getTopDoctors($startDate, $endDate);
        $topServices = $this->getTopServices($startDate, $endDate);
        $topPharmacies = $this->getTopPharmacies($startDate, $endDate);

        // Recent Activity
        $recentAppointments = Appointment::with(['doctor.user', 'patient', 'service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers', 'activeUsers', 'totalDoctors', 'totalPatients', 
            'totalPharmacies', 'totalLaboratories',
            'appointmentStats', 'revenueStats', 'pharmacyStats', 
            'medicalReportStats', 'userGrowthStats',
            'topDoctors', 'topServices', 'topPharmacies',
            'recentAppointments', 'startDate', 'endDate'
        ));
    }

    /**
     * Get appointment analytics data.
     */
    private function getAppointmentAnalytics($startDate, $endDate)
    {
        $totalAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')->count();
        $cancelledAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'cancelled')->count();
        $pendingAppointments = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')->count();

        // Daily appointment trend
        $dailyTrend = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'count' => $item->count
                ];
            });

        // Appointment status distribution
        $statusDistribution = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return [
            'total' => $totalAppointments,
            'completed' => $completedAppointments,
            'cancelled' => $cancelledAppointments,
            'pending' => $pendingAppointments,
            'completion_rate' => $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 2) : 0,
            'cancellation_rate' => $totalAppointments > 0 ? round(($cancelledAppointments / $totalAppointments) * 100, 2) : 0,
            'daily_trend' => $dailyTrend,
            'status_distribution' => $statusDistribution
        ];
    }

    /**
     * Get revenue analytics data.
     */
    private function getRevenueAnalytics($startDate, $endDate)
    {
        $totalRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('amount');

        $pendingPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('amount');

        // Revenue by payment method
        $paymentMethods = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->payment_method => $item->total];
            });

        // Daily revenue trend
        $dailyRevenue = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'total' => $item->total
                ];
            });

        return [
            'total' => $totalRevenue,
            'pending' => $pendingPayments,
            'payment_methods' => $paymentMethods,
            'daily_trend' => $dailyRevenue
        ];
    }

    /**
     * Get pharmacy analytics data.
     */
    private function getPharmacyAnalytics($startDate, $endDate)
    {
        $totalOrders = PharmacyOrder::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedOrders = PharmacyOrder::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'dispensed')->count();
        $totalOrderValue = PharmacyOrder::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Order status distribution
        $orderStatus = PharmacyOrder::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'total_value' => $totalOrderValue,
            'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0,
            'order_status' => $orderStatus
        ];
    }

    /**
     * Get medical report analytics data.
     */
    private function getMedicalReportAnalytics($startDate, $endDate)
    {
        $totalReports = MedicalReport::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalPrescriptions = Prescription::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalLabTests = LabTestRequest::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            'total_reports' => $totalReports,
            'total_prescriptions' => $totalPrescriptions,
            'total_lab_tests' => $totalLabTests
        ];
    }

    /**
     * Get user growth analytics data.
     */
    private function getUserGrowthAnalytics($startDate, $endDate)
    {
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // User type distribution for new users
        $userTypes = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('user_type, COUNT(*) as count')
            ->groupBy('user_type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->user_type => $item->count];
            });

        return [
            'new_users' => $newUsers,
            'user_types' => $userTypes
        ];
    }

    /**
     * Get top performing doctors.
     */
    private function getTopDoctors($startDate, $endDate)
    {
        return Doctor::select('doctors.*')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->withCount(['appointments as total_appointments' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withCount(['appointments as completed_appointments' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', 'completed');
            }])
            ->with('user')
            ->orderBy('total_appointments', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get top performing services.
     */
    private function getTopServices($startDate, $endDate)
    {
        return Service::withCount(['appointments as total_appointments' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('total_appointments', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get top performing pharmacies.
     */
    private function getTopPharmacies($startDate, $endDate)
    {
        return Pharmacy::select('pharmacies.*')
            ->join('users', 'pharmacies.user_id', '=', 'users.id')
            ->withCount(['orders as total_orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->with('user')
            ->orderBy('total_orders', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Export reports to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('admin-access');

        $type = $request->input('type', 'appointments');
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(30)))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()))->endOfDay();

        switch ($type) {
            case 'appointments':
                return $this->exportAppointments($startDate, $endDate);
            case 'revenue':
                return $this->exportRevenue($startDate, $endDate);
            case 'pharmacy':
                return $this->exportPharmacyOrders($startDate, $endDate);
            case 'users':
                return $this->exportUsers($startDate, $endDate);
            default:
                return redirect()->back()->with('error', 'Invalid export type.');
        }
    }

    /**
     * Export appointments data.
     */
    private function exportAppointments($startDate, $endDate)
    {
        $appointments = Appointment::with(['doctor.user', 'patient', 'service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'appointments_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($appointments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Appointment Number', 'Doctor', 'Patient', 'Service', 'Date', 'Time',
                'Status', 'Payment Status', 'Total Amount', 'Created At'
            ]);

            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->appointment_number,
                    $appointment->doctor->user->name ?? 'N/A',
                    $appointment->patient->name ?? 'N/A',
                    $appointment->service->name ?? 'N/A',
                    $appointment->appointment_date,
                    $appointment->start_time . ' - ' . $appointment->end_time,
                    ucfirst($appointment->status),
                    ucfirst($appointment->payment_status),
                    '$' . number_format($appointment->total_amount, 2),
                    $appointment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export revenue data.
     */
    private function exportRevenue($startDate, $endDate)
    {
        $payments = Payment::with(['user', 'appointment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'revenue_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Transaction ID', 'User', 'Appointment Number', 'Amount', 'Payment Method',
                'Status', 'Reference', 'Created At'
            ]);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->transaction_id,
                    $payment->user->name ?? 'N/A',
                    $payment->appointment->appointment_number ?? 'N/A',
                    '$' . number_format($payment->amount, 2),
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    ucfirst($payment->status),
                    $payment->reference,
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export pharmacy orders data.
     */
    private function exportPharmacyOrders($startDate, $endDate)
    {
        $orders = PharmacyOrder::with(['patient', 'pharmacy.user', 'prescription'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'pharmacy_orders_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order Number', 'Patient', 'Pharmacy', 'Status', 'Payment Status',
                'Delivery Method', 'Total Amount', 'Created At'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->patient->name ?? 'N/A',
                    $order->pharmacy->user->name ?? 'N/A',
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    ucfirst(str_replace('_', ' ', $order->delivery_method)),
                    '$' . number_format($order->total_amount, 2),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export users data.
     */
    private function exportUsers($startDate, $endDate)
    {
        $users = User::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'users_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Name', 'Email', 'User Type', 'Phone', 'Gender', 'Status', 'Created At'
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    ucfirst($user->user_type),
                    $user->phone_number,
                    $user->gender ? ucfirst($user->gender) : 'N/A',
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get detailed analytics for a specific metric.
     */
    public function analytics(Request $request)
    {
        $this->authorize('admin-access');

        $metric = $request->input('metric', 'appointments');
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(30)))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()))->endOfDay();

        switch ($metric) {
            case 'appointments':
                $data = $this->getDetailedAppointmentAnalytics($startDate, $endDate);
                break;
            case 'revenue':
                $data = $this->getDetailedRevenueAnalytics($startDate, $endDate);
                break;
            case 'users':
                $data = $this->getDetailedUserAnalytics($startDate, $endDate);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid metric type.');
        }

        return view('admin.reports.analytics', compact('data', 'metric', 'startDate', 'endDate'));
    }

    /**
     * User management page.
     */
    public function userManagement(Request $request)
    {
        $this->authorize('admin-access');

        $query = User::query();

        // Filter by user type
        if ($request->filled('user_type') && $request->user_type !== 'all') {
            $query->where('user_type', $request->user_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('created_at')->paginate(15);

        return view('admin.reports.user-management', compact('users'));
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleUserStatus(User $user)
    {
        $this->authorize('admin-access');

        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$user->name} has been {$status} successfully.");
    }

    /**
     * Get detailed appointment analytics.
     */
    private function getDetailedAppointmentAnalytics($startDate, $endDate)
    {
        // Hourly distribution
        $hourlyDistribution = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(start_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->hour . ':00' => $item->count];
            });

        // Monthly trend (if date range is large enough)
        $monthlyTrend = Appointment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            });

        // Service popularity
        $servicePopularity = Service::withCount(['appointments as total_appointments' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderBy('total_appointments', 'desc')
            ->get();

        return [
            'hourly_distribution' => $hourlyDistribution,
            'monthly_trend' => $monthlyTrend,
            'service_popularity' => $servicePopularity
        ];
    }

    /**
     * Get detailed revenue analytics.
     */
    private function getDetailedRevenueAnalytics($startDate, $endDate)
    {
        // Revenue by service
        $revenueByService = Service::select('services.name')
            ->join('appointments', 'services.id', '=', 'appointments.service_id')
            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->selectRaw('services.name, SUM(payments.amount) as total_revenue')
            ->groupBy('services.id', 'services.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Revenue by doctor
        $revenueByDoctor = User::select('users.name')
            ->join('doctors', 'users.id', '=', 'doctors.user_id')
            ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
            ->join('payments', 'appointments.id', '=', 'payments.appointment_id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.status', 'completed')
            ->selectRaw('users.name, SUM(payments.amount) as total_revenue')
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'revenue_by_service' => $revenueByService,
            'revenue_by_doctor' => $revenueByDoctor
        ];
    }

    /**
     * Get detailed user analytics.
     */
    private function getDetailedUserAnalytics($startDate, $endDate)
    {
        // User registration trend
        $registrationTrend = User::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, user_type, COUNT(*) as count')
            ->groupBy('date', 'user_type')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($users, $date) {
                $types = $users->mapWithKeys(function ($user) {
                    return [$user->user_type => $user->count];
                });
                return [
                    'date' => Carbon::parse($date)->format('M d'),
                    'types' => $types
                ];
            });

        return [
            'registration_trend' => $registrationTrend
        ];
    }
}
