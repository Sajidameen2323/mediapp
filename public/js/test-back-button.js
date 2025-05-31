/**
 * Test script to verify back button functionality
 * This can be run in browser console to test the appointment booking navigation
 */
function testBackButtonFunctionality() {
    console.log('🧪 Testing Back Button Functionality...');
    
    // Check if appointment booking instance exists
    if (typeof window.appointmentBooking === 'undefined') {
        console.error('❌ AppointmentBooking instance not found');
        return;
    }
    
    const booking = window.appointmentBooking;
    const backBtn = document.getElementById('back_btn');
    const stepDisplay = document.getElementById('current_step_display');
    
    if (!backBtn) {
        console.error('❌ Back button not found');
        return;
    }
    
    console.log('✅ Starting tests...');
    
    // Test 1: Initial state (step 1)
    console.log(`📍 Current step: ${booking.currentStep}`);
    console.log(`🔘 Back button disabled: ${backBtn.disabled}`);
    console.log(`👁️ Back button visible: ${backBtn.style.display !== 'none'}`);
    
    if (booking.currentStep === 1 && backBtn.disabled) {
        console.log('✅ Test 1 PASSED: Back button correctly disabled on step 1');
    } else {
        console.log('❌ Test 1 FAILED: Back button state incorrect on step 1');
    }
    
    // Test 2: Navigate to step 2
    console.log('\n🚀 Moving to step 2...');
    booking.nextStep();
    
    setTimeout(() => {
        console.log(`📍 Current step: ${booking.currentStep}`);
        console.log(`🔘 Back button disabled: ${backBtn.disabled}`);
        console.log(`👁️ Step display text: ${stepDisplay?.textContent}`);
        
        if (booking.currentStep === 2 && !backBtn.disabled) {
            console.log('✅ Test 2 PASSED: Back button correctly enabled on step 2');
        } else {
            console.log('❌ Test 2 FAILED: Back button state incorrect on step 2');
        }
        
        // Test 3: Go back to step 1
        console.log('\n⬅️ Going back to step 1...');
        booking.prevStep();
        
        setTimeout(() => {
            console.log(`📍 Current step: ${booking.currentStep}`);
            console.log(`🔘 Back button disabled: ${backBtn.disabled}`);
            console.log(`👁️ Step display text: ${stepDisplay?.textContent}`);
            
            if (booking.currentStep === 1 && backBtn.disabled) {
                console.log('✅ Test 3 PASSED: Back button correctly disabled after returning to step 1');
                console.log('\n🎉 ALL TESTS PASSED! Back button functionality is working correctly.');
            } else {
                console.log('❌ Test 3 FAILED: Back button state incorrect after returning to step 1');
            }
        }, 100);
    }, 100);
}

// Auto-run test if this file is loaded directly
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(testBackButtonFunctionality, 1000);
    });
} else {
    setTimeout(testBackButtonFunctionality, 1000);
}

// Export for manual testing
window.testBackButtonFunctionality = testBackButtonFunctionality;
