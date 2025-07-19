<?php

namespace App\Http\Controllers;

use App\Models\AppointmentConfig;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\ProcessPaymentRequest;

class PaymentController extends Controller
{
    /**
     * Process payment request
     *
     * @param ProcessPaymentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function process(ProcessPaymentRequest $request)
    {
        // Get the validated data
        $validated = $request->validated();
        
        // Get the active configuration
        $config = AppointmentConfig::getActive();
        
        // Create a new payment record
        $payment = new Payment();
        $payment->amount = $validated['amount'];
        $payment->payment_method = $validated['payment_method'];
        $payment->appointment_id = $validated['appointment_id'] ?? null;
        $payment->user_id = auth()->id();
        $payment->status = 'completed'; // In a real app, this would be 'pending' until confirmed
        $payment->reference = 'PAYMENT-' . time(); // Simple mock reference number
        $payment->save();
        
        // Update appointment payment status if this is an appointment payment
        if ($payment->appointment_id) {
            $appointment = \App\Models\Appointment::find($payment->appointment_id);
            
            if ($appointment) {
                // Add the payment to the paid amount
                $newPaidAmount = ($appointment->paid_amount ?? 0) + $payment->amount;
                $appointment->paid_amount = $newPaidAmount;
                
                // Update payment status based on paid amount vs total amount
                if ($newPaidAmount >= $appointment->total_amount) {
                    $appointment->payment_status = 'paid';
                } else {
                    $appointment->payment_status = 'partial';
                }
                
                $appointment->save();
            }
        }
        
        // Return response based on the payment method
        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'payment' => $payment,
            'appointment_updated' => isset($appointment),
        ]);
    }
    
    /**
     * Get available payment methods from config
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPaymentMethods()
    {
        $config = AppointmentConfig::getActive();
        $allMethods = AppointmentConfig::getPaymentMethods();
        
        // Filter available methods based on config
        $availableMethods = collect($allMethods)
            ->filter(function($label, $key) use ($config) {
                return in_array($key, $config->accepted_payment_methods);
            })
            ->toArray();
            
        return response()->json([
            'success' => true,
            'payment_methods' => $availableMethods,
            'require_payment' => $config->require_payment_on_booking,
            'deposit_percentage' => $config->booking_deposit_percentage,
        ]);
    }
}
