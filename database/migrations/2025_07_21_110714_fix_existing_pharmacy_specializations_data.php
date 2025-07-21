<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix existing pharmacy specializations data that might be stored as JSON strings
        $pharmacies = DB::table('pharmacies')->whereNotNull('specializations')->get();
        
        foreach ($pharmacies as $pharmacy) {
            $specializations = $pharmacy->specializations;
            
            // If it's a JSON string, convert it to a proper JSON array format
            if (is_string($specializations) && $this->isJsonString($specializations)) {
                $decodedArray = json_decode($specializations, true);
                if (is_array($decodedArray)) {
                    // Re-encode it properly for JSON storage
                    DB::table('pharmacies')
                        ->where('id', $pharmacy->id)
                        ->update(['specializations' => json_encode($decodedArray)]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only fixes data format, no structural changes to reverse
    }
    
    /**
     * Check if a string is valid JSON
     */
    private function isJsonString($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
};
