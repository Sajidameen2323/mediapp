<?php

namespace App\Console\Commands;

use App\Models\DoctorBreak;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDoctorBreakUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctor:test-break-update {id : The ID of the break to test update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test updating a doctor break directly';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $break = DoctorBreak::find($id);
        
        if (!$break) {
            $this->error("Break with ID $id not found");
            return 1;
        }
        
        $this->info("Current break details:");
        $this->table(
            ['ID', 'Doctor ID', 'Start Time', 'End Time', 'Day', 'Is Active', 'Created At', 'Updated At'],
            [[$break->id, $break->doctor_id, $break->start_time, $break->end_time, $break->day_of_week, $break->is_active ? 'Yes' : 'No', $break->created_at, $break->updated_at]]
        );
        
        // Test direct update with model
        $newStartTime = '10:30';
        $newEndTime = '11:30';
        
        $this->info("Updating start_time to $newStartTime and end_time to $newEndTime");
        
        $break->start_time = $newStartTime;
        $break->end_time = $newEndTime;
        $result = $break->save();
        
        $this->info("Save result: " . ($result ? "Success" : "Failed"));
        
        // Refresh from database
        $break->refresh();
        
        $this->info("Updated break details:");
        $this->table(
            ['ID', 'Doctor ID', 'Start Time', 'End Time', 'Day', 'Is Active', 'Created At', 'Updated At'],
            [[$break->id, $break->doctor_id, $break->start_time, $break->end_time, $break->day_of_week, $break->is_active ? 'Yes' : 'No', $break->created_at, $break->updated_at]]
        );
        
        // Check raw database values
        $rawData = DB::table('doctor_breaks')->where('id', $id)->first();
        $this->info("Raw database values:");
        $this->table(
            ['ID', 'Doctor ID', 'Start Time', 'End Time', 'Day', 'Updated At'],
            [[$rawData->id, $rawData->doctor_id, $rawData->start_time, $rawData->end_time, $rawData->day_of_week, $rawData->updated_at]]
        );
        
        return 0;
    }
}
