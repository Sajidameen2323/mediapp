<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the doctor search with ratings
echo "🔍 Testing Doctor Search with Ratings and Review Count\n";
echo "=" . str_repeat("=", 60) . "\n\n";

try {
    // Create a mock request
    $request = new Request();
    $request->merge([
        'q' => 'cardiology', // Search for cardiologists
    ]);

    // Get the search service
    $doctorSearchService = app(\App\Services\DoctorSearchService::class);

    // Perform search
    echo "🔎 Searching for doctors with query: 'cardiology'\n";
    $filters = [
        'service_id' => null,
        'specialization' => null,
        'available_today' => null
    ];

    $doctors = $doctorSearchService->searchDoctors('cardiology', $filters);
    echo "📊 Found " . $doctors->count() . " doctors\n\n";

    // Transform data for frontend
    $doctorsData = $doctorSearchService->transformDoctorData($doctors);

    // Display results with ratings
    foreach ($doctorsData as $index => $doctor) {
        echo "👨‍⚕️ Doctor #" . ($index + 1) . ":\n";
        echo "   Name: " . $doctor['name'] . "\n";
        echo "   Specialization: " . $doctor['specialization'] . "\n";
        echo "   Experience: " . $doctor['experience_years'] . " years\n";
        echo "   Consultation Fee: $" . $doctor['consultation_fee'] . "\n";
        echo "   ⭐ Rating: " . number_format($doctor['rating'], 1) . "/5\n";
        echo "   📝 Review Count: " . $doctor['rating_count'] . " reviews\n";
        echo "   Available: " . ($doctor['is_available'] ? 'Yes' : 'No') . "\n";
        echo "   Services: " . count($doctor['services']) . " services available\n";
        echo "   " . str_repeat("-", 50) . "\n\n";
    }

    // Test with a broader search
    echo "\n🔎 Searching for all available doctors\n";
    $allDoctors = $doctorSearchService->searchDoctors('', []);
    $allDoctorsData = $doctorSearchService->transformDoctorData($allDoctors);

    echo "📊 Found " . count($allDoctorsData) . " available doctors\n\n";

    // Show ratings summary
    $totalRatings = 0;
    $totalReviews = 0;
    $doctorsWithRatings = 0;

    foreach ($allDoctorsData as $doctor) {
        if ($doctor['rating'] > 0) {
            $totalRatings += $doctor['rating'];
            $doctorsWithRatings++;
        }
        $totalReviews += $doctor['rating_count'];
    }

    echo "📈 Ratings Summary:\n";
    echo "   Total doctors: " . count($allDoctorsData) . "\n";
    echo "   Doctors with ratings: " . $doctorsWithRatings . "\n";
    echo "   Average rating: " . ($doctorsWithRatings > 0 ? number_format($totalRatings / $doctorsWithRatings, 2) : 'N/A') . "\n";
    echo "   Total reviews: " . $totalReviews . "\n";

    echo "\n✅ Test completed successfully!\n";
    echo "🎯 The searchDoctors method now includes:\n";
    echo "   - rating: Average rating for each doctor\n";
    echo "   - rating_count: Number of reviews for each doctor\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    
    if (method_exists($e, 'getTraceAsString')) {
        echo "\n🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
}
