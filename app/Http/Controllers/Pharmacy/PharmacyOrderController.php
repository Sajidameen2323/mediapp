<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\UpdateOrderItemsRequest;
use App\Http\Requests\Pharmacy\CancelOrderRequest;
use App\Models\PharmacyOrder;
use App\Models\PharmacyOrderItem;
use App\Models\PrescriptionMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PharmacyOrderController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->user_type !== 'pharmacist' || !auth()->user()->pharmacy) {
                abort(403, 'Unauthorized access to pharmacy features.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of pharmacy orders.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', PharmacyOrder::class);

        $query = PharmacyOrder::with(['prescription', 'patient', 'items'])
            ->where('pharmacy_id', Auth::user()->pharmacy->id);

        // Filter by status - check for both existence and non-empty value
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number or patient name - check for both existence and non-empty value
        if ($request->filled('search')) {
            $search = trim($request->search);
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('patient', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pharmacy.orders.index', compact('orders'));
    }

    /**
     * Display the specified pharmacy order.
     */
    public function show(PharmacyOrder $order)
    {
        $this->authorize('view', $order);

        $order->load([
            'prescription.prescriptionMedications.medication',
            'patient',
            'items.prescriptionMedication.medication'
        ]);

        return view('pharmacy.orders.show', compact('order'));
    }

    /**
     * Show the form for preparing order items.
     */
    public function prepare(PharmacyOrder $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'confirmed' && $order->status !== 'preparing') {
            return redirect()->route('pharmacy.orders.show', $order)
                ->with('error', 'Only confirmed orders can be prepared.');
        }

        // Create order items if they don't exist
        if ($order->items->isEmpty()) {
            $this->createOrderItems($order);
        }

        $order->load([
            'prescription.prescriptionMedications.medication',
            'items.prescriptionMedication.medication'
        ]);

        return view('pharmacy.orders.prepare', compact('order'));
    }

    /**
     * Update order items during preparation.
     */
    public function updateItems(UpdateOrderItemsRequest $request, PharmacyOrder $order)
    {
        $this->authorize('update', $order);

        // Additional business logic validation
        if ($order->status !== 'confirmed' && $order->status !== 'preparing') {
            return redirect()->route('pharmacy.orders.show', $order)
                ->with('error', 'Only confirmed or preparing orders can be updated.');
        }

        DB::transaction(function () use ($request, $order) {
            $subtotal = 0;
            $hasAvailableItems = false;

            foreach ($request->validated()['items'] as $itemData) {
                $item = PharmacyOrderItem::findOrFail($itemData['id']);
                
                // Verify item belongs to this order
                if ($item->pharmacy_order_id !== $order->id) {
                    throw new \Exception('Invalid item for this order.');
                }
                
                $item->update([
                    'quantity_dispensed' => $itemData['quantity_dispensed'],
                    'unit_price' => $itemData['unit_price'],
                    'is_available' => $itemData['is_available'],
                    'pharmacy_notes' => $itemData['pharmacy_notes'] ?? null,
                    'total_price' => $itemData['quantity_dispensed'] * $itemData['unit_price'],
                    'status' => $this->determineItemStatus($itemData)
                ]);

                $subtotal += $item->total_price;
                
                // Check if we have at least one available item
                if ($itemData['is_available'] && $itemData['quantity_dispensed'] > 0) {
                    $hasAvailableItems = true;
                }
            }

            // Ensure at least one item is available
            if (!$hasAvailableItems) {
                return back()
                    ->withErrors(['items' => 'At least one medication must be available and have quantity greater than 0.'])
                    ->withInput();
            }

            // Calculate tax and total
            $taxRate = 0.10; // 10% tax rate - can be configurable
            $taxAmount = $subtotal * $taxRate;
            $totalAmount = $subtotal + $taxAmount + $order->delivery_fee;

            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'pharmacy_notes' => $request->validated()['pharmacy_notes'] ?? $order->pharmacy_notes,
                'status' => 'preparing'
            ]);
        });

        return redirect()->route('pharmacy.orders.show', $order)
            ->with('success', 'Order items updated successfully.');
    }

    /**
     * Mark order as ready for pickup/delivery.
     */
    public function markReady(PharmacyOrder $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'preparing') {
            return redirect()->route('pharmacy.orders.show', $order)
                ->with('error', 'Only orders being prepared can be marked as ready.');
        }

        $order->update([
            'status' => 'ready',
            'ready_at' => now()
        ]);

        // Notify patient (you can implement notification logic here)
        // NotificationService::notifyOrderReady($order);

        return redirect()->route('pharmacy.orders.show', $order)
            ->with('success', 'Order marked as ready. Patient has been notified.');
    }

    /**
     * Dispense the order (mark as delivered).
     */
    public function dispense(PharmacyOrder $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'ready') {
            return redirect()->route('pharmacy.orders.show', $order)
                ->with('error', 'Only ready orders can be dispensed.');
        }

        $order->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        // Update prescription medications as dispensed
        foreach ($order->items as $item) {
            $item->prescriptionMedication->update([
                'quantity_dispensed' => $item->quantity_dispensed
            ]);
            
            $item->update(['status' => 'dispensed']);
        }

        return redirect()->route('pharmacy.orders.show', $order)
            ->with('success', 'Order dispensed successfully.');
    }

    /**
     * Cancel the order.
     */
    public function cancel(CancelOrderRequest $request, PharmacyOrder $order)
    {
        $this->authorize('update', $order);

        if (!in_array($order->status, ['confirmed', 'preparing'])) {
            return redirect()->route('pharmacy.orders.show', $order)
                ->with('error', 'This order cannot be cancelled.');
        }

        DB::transaction(function () use ($request, $order) {
            // Update order status and add cancellation reason
            $order->update([
                'status' => 'cancelled',
                'pharmacy_notes' => $request->validated()['cancellation_reason']
            ]);

            // Update all items to cancelled status
            $order->items()->update(['status' => 'cancelled']);
        });

        return redirect()->route('pharmacy.orders.index')
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Create order items from prescription medications.
     */
    private function createOrderItems(PharmacyOrder $order)
    {
        $prescriptionMedications = $order->prescription->prescriptionMedications;

        foreach ($prescriptionMedications as $prescriptionMedication) {
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
    }

    /**
     * Determine item status based on availability and quantity.
     */
    private function determineItemStatus(array $itemData): string
    {
        if (!$itemData['is_available']) {
            return 'unavailable';
        }

        if ($itemData['quantity_dispensed'] == 0) {
            return 'unavailable';
        }

        // Find the original item to compare with quantity_prescribed
        $item = PharmacyOrderItem::find($itemData['id']);
        if ($item && $itemData['quantity_dispensed'] < $item->quantity_prescribed) {
            return 'partial';
        }

        return 'available';
    }
}
