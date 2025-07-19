<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class UpdateAppointmentPaymentStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:update-payment-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment statuses of appointments for testing payment features';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating appointment payment statuses...');
        
        // Get all appointments
        $appointments = Appointment::all();
        
        $count = 0;
        foreach ($appointments as $appointment) {
            // Skip if no total amount
            if (!$appointment->total_amount) {
                continue;
            }
            
            // Generate random payment status
            $paymentStatus = $this->generateRandomPaymentStatus();
            
            // Update appointment payment status
            $appointment->payment_status = $paymentStatus;
            
            // Set paid amount based on payment status
            if ($paymentStatus === 'paid') {
                $appointment->paid_amount = $appointment->total_amount;
            } elseif ($paymentStatus === 'partial') {
                // Random percentage between 10% and 90%
                $percentage = rand(10, 90);
                $appointment->paid_amount = round(($appointment->total_amount * $percentage / 100), 2);
            } else {
                $appointment->paid_amount = 0;
            }
            
            $appointment->save();
            $count++;
            
            // Output progress
            $this->info("Updated appointment #{$appointment->id} - Status: {$paymentStatus}, Paid: {$appointment->paid_amount}");
        }
        
        $this->info("Updated payment status for {$count} appointments.");
    }
    
    /**
     * Generate a random payment status.
     */
    private function generateRandomPaymentStatus()
    {
        $statuses = [
            'unpaid' => 40,   // 40% probability
            'partial' => 30,  // 30% probability
            'paid' => 30      // 30% probability
        ];
        
        $rand = rand(1, 100);
        $sum = 0;
        
        foreach ($statuses as $status => $probability) {
            $sum += $probability;
            if ($rand <= $sum) {
                return $status;
            }
        }
        
        return 'unpaid';
    }
}
