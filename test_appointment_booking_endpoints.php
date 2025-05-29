<?php

/**
 * Test script to verify all appointment booking endpoints are working
 * Run this script to test the API endpoints independently
 */

$baseUrl = 'http://localhost:8000';

// Test endpoints
$endpoints = [
    'Search Doctors' => '/api/appointments/search-doctors?initial=1',
    'Selectable Dates' => '/api/appointments/selectable-dates?doctor_id=1',
    'Available Slots' => '/api/appointments/available-slots?doctor_id=1&date=2025-06-01',
    'Doctor Services' => '/api/doctors/1/services',
];

echo "Testing Appointment Booking API Endpoints\n";
echo "=========================================\n\n";

foreach ($endpoints as $name => $endpoint) {
    echo "Testing: $name\n";
    echo "URL: $baseUrl$endpoint\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            'timeout' => 10
        ]
    ]);
    
    $response = @file_get_contents($baseUrl . $endpoint, false, $context);
    
    if ($response === false) {
        echo "❌ FAILED: Could not reach endpoint\n";
    } else {
        $data = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($data['success']) && $data['success']) {
                echo "✅ SUCCESS: Valid JSON response received\n";
                if (isset($data['doctors'])) {
                    echo "   Found " . count($data['doctors']) . " doctors\n";
                }
                if (isset($data['slots'])) {
                    echo "   Found " . count($data['slots']) . " slots\n";
                }
                if (isset($data['services'])) {
                    echo "   Found " . count($data['services']) . " services\n";
                }
                if (isset($data['selectable_dates'])) {
                    echo "   Found " . count($data['selectable_dates']) . " selectable dates\n";
                }
            } else {
                echo "❌ FAILED: Response indicates failure\n";
                if (isset($data['message'])) {
                    echo "   Error: " . $data['message'] . "\n";
                }
            }
        } else {
            echo "❌ FAILED: Invalid JSON response\n";
            echo "   Response: " . substr($response, 0, 200) . "...\n";
        }
    }
    
    echo "\n";
}

echo "Test completed!\n";
