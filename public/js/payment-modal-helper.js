/**
 * Payment Modal Helper
 * 
 * This script provides helper functions for working with the payment modal component.
 */

// Initialize the payment modal
const initPaymentModal = () => {
    // Check if the modal already exists
    if (!document.getElementById('payment-modal')) {
        console.error('Payment modal not found in the DOM');
        return false;
    }
    return true;
};

// Show the payment modal with the specified amount and appointment ID
const showPaymentModal = (amount, appointmentId = null, title = 'Payment') => {
    if (!initPaymentModal()) return;
    
    // Update the modal title if provided
    const modalTitle = document.querySelector('#payment-modal .text-xl');
    if (modalTitle) {
        modalTitle.textContent = title;
    }
    
    // Use the global function defined in the modal component
    window.showPaymentModal(amount, appointmentId);
};

// Show a deposit payment modal
const showDepositPaymentModal = (fullAmount, depositPercentage, appointmentId = null) => {
    if (!initPaymentModal()) return;
    
    const depositAmount = (fullAmount * (depositPercentage / 100)).toFixed(2);
    showPaymentModal(depositAmount, appointmentId, 'Deposit Payment');
};

// Export the functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initPaymentModal,
        showPaymentModal,
        showDepositPaymentModal
    };
}
