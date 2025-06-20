@extends('layouts.app')

@section('title', 'Prepare Order - Medi App')

@section('content')
<x-pharmacy-navigation />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li>
                    <a href="{{ route('pharmacy.orders.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">
                        Orders
                    </a>
                </li>
                <li>
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    <a href="{{ route('pharmacy.orders.show', $order) }}" class="hover:text-gray-700 dark:hover:text-gray-300">
                        {{ $order->order_number }}
                    </a>
                </li>
                <li>
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    <span class="text-gray-900 dark:text-white font-medium">Prepare</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">Prepare Order</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Set pricing and availability for each medication</p>
    </div>

    <form action="{{ route('pharmacy.orders.update-items', $order) }}" method="POST" id="prepare-form">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-pills mr-2 text-blue-500"></i>
                            Medication Items
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($order->items as $index => $item)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 medication-item" data-index="{{ $index }}">
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                    
                                    <!-- Medication Header -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ $item->medication_name }}
                                            </h3>
                                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div><strong>Dosage:</strong> {{ $item->dosage }}</div>
                                                <div><strong>Frequency:</strong> {{ $item->frequency }}</div>
                                                <div><strong>Duration:</strong> {{ $item->duration }}</div>
                                            </div>
                                            @if($item->instructions)
                                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <strong>Instructions:</strong> {{ $item->instructions }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Availability Toggle -->
                                        <div class="ml-4">
                                            <label class="inline-flex items-center">
                                                <input type="hidden" name="items[{{ $index }}][is_available]" value="0">
                                                <input type="checkbox" 
                                                       name="items[{{ $index }}][is_available]" 
                                                       value="1"
                                                       {{ $item->is_available ? 'checked' : '' }}
                                                       class="availability-toggle rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                       onchange="toggleItemAvailability({{ $index }})">
                                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Available</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Quantity and Pricing -->
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Prescribed Qty
                                            </label>
                                            <input type="text" 
                                                   value="{{ $item->quantity_prescribed }}" 
                                                   readonly
                                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Dispensed Qty <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   name="items[{{ $index }}][quantity_dispensed]" 
                                                   value="{{ $item->quantity_dispensed }}"
                                                   min="0"
                                                   max="{{ $item->quantity_prescribed }}"
                                                   required
                                                   class="quantity-input w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                   data-index="{{ $index }}"
                                                   onchange="calculateItemTotal({{ $index }})">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Unit Price ($) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   name="items[{{ $index }}][unit_price]" 
                                                   value="{{ $item->unit_price }}"
                                                   min="0"
                                                   step="0.01"
                                                   required
                                                   class="unit-price-input w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                   data-index="{{ $index }}"
                                                   onchange="calculateItemTotal({{ $index }})">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Total Price ($)
                                            </label>
                                            <input type="text" 
                                                   value="{{ number_format($item->total_price, 2) }}"
                                                   readonly
                                                   class="item-total w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 dark:bg-gray-600"
                                                   data-index="{{ $index }}">
                                        </div>
                                    </div>

                                    <!-- Pharmacy Notes -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Notes (Optional)
                                        </label>
                                        <textarea name="items[{{ $index }}][pharmacy_notes]" 
                                                  rows="2"
                                                  placeholder="Add notes about availability, substitutions, etc..."
                                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $item->pharmacy_notes }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-calculator mr-2 text-green-500"></i>
                            Order Summary
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                            <span class="text-gray-900 dark:text-white subtotal-display">$0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tax (10%)</span>
                            <span class="text-gray-900 dark:text-white tax-display">$0.00</span>
                        </div>
                        @if($order->delivery_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Delivery Fee</span>
                                <span class="text-gray-900 dark:text-white">${{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                        @endif
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                            <div class="flex justify-between font-semibold text-lg">
                                <span class="text-gray-900 dark:text-white">Total</span>
                                <span class="text-gray-900 dark:text-white total-display">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Patient Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Name:</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $order->patient->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Email:</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $order->patient->email }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Delivery:</span>
                            <p class="text-sm text-gray-900 dark:text-white capitalize">{{ $order->delivery_method }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                            General Notes
                        </h3>
                    </div>
                    <div class="p-6">
                        <textarea name="pharmacy_notes" 
                                  rows="4"
                                  placeholder="Add general notes about this order..."
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $order->pharmacy_notes }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md transition-colors duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Order & Continue Preparing
                        </button>
                        
                        <a href="{{ route('pharmacy.orders.show', $order) }}" 
                           class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-md transition-colors duration-200 font-medium text-center block">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Order Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const taxRate = 0.10; // 10% tax rate
    const deliveryFee = {{ $order->delivery_fee }};

    function toggleItemAvailability(index) {
        const checkbox = document.querySelector(`input[name="items[${index}][is_available]"][type="checkbox"]`);
        const itemDiv = document.querySelector(`.medication-item[data-index="${index}"]`);
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity_dispensed]"]`);
        const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
        
        if (checkbox.checked) {
            itemDiv.classList.remove('opacity-50');
            quantityInput.disabled = false;
            priceInput.disabled = false;
        } else {
            itemDiv.classList.add('opacity-50');
            quantityInput.disabled = true;
            quantityInput.value = 0;
            priceInput.disabled = true;
            calculateItemTotal(index);
        }
        
        calculateOrderTotal();
    }

    function calculateItemTotal(index) {
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity_dispensed]"]`);
        const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
        const totalDisplay = document.querySelector(`.item-total[data-index="${index}"]`);
        
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(priceInput.value) || 0;
        const total = quantity * unitPrice;
        
        totalDisplay.value = total.toFixed(2);
        calculateOrderTotal();
    }

    function calculateOrderTotal() {
        let subtotal = 0;
        
        // Calculate subtotal from all items
        document.querySelectorAll('.item-total').forEach(function(input) {
            subtotal += parseFloat(input.value) || 0;
        });
        
        const taxAmount = subtotal * taxRate;
        const totalAmount = subtotal + taxAmount + deliveryFee;
        
        // Update displays
        document.querySelector('.subtotal-display').textContent = '$' + subtotal.toFixed(2);
        document.querySelector('.tax-display').textContent = '$' + taxAmount.toFixed(2);
        document.querySelector('.total-display').textContent = '$' + totalAmount.toFixed(2);
    }

    // Initialize calculations on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate initial totals for each item
        @foreach($order->items as $index => $item)
            calculateItemTotal({{ $index }});
        @endforeach
        
        // Set initial availability states
        @foreach($order->items as $index => $item)
            @if(!$item->is_available)
                toggleItemAvailability({{ $index }});
            @endif
        @endforeach
    });

    // Form validation
    document.getElementById('prepare-form').addEventListener('submit', function(e) {
        let hasAvailableItems = false;
        
        document.querySelectorAll('.availability-toggle').forEach(function(checkbox) {
            if (checkbox.checked) {
                hasAvailableItems = true;
            }
        });
        
        if (!hasAvailableItems) {
            e.preventDefault();
            alert('At least one medication must be available to continue.');
            return false;
        }
    });
</script>
@endsection
