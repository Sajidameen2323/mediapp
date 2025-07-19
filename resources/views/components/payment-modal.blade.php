@props([
    'id' => 'payment-modal',
    'title' => 'Payment',
    'amount' => 0,
    'appointmentId' => null,
])

<div id="{{ $id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
    <div class="relative w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $title }}
                </h3>
                <button type="button" class="close-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <div id="payment-modal-content">
                    <div class="mb-4">
                        <div class="text-gray-700 dark:text-gray-300">
                            <p class="text-lg font-medium">Amount to Pay: <span class="font-bold text-indigo-600 dark:text-indigo-400" id="payment-amount">${{ number_format($amount, 2) }}</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400" id="payment-deposit-note"></p>
                        </div>
                    </div>

                    <form id="payment-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $amount }}" id="amount-input">
                        @if($appointmentId)
                            <input type="hidden" name="appointment_id" value="{{ $appointmentId }}">
                        @endif

                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
                            <div id="payment-methods-container" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <!-- Payment methods will be populated here dynamically -->
                                <div class="flex justify-center items-center h-20 text-gray-500 dark:text-gray-400">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"></div>
                                </div>
                            </div>
                        </div>

                        <div id="card-details" class="hidden space-y-4">
                            <div>
                                <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card Number</label>
                                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" maxlength="16">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="card_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                                    <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" maxlength="5">
                                </div>
                                <div>
                                    <label for="card_cvc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVC</label>
                                    <input type="text" id="card_cvc" name="card_cvc" placeholder="123" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" maxlength="3">
                                </div>
                            </div>
                            <div>
                                <label for="cardholder_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cardholder Name</label>
                                <input type="text" id="cardholder_name" name="cardholder_name" placeholder="John Doe" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div id="bank-details" class="hidden space-y-4">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                                <h4 class="font-medium text-blue-800 dark:text-blue-300">Bank Transfer Instructions</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                    Please transfer the exact amount to the following account:
                                </p>
                                <dl class="mt-3 space-y-1 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600 dark:text-gray-400">Account Name:</dt>
                                        <dd>MediApp Healthcare Ltd</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600 dark:text-gray-400">Account Number:</dt>
                                        <dd>12345678</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600 dark:text-gray-400">Sort Code:</dt>
                                        <dd>01-02-03</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600 dark:text-gray-400">Reference:</dt>
                                        <dd id="payment-reference">PAYMENT-{{ time() }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div id="insurance-details" class="hidden space-y-4">
                            <div>
                                <label for="insurance_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insurance Provider</label>
                                <input type="text" id="insurance_provider" name="insurance_provider" placeholder="Insurance Company Name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="policy_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Policy Number</label>
                                <input type="text" id="policy_number" name="policy_number" placeholder="Policy Number" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div id="payment-messages" class="hidden py-3 px-4 rounded-md"></div>
                    </form>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                <button type="button" id="submit-payment" class="px-5 py-2.5 text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                    Confirm Payment
                </button>
                <button type="button" class="close-modal px-5 py-2.5 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentModal = document.getElementById('{{ $id }}');
        const paymentForm = document.getElementById('payment-form');
        const paymentMethodsContainer = document.getElementById('payment-methods-container');
        const cardDetails = document.getElementById('card-details');
        const bankDetails = document.getElementById('bank-details');
        const insuranceDetails = document.getElementById('insurance-details');
        const submitPaymentBtn = document.getElementById('submit-payment');
        const paymentMessages = document.getElementById('payment-messages');
        const depositNoteElement = document.getElementById('payment-deposit-note');
        
        // Close modal
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', () => {
                paymentModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });

        // Fetch payment methods
        async function fetchPaymentMethods() {
            try {
                const response = await fetch('/api/payment-methods');
                const data = await response.json();
                
                if (data.success) {
                    renderPaymentMethods(data.payment_methods, data.deposit_percentage);
                    
                    // Display deposit note if applicable
                    if (data.deposit_percentage > 0) {
                        depositNoteElement.textContent = `This is a ${data.deposit_percentage}% deposit of the total amount.`;
                    }
                } else {
                    paymentMethodsContainer.innerHTML = '<p class="text-red-500">Failed to load payment methods</p>';
                }
            } catch (error) {
                console.error('Error fetching payment methods:', error);
                paymentMethodsContainer.innerHTML = '<p class="text-red-500">Failed to load payment methods</p>';
            }
        }

        // Render payment methods
        function renderPaymentMethods(methods, depositPercentage) {
            paymentMethodsContainer.innerHTML = '';
            
            Object.entries(methods).forEach(([key, label]) => {
                const methodCard = document.createElement('div');
                methodCard.className = 'payment-method-card border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700';
                methodCard.dataset.method = key;
                
                let icon = 'fa-money-bill';
                if (key === 'card') icon = 'fa-credit-card';
                if (key === 'online') icon = 'fa-globe';
                if (key === 'insurance') icon = 'fa-file-medical';
                if (key === 'bank_transfer') icon = 'fa-university';
                
                methodCard.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="text-indigo-500 dark:text-indigo-400 text-xl">
                            <i class="fas ${icon}"></i>
                        </div>
                        <span class="font-medium text-gray-800 dark:text-gray-200">${label}</span>
                    </div>
                `;
                
                methodCard.addEventListener('click', () => selectPaymentMethod(key));
                paymentMethodsContainer.appendChild(methodCard);
            });
        }

        // Select payment method
        function selectPaymentMethod(method) {
            // Reset selection
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('bg-indigo-50', 'border-indigo-500', 'dark:bg-indigo-900/30', 'dark:border-indigo-500');
            });
            
            // Select the clicked one
            const selectedCard = document.querySelector(`.payment-method-card[data-method="${method}"]`);
            if (selectedCard) {
                selectedCard.classList.add('bg-indigo-50', 'border-indigo-500', 'dark:bg-indigo-900/30', 'dark:border-indigo-500');
            }
            
            // Add hidden input for payment method
            let methodInput = document.querySelector('input[name="payment_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = 'payment_method';
                paymentForm.appendChild(methodInput);
            }
            methodInput.value = method;
            
            // Show/hide appropriate detail sections
            cardDetails.classList.add('hidden');
            bankDetails.classList.add('hidden');
            insuranceDetails.classList.add('hidden');
            
            if (method === 'card') {
                cardDetails.classList.remove('hidden');
            } else if (method === 'bank_transfer') {
                bankDetails.classList.remove('hidden');
            } else if (method === 'insurance') {
                insuranceDetails.classList.remove('hidden');
            }
        }

        // Submit payment
        submitPaymentBtn.addEventListener('click', async () => {
            paymentMessages.classList.add('hidden');
            
            const formData = new FormData(paymentForm);
            const paymentMethod = formData.get('payment_method');
            
            if (!paymentMethod) {
                showMessage('Please select a payment method', 'error');
                return;
            }
            
            // Validate card details if card payment
            if (paymentMethod === 'card') {
                const cardNumber = formData.get('card_number');
                const cardExpiry = formData.get('card_expiry');
                const cardCvc = formData.get('card_cvc');
                const cardholderName = formData.get('cardholder_name');
                
                if (!cardNumber || !cardExpiry || !cardCvc || !cardholderName) {
                    showMessage('Please fill in all card details', 'error');
                    return;
                }
                
                if (cardNumber.length !== 16) {
                    showMessage('Card number must be 16 digits', 'error');
                    return;
                }
                
                if (!cardExpiry.match(/^\d{2}\/\d{2}$/)) {
                    showMessage('Expiry date must be in MM/YY format', 'error');
                    return;
                }
                
                if (cardCvc.length !== 3) {
                    showMessage('CVC must be 3 digits', 'error');
                    return;
                }
            }
            
            // Disable button and show loading
            submitPaymentBtn.disabled = true;
            submitPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            
            try {
                const response = await fetch('/patient/payments/process', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showMessage('Payment successful! Redirecting...', 'success');
                    
                    // Emit payment:success event
                    const paymentSuccessEvent = new CustomEvent('payment:success', {
                        detail: {
                            paymentId: result.payment?.id,
                            appointmentId: result.payment?.appointment_id,
                            amount: result.payment?.amount
                        }
                    });
                    document.dispatchEvent(paymentSuccessEvent);
                    
                    // Close modal after delay
                    setTimeout(() => {
                        paymentModal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }, 2000);
                } else {
                    showMessage(result.message || 'Payment failed', 'error');
                    submitPaymentBtn.disabled = false;
                    submitPaymentBtn.innerHTML = 'Confirm Payment';
                }
            } catch (error) {
                console.error('Payment error:', error);
                showMessage('An error occurred while processing payment', 'error');
                submitPaymentBtn.disabled = false;
                submitPaymentBtn.innerHTML = 'Confirm Payment';
            }
        });

        // Show message
        function showMessage(message, type = 'success') {
            paymentMessages.classList.remove('hidden');
            paymentMessages.className = 'py-3 px-4 rounded-md';
            
            if (type === 'success') {
                paymentMessages.classList.add('bg-green-50', 'text-green-800', 'dark:bg-green-900/30', 'dark:text-green-300');
                paymentMessages.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i> ${message}</div>`;
            } else {
                paymentMessages.classList.add('bg-red-50', 'text-red-800', 'dark:bg-red-900/30', 'dark:text-red-300');
                paymentMessages.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i> ${message}</div>`;
            }
        }

        // Initialize payment methods when modal is opened
        const showPaymentModalEvent = new CustomEvent('showPaymentModal');
        document.addEventListener('showPaymentModal', () => {
            fetchPaymentMethods();
        });

        // Global function to show modal
        window.showPaymentModal = function(amount, appointmentId) {
            // Update amount if provided
            if (amount) {
                const amountDisplay = document.getElementById('payment-amount');
                const amountInput = document.getElementById('amount-input');
                
                amountDisplay.textContent = '$' + parseFloat(amount).toFixed(2);
                amountInput.value = amount;
            }
            
            // Update appointment ID if provided
            if (appointmentId) {
                let appointmentInput = document.querySelector('input[name="appointment_id"]');
                if (!appointmentInput) {
                    appointmentInput = document.createElement('input');
                    appointmentInput.type = 'hidden';
                    appointmentInput.name = 'appointment_id';
                    paymentForm.appendChild(appointmentInput);
                }
                appointmentInput.value = appointmentId;
            }
            
            // Show modal
            paymentModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Trigger event to fetch payment methods
            document.dispatchEvent(showPaymentModalEvent);
        };

        // Format card number as user types
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput?.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 16);
        });

        // Format expiry date as MM/YY
        const cardExpiryInput = document.getElementById('card_expiry');
        cardExpiryInput?.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });

        // Only allow digits for CVC
        const cardCvcInput = document.getElementById('card_cvc');
        cardCvcInput?.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
        });
    });
</script>
