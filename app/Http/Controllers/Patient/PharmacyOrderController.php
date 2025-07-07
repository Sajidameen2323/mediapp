<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\PharmacyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PharmacyOrderController extends Controller
{
    /**
     * Display a listing of the patient's pharmacy orders.
     */
    public function index(Request $request)
    {
        Gate::authorize('patient-access');

        $query = PharmacyOrder::with(['pharmacy.user', 'prescription', 'items.prescriptionMedication.medication'])
            ->where('patient_id', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // By default, exclude cancelled orders
            $query->where('status', '!=', 'cancelled');
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or pharmacy name
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('pharmacy', function($pharmacyQuery) use ($search) {
                          $pharmacyQuery->where('pharmacy_name', 'like', "%{$search}%");
                      });
                });
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Append query parameters to pagination links
        $orders->appends($request->query());

        return view('dashboard.patient.pharmacy-orders.index', compact('orders'));
    }

    /**
     * Display the specified pharmacy order.
     */
    public function show(PharmacyOrder $pharmacyOrder)
    {
        Gate::authorize('patient-access');

        // Ensure the order belongs to the authenticated patient
        if ($pharmacyOrder->patient_id !== auth()->id()) {
            abort(403);
        }

        $pharmacyOrder->load([
            'pharmacy.user',
            'prescription.prescriptionMedications.medication',
            'items.prescriptionMedication.medication'
        ]);

        return view('dashboard.patient.pharmacy-orders.show', compact('pharmacyOrder'));
    }

    /**
     * Show the payment form for the pharmacy order.
     */
    public function showPayment(PharmacyOrder $pharmacyOrder)
    {
        Gate::authorize('patient-access');

        // Ensure the order belongs to the authenticated patient
        if ($pharmacyOrder->patient_id !== auth()->id()) {
            abort(403);
        }

        // Check if payment can be processed
        if (!$pharmacyOrder->canProcessPayment()) {
            return redirect()->route('patient.pharmacy-orders.show', $pharmacyOrder)
                ->with('error', 'Payment cannot be processed for this order.');
        }

        return view('dashboard.patient.pharmacy-orders.payment', compact('pharmacyOrder'));
    }

    /**
     * Process payment for the pharmacy order.
     */
    public function processPayment(Request $request, PharmacyOrder $pharmacyOrder)
    {
        Gate::authorize('patient-access');

        // Ensure the order belongs to the authenticated patient
        if ($pharmacyOrder->patient_id !== auth()->id()) {
            abort(403);
        }

        // Check if payment can be processed
        if (!$pharmacyOrder->canProcessPayment()) {
            return redirect()->route('patient.pharmacy-orders.show', $pharmacyOrder)
                ->with('error', 'Payment cannot be processed for this order.');
        }

        $request->validate([
            'payment_method' => 'required|in:card,cash,bank_transfer',
            'card_number' => 'required_if:payment_method,card|nullable|string|min:16|max:19',
            'expiry_date' => 'required_if:payment_method,card|nullable|string',
            'cvv' => 'required_if:payment_method,card|nullable|string|min:3|max:4',
            'cardholder_name' => 'required_if:payment_method,card|nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // In a real application, you would integrate with a payment gateway
            // For now, we'll simulate payment processing
            
            // Update payment status
            $pharmacyOrder->update([
                'payment_status' => 'paid',
                'confirmed_at' => now(),
                'status' => $pharmacyOrder->status === 'pending' ? 'confirmed' : $pharmacyOrder->status,
            ]);

            DB::commit();

            return redirect()->route('patient.pharmacy-orders.show', $pharmacyOrder)
                ->with('success', 'Payment processed successfully! Your order has been confirmed.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Payment processing failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Cancel the pharmacy order.
     */
    public function cancel(PharmacyOrder $pharmacyOrder)
    {
        Gate::authorize('patient-access');

        // Ensure the order belongs to the authenticated patient
        if ($pharmacyOrder->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$pharmacyOrder->canBeCancelled()) {
            return redirect()->route('patient.pharmacy-orders.show', $pharmacyOrder)
                ->with('error', 'This order cannot be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $pharmacyOrder->update([
                'status' => 'cancelled'
            ]);

            // If payment was made, update to refunded
            if ($pharmacyOrder->payment_status === 'paid') {
                $pharmacyOrder->update([
                    'payment_status' => 'refunded'
                ]);
            }

            DB::commit();

            return redirect()->route('patient.pharmacy-orders.index')
                ->with('success', 'Order cancelled successfully.' . 
                    ($pharmacyOrder->payment_status === 'refunded' ? ' Refund will be processed within 5-7 business days.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please try again.');
        }
    }
}
