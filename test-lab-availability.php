<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Laboratory;
use Carbon\Carbon;

$lab = Laboratory::first();

if ($lab) {
    echo "Laboratory: " . $lab->name . "\n";
    echo "Is Available: " . ($lab->is_available ? 'YES' : 'NO') . "\n";
    echo "Working Days: " . json_encode($lab->working_days) . "\n";
    echo "Available today: " . ($lab->isAvailableOnDate(now()) ? 'YES' : 'NO') . "\n";
    echo "Available tomorrow: " . ($lab->isAvailableOnDate(now()->addDay()) ? 'YES' : 'NO') . "\n";
    echo "Opening time raw: " . $lab->opening_time . "\n";
    echo "Closing time raw: " . $lab->closing_time . "\n";
    
    // Test time parsing
    $openingTime = Carbon::parse($lab->opening_time ?? '09:00');
    $closingTime = Carbon::parse($lab->closing_time ?? '17:00');
    echo "Parsed opening time: " . $openingTime->format('H:i') . "\n";
    echo "Parsed closing time: " . $closingTime->format('H:i') . "\n";
    
    // Test slot generation
    $slots = [];
    $currentTime = $openingTime->copy();
    $count = 0;
    
    while ($currentTime->lt($closingTime) && $count < 10) {
        $timeSlot = $currentTime->format('H:i');
        $slots[] = [
            'time' => $timeSlot,
            'formatted' => $currentTime->format('g:i A')
        ];
        $currentTime->addMinutes(30);
        $count++;
    }
    
    echo "Generated slots: " . json_encode($slots) . "\n";
} else {
    echo "No laboratory found\n";
}
