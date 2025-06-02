<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing User model profile method...\n";

try {
    $user = App\Models\User::first();
    if ($user) {
        echo "User found: " . $user->name . "\n";
        echo "User type: " . $user->user_type . "\n";
        $profile = $user->profile();
        echo "Profile method works! Returns: " . gettype($profile) . "\n";
        echo "Profile data count: " . count($profile) . " items\n";
    } else {
        echo "No users found in database\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
