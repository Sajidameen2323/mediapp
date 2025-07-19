<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Payment Examples</h2>
    
    <div class="space-y-6">
        <!-- Standard Payment Example -->
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Standard Payment</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Process a standard payment for $49.99</p>
            
            <button type="button" id="standard-payment-btn" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-700">
                Pay Now
            </button>
        </div>
        
        <!-- Deposit Payment Example -->
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Deposit Payment</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Pay a 25% deposit of $100.00 = $25.00</p>
            
            <button type="button" id="deposit-payment-btn"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-700">
                Pay Deposit
            </button>
        </div>
        
        <!-- Appointment Payment Example -->
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Appointment Payment</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Pay for an appointment (ID: 123) for $75.00</p>
            
            <button type="button" id="appointment-payment-btn"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-700">
                Pay for Appointment
            </button>
        </div>
    </div>
</div>

<!-- Include the payment modal component -->
<x-payment-modal id="payment-modal" />

<script src="{{ asset('js/payment-modal-helper.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Standard payment example
        document.getElementById('standard-payment-btn').addEventListener('click', function() {
            showPaymentModal(49.99);
        });
        
        // Deposit payment example
        document.getElementById('deposit-payment-btn').addEventListener('click', function() {
            showDepositPaymentModal(100.00, 25);
        });
        
        // Appointment payment example
        document.getElementById('appointment-payment-btn').addEventListener('click', function() {
            showPaymentModal(75.00, 123, 'Appointment Payment');
        });
    });
</script>
