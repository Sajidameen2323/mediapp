@extends('layouts.patient')

@section('title', 'Payment - Order #' . $pharmacyOrder->order_number)

@section('content')


<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('patient.pharmacy-orders.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-pills"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.pharmacy-orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Pharmacy Orders</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.pharmacy-orders.show', $pharmacyOrder) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Order #{{ $pharmacyOrder->order_number }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">Payment</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Complete Payment</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Complete your payment to confirm Order #{{ $pharmacyOrder->order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('patient.pharmacy-orders.process-payment', $pharmacyOrder) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Payment Method -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-credit-card mr-2 text-blue-600 dark:text-blue-400"></i>
                                Payment Method
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <!-- Credit/Debit Card -->
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="radio" name="payment_method" value="card" checked class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-credit-card text-2xl text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Credit/Debit Card</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Pay securely with your card</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-4 h-4 border-2 border-blue-600 rounded-full bg-blue-600 flex items-center justify-center">
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Cash Payment -->
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="radio" name="payment_method" value="cash" class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-money-bill text-2xl text-green-600 dark:text-green-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Cash Payment</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Pay with cash on delivery/pickup</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-4 h-4 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Bank Transfer -->
                                <label class="flex items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="sr-only">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-university text-2xl text-purple-600 dark:text-purple-400"></i>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Bank Transfer</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Transfer directly from your bank</p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-4 h-4 border-2 border-gray-300 dark:border-gray-600 rounded-full"></div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div id="card-details" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-lock mr-2 text-blue-600 dark:text-blue-400"></i>
                                Card Details
                            </h2>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <!-- Cardholder Name -->
                            <div>
                                <label for="cardholder_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cardholder Name</label>
                                <input type="text" 
                                       id="cardholder_name" 
                                       name="cardholder_name" 
                                       value="{{ old('cardholder_name') }}"
                                       placeholder="John Doe"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                                @error('cardholder_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Card Number -->
                            <div>
                                <label for="card_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Card Number</label>
                                <input type="text" 
                                       id="card_number" 
                                       name="card_number" 
                                       value="{{ old('card_number') }}"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                                @error('card_number')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Expiry Date -->
                                <div>
                                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                                    <input type="text" 
                                           id="expiry_date" 
                                           name="expiry_date" 
                                           value="{{ old('expiry_date') }}"
                                           placeholder="MM/YY"
                                           maxlength="5"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                                    @error('expiry_date')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CVV -->
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVV</label>
                                    <input type="text" 
                                           id="cvv" 
                                           name="cvv" 
                                           value="{{ old('cvv') }}"
                                           placeholder="123"
                                           maxlength="4"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                                    @error('cvv')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Secure Payment</h3>
                                <p class="text-sm text-blue-700 dark:text-blue-300">Your payment information is encrypted and secure. We do not store your card details.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('patient.pharmacy-orders.show', $pharmacyOrder) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>Pay ${{ number_format($pharmacyOrder->total_amount, 2) }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Order Info -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-receipt mr-2 text-blue-600 dark:text-blue-400"></i>
                            Order Summary
                        </h2>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Order Number</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Pharmacy</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->pharmacy->pharmacy_name ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Items</p>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->items->count() }} medication(s)</p>
                        </div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-calculator mr-2 text-blue-600 dark:text-blue-400"></i>
                            Price Breakdown
                        </h2>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Subtotal</span>
                            <span class="text-sm text-gray-900 dark:text-white">${{ number_format($pharmacyOrder->subtotal, 2) }}</span>
                        </div>
                        @if($pharmacyOrder->delivery_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Delivery Fee</span>
                                <span class="text-sm text-gray-900 dark:text-white">${{ number_format($pharmacyOrder->delivery_fee, 2) }}</span>
                            </div>
                        @endif
                        @if($pharmacyOrder->tax_amount > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Tax</span>
                                <span class="text-sm text-gray-900 dark:text-white">${{ number_format($pharmacyOrder->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                            <div class="flex justify-between">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($pharmacyOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('card-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            // Update visual state of radio buttons
            paymentMethods.forEach(m => {
                const label = m.closest('label');
                const radioIndicator = label.querySelector('.w-4.h-4');
                if (m.checked) {
                    radioIndicator.classList.add('border-blue-600', 'bg-blue-600');
                    radioIndicator.classList.remove('border-gray-300', 'dark:border-gray-600');
                    radioIndicator.innerHTML = '<div class="w-2 h-2 bg-white rounded-full"></div>';
                } else {
                    radioIndicator.classList.remove('border-blue-600', 'bg-blue-600');
                    radioIndicator.classList.add('border-gray-300', 'dark:border-gray-600');
                    radioIndicator.innerHTML = '';
                }
            });
            
            // Show/hide card details
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });
    
    // Format card number
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function() {
        let value = this.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        this.value = formattedValue;
    });
    
    // Format expiry date
    const expiryInput = document.getElementById('expiry_date');
    expiryInput.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        this.value = value;
    });
    
    // CVV numeric only
    const cvvInput = document.getElementById('cvv');
    cvvInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>
@endsection
