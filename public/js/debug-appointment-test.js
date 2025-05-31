/**
 * Test script to demonstrate the debug functionality
 * This can be run in browser console to test the debug button
 */

// Test function to simulate clicking the debug button
function testDebugFunctionality() {
    console.log('🧪 Testing Debug Button Functionality...\n');
    
    // Check if appointment booking is initialized
    if (typeof window.appointmentBooking === 'undefined') {
        console.error('❌ AppointmentBooking not found. Make sure you\'re on the appointment booking page.');
        return;
    }
    
    // Check if debug button exists
    const debugBtn = document.getElementById('debug_form_state');
    if (!debugBtn) {
        console.error('❌ Debug button not found. Make sure you\'re on the final step (step 5).');
        return;
    }
    
    console.log('✅ Debug button found. Simulating click...\n');
    
    // Simulate debug button click
    debugBtn.click();
    
    console.log('\n🎉 Debug test completed! Check the console output above for form state details.');
}

// Test function to verify debug method exists
function verifyDebugMethod() {
    console.log('🔍 Verifying Debug Method...\n');
    
    if (typeof window.appointmentBooking === 'undefined') {
        console.error('❌ AppointmentBooking not found.');
        return false;
    }
    
    if (typeof window.appointmentBooking.logCurrentFormState === 'function') {
        console.log('✅ logCurrentFormState method exists');
        return true;
    } else {
        console.error('❌ logCurrentFormState method not found');
        return false;
    }
}

// Export functions for console use
window.testDebugFunctionality = testDebugFunctionality;
window.verifyDebugMethod = verifyDebugMethod;

console.log('🚀 Debug test functions loaded. Use:');
console.log('  - testDebugFunctionality() to test the debug button');
console.log('  - verifyDebugMethod() to check if debug method exists');
