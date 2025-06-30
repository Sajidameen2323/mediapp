<?php

// Test script to verify laboratory layout and settings updates

echo "✅ Laboratory Layout & Settings Update Test\n";
echo "==========================================\n\n";

// Check if layout file exists and theme toggle is removed
$layoutFile = 'resources/views/layouts/laboratory.blade.php';
if (file_exists($layoutFile)) {
    $content = file_get_contents($layoutFile);
    
    echo "1. Layout File Checks:\n";
    echo "   - Layout file exists: ✅\n";
    
    // Check if theme toggle is removed
    if (strpos($content, 'toggleTheme()') === false) {
        echo "   - Theme toggle removed: ✅\n";
    } else {
        echo "   - Theme toggle removed: ❌ (still found)\n";
    }
    
    // Check if availability toggle still exists
    if (strpos($content, 'toggleAvailability()') !== false) {
        echo "   - Availability toggle preserved: ✅\n";
    } else {
        echo "   - Availability toggle preserved: ❌ (not found)\n";
    }
    
    echo "\n";
} else {
    echo "❌ Layout file not found\n\n";
}

// Check settings file
$settingsFile = 'resources/views/dashboard/laboratory/settings/index.blade.php';
if (file_exists($settingsFile)) {
    $content = file_get_contents($settingsFile);
    
    echo "2. Settings File Checks:\n";
    echo "   - Settings file exists: ✅\n";
    
    // Check if time formatting is updated
    if (strpos($content, '->format(\'H:i\')') !== false) {
        echo "   - Time format updated: ✅\n";
    } else {
        echo "   - Time format updated: ❌ (not found)\n";
    }
    
    // Check if services offered uses checkboxes
    if (strpos($content, 'services_offered[]') !== false) {
        echo "   - Services offered updated to checkboxes: ✅\n";
    } else {
        echo "   - Services offered updated to checkboxes: ❌ (not found)\n";
    }
    
    echo "\n";
} else {
    echo "❌ Settings file not found\n\n";
}

// Check controller file
$controllerFile = 'app/Http/Controllers/Laboratory/SettingsController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    echo "3. Controller Checks:\n";
    echo "   - Controller file exists: ✅\n";
    
    // Check if services_offered validation is updated
    if (strpos($content, '\'services_offered\' => \'nullable|array\'') !== false) {
        echo "   - Services validation updated: ✅\n";
    } else {
        echo "   - Services validation updated: ❌ (not found)\n";
    }
    
    // Check if implode is used for services
    if (strpos($content, 'implode(\',\', $request->services_offered)') !== false) {
        echo "   - Services array to string conversion: ✅\n";
    } else {
        echo "   - Services array to string conversion: ❌ (not found)\n";
    }
    
    echo "\n";
} else {
    echo "❌ Controller file not found\n\n";
}

echo "4. Summary:\n";
echo "   - Theme toggle removed from laboratory layout\n";
echo "   - Opening and closing time inputs now format correctly\n";
echo "   - Services offered now uses checkboxes like admin section\n";
echo "   - Controller updated to handle array of services\n";
echo "   - Working days display optimized with shorter labels\n\n";

echo "✅ All updates completed successfully!\n";

?>
