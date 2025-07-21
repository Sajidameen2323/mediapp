@props([
    'id' => 'payment-modal',
    'title' => 'Payment',
    'amount' => 0,
    'appointmentId' => null,
])

<!-- Modal backdrop -->
<div id="{{ $id }}" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6 lg:p-8">
        <!-- Modal backdrop overlay -->
        <div class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 transition-opacity"></div>
        
        <!-- Modal content -->
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all dark:bg-gray-800 sm:max-w-xl lg:max-w-2xl">
            <!-- Modal header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700 sm:px-8 sm:py-6">
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <i class="fas fa-credit-card text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white sm:text-xl">
                            {{ $title }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Secure payment processing</p>
                    </div>
                </div>
                <button type="button" class="close-modal inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="px-6 py-6 sm:px-8 sm:py-8">
                <div id="payment-modal-content">
                    <!-- Amount Section -->
                    <div class="mb-8 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 p-6 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <div class="text-center">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Amount to Pay</p>
                            <p class="mt-1 text-3xl font-bold text-blue-600 dark:text-blue-400 sm:text-4xl" id="payment-amount">
                                ${{ number_format($amount, 2) }}
                            </p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400" id="payment-deposit-note"></p>
                        </div>
                    </div>

                    <form id="payment-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $amount }}" id="amount-input">
                        @if($appointmentId)
                            <input type="hidden" name="appointment_id" value="{{ $appointmentId }}">
                        @endif

                        <!-- Payment Methods Section -->
                        <div>
                            <label class="mb-4 block text-base font-semibold text-gray-900 dark:text-white">
                                Choose Payment Method
                            </label>
                            <div id="payment-methods-container" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                <!-- Loading state -->
                                <div class="col-span-full flex items-center justify-center py-8">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-6 w-6 animate-spin rounded-full border-2 border-blue-600 border-t-transparent"></div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Loading payment methods...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Details Section -->
                        <div id="card-details" class="hidden">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                                <h4 class="mb-4 flex items-center text-lg font-semibold text-gray-900 dark:text-white">
                                    <i class="fas fa-credit-card mr-3 text-blue-600 dark:text-blue-400"></i>
                                    Card Information
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Card Number
                                        </label>
                                        <div class="relative mt-1">
                                            <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" 
                                                class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400" 
                                                maxlength="19">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <i class="fas fa-credit-card text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="card_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Expiry Date
                                            </label>
                                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" 
                                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400" 
                                                maxlength="5">
                                        </div>
                                        <div>
                                            <label for="card_cvc" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                CVC
                                            </label>
                                            <input type="text" id="card_cvc" name="card_cvc" placeholder="123" 
                                                class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400" 
                                                maxlength="3">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="cardholder_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Cardholder Name
                                        </label>
                                        <input type="text" id="cardholder_name" name="cardholder_name" placeholder="John Doe" 
                                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-400">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Details Section -->
                        <div id="bank-details" class="hidden">
                            <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-900/20">
                                <h4 class="mb-4 flex items-center text-lg font-semibold text-blue-900 dark:text-blue-300">
                                    <i class="fas fa-university mr-3"></i>
                                    Bank Transfer Instructions
                                </h4>
                                <div class="rounded-lg bg-white p-4 dark:bg-gray-800">
                                    <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                                        Please transfer the exact amount to the following account and use the reference number below:
                                    </p>
                                    <dl class="space-y-3">
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <dt class="font-medium text-gray-900 dark:text-gray-100">Account Name:</dt>
                                            <dd class="font-mono text-sm text-gray-700 dark:text-gray-300 sm:text-right">MediApp Healthcare Ltd</dd>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <dt class="font-medium text-gray-900 dark:text-gray-100">Account Number:</dt>
                                            <dd class="font-mono text-sm text-gray-700 dark:text-gray-300 sm:text-right">12345678</dd>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between">
                                            <dt class="font-medium text-gray-900 dark:text-gray-100">Sort Code:</dt>
                                            <dd class="font-mono text-sm text-gray-700 dark:text-gray-300 sm:text-right">01-02-03</dd>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between border-t pt-3 dark:border-gray-700">
                                            <dt class="font-medium text-gray-900 dark:text-gray-100">Reference:</dt>
                                            <dd class="font-mono text-sm font-bold text-blue-600 dark:text-blue-400 sm:text-right" id="payment-reference">PAYMENT-{{ time() }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="mt-4 rounded-lg bg-amber-50 p-3 dark:bg-amber-900/20">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle mr-2 mt-0.5 text-amber-600 dark:text-amber-400"></i>
                                        <p class="text-sm text-amber-800 dark:text-amber-300">
                                            <strong>Important:</strong> Please include the reference number in your transfer to ensure proper processing.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance Details Section -->
                        <div id="insurance-details" class="hidden">
                            <div class="rounded-xl border border-green-200 bg-green-50 p-6 dark:border-green-800 dark:bg-green-900/20">
                                <h4 class="mb-4 flex items-center text-lg font-semibold text-green-900 dark:text-green-300">
                                    <i class="fas fa-shield-alt mr-3"></i>
                                    Insurance Information
                                </h4>
                                <div class="space-y-4">
                                    <div>
                                        <label for="insurance_provider" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Insurance Provider
                                        </label>
                                        <input type="text" id="insurance_provider" name="insurance_provider" placeholder="Insurance Company Name" 
                                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-green-400">
                                    </div>
                                    <div>
                                        <label for="policy_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Policy Number
                                        </label>
                                        <input type="text" id="policy_number" name="policy_number" placeholder="Policy Number" 
                                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-500 shadow-sm transition-colors focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-green-400">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Section -->
                        <div id="payment-messages" class="hidden rounded-lg p-4"></div>
                    </form>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/50 sm:flex sm:flex-row-reverse sm:px-8">
                <button type="button" id="submit-payment" 
                    class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white shadow-lg transition-all hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 sm:ml-3 sm:w-auto">
                    <i class="fas fa-lock mr-2"></i>
                    Secure Payment
                </button>
                <button type="button" 
                    class="close-modal mt-3 inline-flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-700 sm:mt-0 sm:w-auto">
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

        // Close modal when clicking backdrop
        paymentModal.addEventListener('click', (e) => {
            if (e.target === paymentModal) {
                paymentModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // Prevent modal from closing when clicking inside the modal content
        const modalContent = paymentModal.querySelector('.relative.w-full');
        modalContent?.addEventListener('click', (e) => {
            e.stopPropagation();
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
                methodCard.className = 'payment-method-card group relative cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-gray-800 dark:hover:border-blue-600';
                methodCard.dataset.method = key;
                
                let icon = 'fa-money-bill-wave';
                let bgColor = 'bg-gray-100 dark:bg-gray-700';
                let iconColor = 'text-gray-600 dark:text-gray-400';
                
                if (key === 'card') {
                    icon = 'fa-credit-card';
                    bgColor = 'bg-blue-100 dark:bg-blue-900/30';
                    iconColor = 'text-blue-600 dark:text-blue-400';
                } else if (key === 'online') {
                    icon = 'fa-globe';
                    bgColor = 'bg-purple-100 dark:bg-purple-900/30';
                    iconColor = 'text-purple-600 dark:text-purple-400';
                } else if (key === 'insurance') {
                    icon = 'fa-shield-alt';
                    bgColor = 'bg-green-100 dark:bg-green-900/30';
                    iconColor = 'text-green-600 dark:text-green-400';
                } else if (key === 'bank_transfer') {
                    icon = 'fa-university';
                    bgColor = 'bg-orange-100 dark:bg-orange-900/30';
                    iconColor = 'text-orange-600 dark:text-orange-400';
                }
                
                methodCard.innerHTML = `
                    <div class="flex flex-col items-center space-y-3 text-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full ${bgColor} transition-colors group-hover:scale-110">
                            <i class="fas ${icon} text-lg ${iconColor}"></i>
                        </div>
                        <div>
                            <span class="block font-semibold text-gray-900 dark:text-gray-100">${label}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Click to select</span>
                        </div>
                    </div>
                    <div class="absolute inset-0 rounded-xl border-2 border-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                `;
                
                methodCard.addEventListener('click', () => selectPaymentMethod(key));
                paymentMethodsContainer.appendChild(methodCard);
            });
        }

        // Select payment method
        function selectPaymentMethod(method) {
            // Reset selection
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50', 'dark:border-blue-500', 'dark:bg-blue-900/30', 'ring-2', 'ring-blue-200', 'dark:ring-blue-800');
                card.classList.add('border-gray-200', 'dark:border-gray-700');
            });
            
            // Select the clicked one
            const selectedCard = document.querySelector(`.payment-method-card[data-method="${method}"]`);
            if (selectedCard) {
                selectedCard.classList.remove('border-gray-200', 'dark:border-gray-700');
                selectedCard.classList.add('border-blue-500', 'bg-blue-50', 'dark:border-blue-500', 'dark:bg-blue-900/30', 'ring-2', 'ring-blue-200', 'dark:ring-blue-800');
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
            
            // Clean and validate card details if card payment
            if (paymentMethod === 'card') {
                const cardNumber = formData.get('card_number').replace(/\s/g, ''); // Remove spaces for validation
                
                // Update the form data with cleaned card number (no spaces)
                formData.set('card_number', cardNumber);
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
            submitPaymentBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing Payment...';
            submitPaymentBtn.classList.add('opacity-75', 'cursor-not-allowed');
            
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
                    submitPaymentBtn.innerHTML = '<i class="fas fa-lock mr-2"></i> Secure Payment';
                    submitPaymentBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                }
            } catch (error) {
                console.error('Payment error:', error);
                showMessage('An error occurred while processing payment', 'error');
                submitPaymentBtn.disabled = false;
                submitPaymentBtn.innerHTML = '<i class="fas fa-lock mr-2"></i> Secure Payment';
                submitPaymentBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        });

        // Show message
        function showMessage(message, type = 'success') {
            paymentMessages.classList.remove('hidden');
            paymentMessages.className = 'rounded-lg p-4';
            
            if (type === 'success') {
                paymentMessages.classList.add('bg-green-50', 'border', 'border-green-200', 'text-green-800', 'dark:bg-green-900/30', 'dark:border-green-800', 'dark:text-green-300');
                paymentMessages.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/50">
                            <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">${message}</p>
                        </div>
                    </div>
                `;
            } else {
                paymentMessages.classList.add('bg-red-50', 'border', 'border-red-200', 'text-red-800', 'dark:bg-red-900/30', 'dark:border-red-800', 'dark:text-red-300');
                paymentMessages.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/50">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">${message}</p>
                        </div>
                    </div>
                `;
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

        // Format card number with spaces for better readability
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput?.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '').substring(0, 16);
            // Add spaces every 4 digits for display
            let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = formattedValue;
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
