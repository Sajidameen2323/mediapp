<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorHoliday;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of doctor holiday requests.
     */
    public function index(Request $request)
    {
        $this->authorize('admin-access');

        $query = DoctorHoliday::with(['doctor.user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by doctor
        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->where('start_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->where('end_date', '<=', $request->to_date);
        }

        $holidays = $query->paginate(15);
        $doctors = Doctor::with('user')->orderBy('id')->get();

        // Statistics for dashboard
        $stats = [
            'total' => DoctorHoliday::count(),
            'pending' => DoctorHoliday::where('status', 'pending')->count(),
            'approved' => DoctorHoliday::where('status', 'approved')->count(),
            'rejected' => DoctorHoliday::where('status', 'rejected')->count(),
        ];

        return view('admin.holidays.index', compact('holidays', 'doctors', 'stats'));
    }

    /**
     * Display the specified holiday request.
     */
    public function show(DoctorHoliday $holiday)
    {
        $this->authorize('admin-access');

        $holiday->load(['doctor.user']);

        return view('admin.holidays.show', compact('holiday'));
    }

    /**
     * Approve a holiday request.
     */
    public function approve(Request $request, DoctorHoliday $holiday)
    {
        $this->authorize('admin-access');

        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        DB::transaction(function () use ($holiday, $request) {
            $holiday->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => auth()->id()
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Holiday request approved successfully.',
                'holiday' => [
                    'id' => $holiday->id,
                    'status' => $holiday->status,
                    'admin_notes' => $holiday->admin_notes,
                ]
            ]);
        }

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday request approved successfully.');
    }

    /**
     * Reject a holiday request.
     */
    public function reject(Request $request, DoctorHoliday $holiday)
    {
        $this->authorize('admin-access');

        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        DB::transaction(function () use ($holiday, $request) {
            $holiday->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'rejected_at' => now(),
                'rejected_by' => auth()->id()
            ]);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Holiday request rejected.',
                'holiday' => [
                    'id' => $holiday->id,
                    'status' => $holiday->status,
                    'admin_notes' => $holiday->admin_notes,
                ]
            ]);
        }

        return redirect()->route('admin.holidays.index')
            ->with('success', 'Holiday request rejected.');
    }

    /**
     * Bulk approve multiple holiday requests.
     */
    public function bulkApprove(Request $request)
    {
        $this->authorize('admin-access');

        $request->validate([
            'holiday_ids' => 'required|array',
            'holiday_ids.*' => 'exists:doctor_holidays,id',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $updated = DB::transaction(function () use ($request) {
            return DoctorHoliday::whereIn('id', $request->holiday_ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'admin_notes' => $request->admin_notes,
                    'approved_at' => now(),
                    'approved_by' => auth()->id()
                ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Successfully approved {$updated} holiday request(s)."
        ]);
    }

    /**
     * Bulk reject multiple holiday requests.
     */
    public function bulkReject(Request $request)
    {
        $this->authorize('admin-access');

        $request->validate([
            'holiday_ids' => 'required|array',
            'holiday_ids.*' => 'exists:doctor_holidays,id',
            'admin_notes' => 'required|string|max:1000'
        ]);

        $updated = DB::transaction(function () use ($request) {
            return DoctorHoliday::whereIn('id', $request->holiday_ids)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->admin_notes,
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id()
                ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Successfully rejected {$updated} holiday request(s)."
        ]);
    }

    /**
     * Get the CSS class for status badge.
     */
    private function getStatusBadgeClass(string $status): string
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }
}
