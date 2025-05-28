<?php

require_once 'vendor/autoload.php';

// Test if we can create a basic PDF
try {
    // Load Laravel app
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Test PDF creation
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<h1>Test PDF</h1><p>This is a test PDF document.</p>');
    
    echo "PDF facade loaded successfully!\n";
    echo "PDF object type: " . get_class($pdf) . "\n";
    
    // Try to generate PDF content
    $output = $pdf->output();
    echo "PDF generated successfully! Size: " . strlen($output) . " bytes\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
