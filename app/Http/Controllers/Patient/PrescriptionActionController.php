<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PharmacyOrderRequest;
use App\Models\Pharmacy;
use App\Models\PharmacyOrder;
use App\Models\PharmacyOrderItem;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PrescriptionActionController extends Controller
{
    /**
     * Show pharmacy order form.
     */
    public function orderFromPharmacy(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$prescription->canBeOrdered()) {
            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('error', 'This prescription cannot be ordered at this time.');
        }

        $pharmacies = Pharmacy::available()
            ->with('user')
            ->orderBy('pharmacy_name')
            ->get();

        return view('dashboard.patient.prescriptions.order-pharmacy', compact('prescription', 'pharmacies'));
    }

    /**
     * Store pharmacy order.
     */
    public function storePharmacyOrder(PharmacyOrderRequest $request, Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$prescription->canBeOrdered()) {
            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('error', 'This prescription cannot be ordered at this time.');
        }

        try {
            DB::beginTransaction();

            $pharmacy = Pharmacy::findOrFail($request->pharmacy_id);
            
            // Calculate pricing (basic implementation)
            $subtotal = $prescription->prescriptionMedications->reduce(function($carry, $medication) {
                return $carry + ($medication->unit_price * $medication->quantity_prescribed);
            }, 0);
            
            $deliveryFee = $request->delivery_method === 'delivery' ? $pharmacy->home_delivery_fee : 0;
            $taxRate = 0.08; // 8% tax rate
            $taxAmount = $subtotal * $taxRate;
            $totalAmount = $subtotal + $deliveryFee + $taxAmount;

            $order = PharmacyOrder::create([
                'order_number' => PharmacyOrder::generateOrderNumber(),
                'prescription_id' => $prescription->id,
                'patient_id' => auth()->id(),
                'pharmacy_id' => $pharmacy->id,
                'delivery_method' => $request->delivery_method,
                'delivery_address' => $request->delivery_address,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'confirmed', // Auto-confirm orders
            ]);

            // Create order items from prescription medications
            foreach ($prescription->prescriptionMedications as $prescriptionMedication) {
                PharmacyOrderItem::create([
                    'pharmacy_order_id' => $order->id,
                    'prescription_medication_id' => $prescriptionMedication->id,
                    'medication_name' => $prescriptionMedication->medication->name,
                    'dosage' => $prescriptionMedication->dosage,
                    'frequency' => $prescriptionMedication->frequency,
                    'duration' => $prescriptionMedication->duration,
                    'instructions' => $prescriptionMedication->instructions,
                    'quantity_prescribed' => $prescriptionMedication->quantity_prescribed,
                    'quantity_dispensed' => $prescriptionMedication->quantity_prescribed,
                    'unit_price' => $prescriptionMedication->unit_price ?? 0,
                    'total_price' => ($prescriptionMedication->quantity_prescribed * ($prescriptionMedication->unit_price ?? 0)),
                    'status' => 'pending'
                ]);
            }

            DB::commit();

            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('success', 'Pharmacy order placed successfully! Order number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Request refill.
     */
    public function requestRefill(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$prescription->hasRefillsRemaining()) {
            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('error', 'No refills remaining for this prescription.');
        }

        try {
            $prescription->processRefill();

            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('success', 'Refill requested successfully! Refills remaining: ' . $prescription->refills_remaining);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to request refill. Please try again.');
        }
    }

    /**
     * Mark prescription as completed.
     */
    public function markCompleted(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$prescription->canBeCompleted()) {
            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('error', 'This prescription cannot be marked as completed.');
        }

        try {
            $prescription->markAsCompleted();

            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('success', 'Prescription marked as completed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to mark prescription as completed. Please try again.');
        }
    }

    /**
     * Cancel a pharmacy order.
     */
    public function cancelPharmacyOrder(PharmacyOrder $pharmacyOrder)
    {
        Gate::authorize('patient-access');

        // Ensure the pharmacy order belongs to the authenticated patient
        if ($pharmacyOrder->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$pharmacyOrder->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'This pharmacy order cannot be cancelled at this time.');
        }

        try {
            $pharmacyOrder->update(['status' => 'cancelled']);

            return redirect()->route('patient.prescriptions.show', $pharmacyOrder->prescription)
                ->with('success', 'Pharmacy order cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel pharmacy order. Please try again.');
        }
    }

    /**
     * Mark prescription as active.
     */
    public function markAsActive(Prescription $prescription)
    {
        Gate::authorize('patient-access');

        // Ensure the prescription belongs to the authenticated patient
        if ($prescription->patient_id !== auth()->id()) {
            abort(403);
        }

        if (!$prescription->canBeActivated()) {
            return redirect()->back()
                ->with('error', 'This prescription cannot be marked as active at this time.');
        }

        try {
            $prescription->markAsActive();

            return redirect()->route('patient.prescriptions.show', $prescription)
                ->with('success', 'Prescription marked as active successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to mark prescription as active. Please try again.');
        }
    }
}
