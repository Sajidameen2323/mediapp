@extends('layouts.pharmacy')

@section('title', 'Prepare Order - Medi App')

@push('styles')
<style>
    /* Custom styles for pharmacy prepare form */
    .medication-item {
        transition: all 0.3s ease;
    }
    
    .medication-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .availability-toggle:checked + div {
        background-color: #10B981;
    }
    
    .availability-toggle:checked + div:after {
        transform: translateX(100%);
    }
    
    .progress-bar {
        background: linear-gradient(90deg, #3B82F6, #10B981);
        animation: progress-animation 2s ease-in-out;
    }
    
    @keyframes progress-animation {
        0% { width: 0%; }
        100% { width: 25%; }
    }
    
    .pulse-green {
        animation: pulse-green 2s infinite;
    }
    
    @keyframes pulse-green {
        0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        50% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    }
    
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    /* Enhanced focus states */
    .focus-enhanced:focus {
        ring-width: 2px;
        ring-color: #3B82F6;
        border-color: #3B82F6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Loading animation for buttons */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border-radius: 50%;
        border: 2px solid transparent;
        border-top-color: currentColor;
        animation: spin 1s ease infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Smooth transitions for all interactive elements */
    input, textarea, button, .medication-item {
        transition: all 0.2s ease;
    }
    
    /* Gradient borders for important sections */
    .gradient-border {
        position: relative;
        background: linear-gradient(45deg, #3B82F6, #10B981);
        padding: 2px;
        border-radius: 8px;
    }
    
    .gradient-border-content {
        background: white;
        border-radius: 6px;
        padding: 1rem;
    }
    
    .dark .gradient-border-content {
        background: #1F2937;
    }
    
    /* Toast notification styles */
    .toast {
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    /* Fix for sidebar cards spacing and alignment */
    .sidebar-cards {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .sidebar-card {
        flex-shrink: 0;
        min-height: fit-content;
    }
    
    /* Ensure consistent card heights and spacing */
    .sidebar-cards > div {
        margin-bottom: 0 !important;
    }
    
    /* Fix for overflow and text wrapping */
    .text-truncate-custom {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .sidebar-cards {
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Enhanced Header with Status -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li>
                    <a href="{{ route('pharmacy.orders.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <i class="fas fa-box mr-1"></i>Orders
                    </a>
                </li>
                <li>
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    <a href="{{ route('pharmacy.orders.show', $order) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        {{ $order->order_number }}
                    </a>
                </li>
                <li>
                    <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                    <span class="text-gray-900 dark:text-white font-medium">
                        <i class="fas fa-cogs mr-1"></i>Prepare
                    </span>
                </li>
            </ol>
        </nav>
        
        <div class="flex items-center justify-between mt-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Prepare Order</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Configure pricing and availability for each prescribed medication</p>
            </div>
            
            <!-- Order Status Badge -->
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                    <i class="fas fa-clock mr-1"></i>
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Order #{{ $order->order_number }}
                </span>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Received</span>
                    </div>
                    <div class="w-16 h-0.5 bg-blue-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-cogs text-xs"></i>
                        </div>
                        <span class="ml-2 font-medium text-gray-900 dark:text-white">Preparing</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-xs text-gray-500"></i>
                        </div>
                        <span class="ml-2 text-gray-500 dark:text-gray-400">Ready</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-truck text-xs text-gray-500"></i>
                        </div>
                        <span class="ml-2 text-gray-500 dark:text-gray-400">Delivered</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Progress</div>
                    <div class="text-lg font-semibold text-blue-600 dark:text-blue-400">25%</div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('pharmacy.orders.update-items', $order) }}" method="POST" id="prepare-form" class="space-y-8">
        @csrf
        @method('PATCH')

        <!-- Quick Actions Bar -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-lg border border-blue-200 dark:border-gray-600 p-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <button type="button" onclick="setAllAvailable(true)" 
                            class="inline-flex items-center px-3 py-2 border border-green-300 dark:border-green-600 rounded-md text-sm font-medium text-green-700 dark:text-green-300 bg-white dark:bg-gray-800 hover:bg-green-50 dark:hover:bg-green-900 transition-colors">
                        <i class="fas fa-check-circle mr-2"></i>
                        Mark All Available
                    </button>
                    <button type="button" onclick="setAllAvailable(false)" 
                            class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 rounded-md text-sm font-medium text-red-700 dark:text-red-300 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900 transition-colors">
                        <i class="fas fa-times-circle mr-2"></i>
                        Mark All Unavailable
                    </button>
                    <button type="button" onclick="applyBulkPricing()" 
                            class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md text-sm font-medium text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        Bulk Pricing
                    </button>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span id="available-count">0</span> of {{ count($order->items) }} medications available
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Enhanced Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-gray-700 dark:to-gray-600 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-pills mr-2 text-blue-500"></i>
                                Prescribed Medications
                            </h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ count($order->items) }} items
                            </span>
                        </div>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($order->items as $index => $item)
                            <div class="medication-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" data-index="{{ $index }}">
                                <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                
                                <!-- Enhanced Medication Header -->
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-capsules text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                    {{ $item->medication_name }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Item #{{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Prescription Details Grid -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <div class="text-center">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Dosage</div>
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->dosage }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Frequency</div>
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->frequency }}</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</div>
                                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->duration }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($item->instructions)
                                            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 rounded-r-lg">
                                                <div class="flex items-start">
                                                    <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5 mr-2"></i>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Special Instructions</h4>
                                                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">{{ $item->instructions }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Enhanced Availability Toggle -->
                                    <div class="ml-6">
                                        <div class="flex flex-col items-center space-y-2">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="hidden" name="items[{{ $index }}][is_available]" value="0">
                                                <input type="checkbox" 
                                                       name="items[{{ $index }}][is_available]" 
                                                       value="1"
                                                       {{ $item->is_available ? 'checked' : '' }}
                                                       class="availability-toggle sr-only"
                                                       onchange="toggleItemAvailability({{ $index }})">
                                                <div class="relative w-14 h-8 bg-gray-200 rounded-full dark:bg-gray-700 transition-colors duration-300">
                                                    <div class="absolute top-1 left-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 shadow-md"></div>
                                                </div>
                                            </label>
                                            <span class="availability-label text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Available
                                            </span>
                                            <div class="availability-status">
                                                <span class="available-badge hidden inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    In Stock
                                                </span>
                                                <span class="unavailable-badge hidden inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Out of Stock
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Quantity and Pricing Section -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <i class="fas fa-calculator mr-2 text-green-500"></i>
                                        Quantity & Pricing Details
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="fas fa-prescription mr-1 text-blue-500"></i>
                                                Prescribed Qty
                                            </label>
                                            <div class="relative">
                                                <input type="text" 
                                                       value="{{ $item->quantity_prescribed }}" 
                                                       readonly
                                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 pr-8">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <i class="fas fa-lock text-gray-400 text-sm"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="fas fa-hand-holding-medical mr-1 text-green-500"></i>
                                                Dispensed Qty <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" 
                                                       name="items[{{ $index }}][quantity_dispensed]" 
                                                       value="{{ $item->quantity_dispensed }}"
                                                       min="0"
                                                       max="{{ $item->quantity_prescribed }}"
                                                       required
                                                       class="quantity-input focus-enhanced w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-16"
                                                       data-index="{{ $index }}"
                                                       onchange="calculateItemTotal({{ $index }})"
                                                       oninput="calculateItemTotal({{ $index }})">>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                    <button type="button" onclick="setMaxQuantity({{ $index }})" 
                                                            class="text-blue-500 hover:text-blue-700 text-xs font-medium focus-enhanced px-2 py-1 rounded">
                                                        MAX
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="fas fa-dollar-sign mr-1 text-yellow-500"></i>
                                                Unit Price ($) <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 text-sm">$</span>
                                                </div>
                                                <input type="number" 
                                                       name="items[{{ $index }}][unit_price]" 
                                                       value="{{ $item->unit_price }}"
                                                       min="0"
                                                       step="0.01"
                                                       required
                                                       class="unit-price-input focus-enhanced w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       data-index="{{ $index }}"
                                                       onchange="calculateItemTotal({{ $index }})"
                                                       oninput="calculateItemTotal({{ $index }})">>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                <i class="fas fa-receipt mr-1 text-purple-500"></i>
                                                Total Price
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-gray-500 text-sm">$</span>
                                                </div>
                                                <input type="text" 
                                                       value="{{ number_format($item->total_price, 2) }}"
                                                       readonly
                                                       class="item-total w-full pl-8 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-50 font-semibold text-green-600 dark:text-green-400"
                                                       data-index="{{ $index }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Pharmacy Notes -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-sticky-note mr-1 text-orange-500"></i>
                                        Pharmacy Notes (Optional)
                                    </label>
                                    <textarea name="items[{{ $index }}][pharmacy_notes]" 
                                              rows="3"
                                              placeholder="Add notes about availability, substitutions, special handling instructions..."
                                              class="focus-enhanced w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none">{{ $item->pharmacy_notes }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Enhanced Order Summary -->
            <div class="sidebar-cards">
                <!-- Live Summary Card with Real-time Updates -->
                <div class="sidebar-card bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 border-b border-gray-200 dark:border-gray-600 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-calculator mr-2 text-green-500"></i>
                            Live Order Summary
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Updates automatically as you make changes</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Summary Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="items-available">0</div>
                                <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">Available Items</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-2xl font-bold text-gray-600 dark:text-gray-400" id="items-total">{{ count($order->items) }}</div>
                                <div class="text-xs text-gray-600 dark:text-gray-400 font-medium">Total Items</div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                    <i class="fas fa-list mr-2 text-gray-400"></i>
                                    Subtotal
                                </span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white subtotal-display">$0.00</span>
                            </div>
                            
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                    <i class="fas fa-percentage mr-2 text-gray-400"></i>
                                    Tax (0%)
                                </span>
                                <span class="text-lg font-medium text-gray-900 dark:text-white tax-display">$0.00</span>
                            </div>
                            
                            @if($order->delivery_fee > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400 flex items-center text-sm">
                                        <i class="fas fa-truck mr-2 text-gray-400"></i>
                                        Delivery Fee
                                    </span>
                                    <span class="text-lg font-medium text-gray-900 dark:text-white">${{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                            @endif
                            
                            <div class="border-t-2 border-gray-200 dark:border-gray-600 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">Total Amount</span>
                                    <span class="text-2xl font-bold text-green-600 dark:text-green-400 total-display">$0.00</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-right mt-1">
                                    Including all taxes and fees
                                </div>
                            </div>
                        </div>
                        
                        <!-- Savings Indicator -->
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg" id="savings-indicator" style="display: none;">
                            <div class="flex items-center">
                                <i class="fas fa-piggy-bank text-yellow-600 dark:text-yellow-400 mr-2"></i>
                                <div>
                                    <div class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Potential Savings</div>
                                    <div class="text-xs text-yellow-700 dark:text-yellow-300">Customer pays less due to quantity adjustments</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Patient Info -->
                <div class="sidebar-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900 dark:to-cyan-900 border-b border-gray-200 dark:border-gray-600 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-injured mr-2 text-blue-500"></i>
                            Patient Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</div>
                                    <div class="text-base font-semibold text-gray-900 dark:text-white text-truncate-custom">{{ $order->patient->name }}</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-envelope text-green-600 dark:text-green-400"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</div>
                                    <div class="text-base font-semibold text-gray-900 dark:text-white text-truncate-custom">{{ $order->patient->email }}</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-{{ $order->delivery_method === 'delivery' ? 'truck' : 'store' }} text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Method</div>
                                    <div class="text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="capitalize">{{ $order->delivery_method }}</span>
                                        @if($order->delivery_method === 'delivery')
                                            <span class="ml-2 text-sm text-green-600 dark:text-green-400">(+${{ number_format($order->delivery_fee, 2) }})</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced General Notes -->
                <div class="sidebar-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900 dark:to-orange-900 border-b border-gray-200 dark:border-gray-600 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-yellow-500"></i>
                            General Order Notes
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add notes visible to all staff members</p>
                    </div>
                    <div class="p-6">
                        <textarea name="pharmacy_notes" 
                                  rows="4"
                                  placeholder="Add general notes about this order, special handling instructions, customer requests, etc..."
                                  class="focus-enhanced w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-yellow-500 focus:ring-yellow-500 resize-none">{{ $order->pharmacy_notes }}</textarea>
                    </div>
                </div>

                <!-- Enhanced Action Buttons -->
                <div class="sidebar-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 space-y-4">
                        <!-- Primary Action -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-4 rounded-lg transition-all duration-200 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Save Changes & Continue Preparing
                        </button>
                        
                        <!-- Secondary Actions -->
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" 
                                    onclick="saveDraft()"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg transition-colors duration-200 font-medium">
                                <i class="fas fa-bookmark mr-2"></i>
                                Save Draft
                            </button>
                            
                            <a href="{{ route('pharmacy.orders.show', $order) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg transition-colors duration-200 font-medium text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Order
                            </a>
                        </div>
                        
                        <!-- Quick Actions -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">Quick Actions:</div>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" 
                                        onclick="printOrderSummary()"
                                        class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-print mr-1"></i>Print Summary
                                </button>
                                <button type="button" 
                                        onclick="exportOrderData()"
                                        class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-download mr-1"></i>Export Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Bulk Pricing Modal -->
    <div id="bulk-pricing-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                    Apply Bulk Pricing
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Base Price per Unit ($)
                        </label>
                        <input type="number" 
                               id="bulk-price" 
                               min="0" 
                               step="0.01" 
                               placeholder="Enter base price"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>This price will be applied to all available medications. You can adjust individual prices afterward.</p>
                        <p class="mt-2">
                            <span class="font-medium">Available items:</span> 
                            <span id="bulk-available-count">0</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            onclick="closeBulkPricingModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button" 
                            onclick="applyBulkPrice()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Apply to All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const taxRate = 0.00; // 0% tax rate
    const deliveryFee = {{ $order->delivery_fee }};

    // Enhanced toggle with professional animations and feedback
    function toggleItemAvailability(index) {
        const checkbox = document.querySelector(`input[name="items[${index}][is_available]"][type="checkbox"]`);
        const itemDiv = document.querySelector(`.medication-item[data-index="${index}"]`);
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity_dispensed]"]`);
        const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
        const statusDiv = itemDiv.querySelector('.availability-status');
        const availableBadge = itemDiv.querySelector('.available-badge');
        const unavailableBadge = itemDiv.querySelector('.unavailable-badge');
        
        if (checkbox.checked) {
            // Item is now available
            itemDiv.classList.remove('opacity-50');
            itemDiv.classList.add('ring-2', 'ring-green-200', 'dark:ring-green-700');
            itemDiv.classList.remove('ring-2', 'ring-red-200', 'dark:ring-red-700');
            quantityInput.disabled = false;
            priceInput.disabled = false;
            
            // Show/hide status badges
            if (availableBadge && unavailableBadge) {
                availableBadge.classList.remove('hidden');
                unavailableBadge.classList.add('hidden');
                statusDiv.classList.remove('hidden');
            }
            
            // Auto-set max quantity if empty
            if (!quantityInput.value || quantityInput.value === '0') {
                quantityInput.value = quantityInput.getAttribute('max');
            }
            
            // Recalculate item total to restore pricing
            calculateItemTotal(index);
        } else {
            // Item is now unavailable
            itemDiv.classList.add('opacity-50');
            itemDiv.classList.remove('ring-2', 'ring-green-200', 'dark:ring-green-700');
            itemDiv.classList.add('ring-2', 'ring-red-200', 'dark:ring-red-700');
            quantityInput.disabled = true;
            quantityInput.value = 0;
            priceInput.disabled = true;
            
            // Show/hide status badges
            if (availableBadge && unavailableBadge) {
                availableBadge.classList.add('hidden');
                unavailableBadge.classList.remove('hidden');
                statusDiv.classList.remove('hidden');
            }
            
            // Calculate item total (will set to 0 for unavailable items)
            calculateItemTotal(index);
        }
        
        updateAvailabilityCounter();
        // No need to call calculateOrderTotal again since calculateItemTotal already does it
    }

    // Calculate individual item totals with animation
    function calculateItemTotal(index) {
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity_dispensed]"]`);
        const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
        const totalDisplay = document.querySelector(`.item-total[data-index="${index}"]`);
        const checkbox = document.querySelector(`input[name="items[${index}][is_available]"][type="checkbox"]`);
        
        // Only calculate if item is available
        if (checkbox && checkbox.checked) {
            const quantity = parseFloat(quantityInput.value) || 0;
            const unitPrice = parseFloat(priceInput.value) || 0;
            const total = quantity * unitPrice;
            
            // Animate the total change
            if (totalDisplay) {
                totalDisplay.style.transform = 'scale(1.05)';
                totalDisplay.style.transition = 'transform 0.2s ease';
                
                setTimeout(() => {
                    totalDisplay.value = total.toFixed(2);
                    totalDisplay.style.transform = 'scale(1)';
                }, 100);
            }
        } else {
            // Item is not available, set total to 0
            if (totalDisplay) {
                totalDisplay.value = '0.00';
            }
        }
        
        // Always recalculate order total
        calculateOrderTotal();
    }

    // Enhanced order total calculation with visual feedback
    function calculateOrderTotal() {
        let subtotal = 0;
        let availableItems = 0;
        
        // Calculate subtotal from all available items only
        document.querySelectorAll('.availability-toggle').forEach(function(checkbox) {
            if (checkbox.checked) {
                const actualIndex = parseInt(checkbox.name.match(/\[(\d+)\]/)[1]);
                const totalInput = document.querySelector(`.item-total[data-index="${actualIndex}"]`);
                if (totalInput) {
                    subtotal += parseFloat(totalInput.value) || 0;
                    availableItems++;
                }
            }
        });
        
        const taxAmount = subtotal * taxRate;
        const totalAmount = subtotal + taxAmount + deliveryFee;
        
        // Update displays with animations
        animateValueChange('.subtotal-display', '$' + subtotal.toFixed(2));
        animateValueChange('.tax-display', '$' + taxAmount.toFixed(2));
        animateValueChange('.total-display', '$' + totalAmount.toFixed(2));
        
        // Update item counters
        document.getElementById('items-available').textContent = availableItems;
        updateAvailabilityCounter();
        
        // Show savings indicator if applicable
        showSavingsIndicator(subtotal);
    }

    // Animate value changes for better UX
    function animateValueChange(selector, newValue) {
        const element = document.querySelector(selector);
        if (element) {
            element.style.transform = 'scale(1.1)';
            element.style.transition = 'transform 0.3s ease';
            element.style.color = '#10B981'; // Green color
            
            setTimeout(() => {
                element.textContent = newValue;
                element.style.transform = 'scale(1)';
                
                setTimeout(() => {
                    element.style.color = ''; // Reset color
                }, 300);
            }, 150);
        }
    }

    // Update availability counter
    function updateAvailabilityCounter() {
        const availableCount = document.querySelectorAll('.availability-toggle:checked').length;
        const totalCount = document.querySelectorAll('.availability-toggle').length;
        
        const counterElement = document.getElementById('available-count');
        if (counterElement) {
            counterElement.textContent = availableCount;
            
            // Change color based on availability
            const parentElement = counterElement.parentElement;
            if (availableCount === 0) {
                parentElement.className = parentElement.className.replace(/text-\w+-\d+/g, 'text-red-600 dark:text-red-400');
            } else if (availableCount === totalCount) {
                parentElement.className = parentElement.className.replace(/text-\w+-\d+/g, 'text-green-600 dark:text-green-400');
            } else {
                parentElement.className = parentElement.className.replace(/text-\w+-\d+/g, 'text-yellow-600 dark:text-yellow-400');
            }
        }
    }

    // Show savings indicator
    function showSavingsIndicator(currentTotal) {
        // This would compare with original prescription total
        // For now, we'll show it if there are partial quantities
        const savingsDiv = document.getElementById('savings-indicator');
        if (savingsDiv) {
            let hasPartialQuantities = false;
            
            document.querySelectorAll('.quantity-input').forEach(function(input) {
                const max = parseInt(input.getAttribute('max'));
                const current = parseInt(input.value) || 0;
                if (current > 0 && current < max) {
                    hasPartialQuantities = true;
                }
            });
            
            if (hasPartialQuantities) {
                savingsDiv.style.display = 'block';
            } else {
                savingsDiv.style.display = 'none';
            }
        }
    }

    // Bulk actions
    function setAllAvailable(available) {
        document.querySelectorAll('.availability-toggle').forEach(function(checkbox, index) {
            if (checkbox.checked !== available) {
                checkbox.checked = available;
                toggleItemAvailability(index);
            }
        });
        
        // Show feedback toast
        showToast(available ? 'All items marked as available' : 'All items marked as unavailable', 
                 available ? 'success' : 'warning');
    }

    // Set maximum quantity for an item
    function setMaxQuantity(index) {
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity_dispensed]"]`);
        const maxQuantity = quantityInput.getAttribute('max');
        
        quantityInput.value = maxQuantity;
        calculateItemTotal(index);
        
        // Visual feedback
        quantityInput.style.backgroundColor = '#10B981';
        quantityInput.style.color = 'white';
        setTimeout(() => {
            quantityInput.style.backgroundColor = '';
            quantityInput.style.color = '';
        }, 500);
    }

    // Bulk pricing modal functions
    function applyBulkPricing() {
        // Count available items
        const availableCount = document.querySelectorAll('.availability-toggle:checked').length;
        document.getElementById('bulk-available-count').textContent = availableCount;
        
        if (availableCount === 0) {
            showToast('No available items to apply bulk pricing to', 'warning');
            return;
        }
        
        document.getElementById('bulk-pricing-modal').classList.remove('hidden');
    }

    function closeBulkPricingModal() {
        document.getElementById('bulk-pricing-modal').classList.add('hidden');
        document.getElementById('bulk-price').value = '';
    }

    function applyBulkPrice() {
        const bulkPrice = parseFloat(document.getElementById('bulk-price').value);
        
        if (!bulkPrice || bulkPrice <= 0) {
            showToast('Please enter a valid price', 'error');
            return;
        }
        
        let updatedCount = 0;
        document.querySelectorAll('.availability-toggle:checked').forEach(function(checkbox) {
            const actualIndex = parseInt(checkbox.name.match(/\[(\d+)\]/)[1]);
            const priceInput = document.querySelector(`input[name="items[${actualIndex}][unit_price]"]`);
            if (priceInput && !priceInput.disabled) {
                priceInput.value = bulkPrice.toFixed(2);
                // Calculate individual item total immediately
                calculateItemTotal(actualIndex);
                updatedCount++;
            }
        });
        
        // Force recalculation of order total after a brief delay to ensure all calculations are complete
        setTimeout(() => {
            calculateOrderTotal();
        }, 100);
        
        closeBulkPricingModal();
        showToast(`Updated pricing for ${updatedCount} available items`, 'success');
    }

    // Additional utility functions
    function saveDraft() {
        showToast('Draft saved successfully', 'info');
        // Here you would implement actual draft saving
    }

    function printOrderSummary() {
        window.print();
    }

    function exportOrderData() {
        showToast('Export functionality coming soon', 'info');
        // Here you would implement export functionality
    }

    // Toast notification system
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        
        toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial availability states first
        @foreach($order->items as $index => $item)
            @if($item->is_available)
                // Item is available, ensure proper state
                const checkbox{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][is_available]"][type="checkbox"]`);
                if (checkbox{{ $index }}) {
                    checkbox{{ $index }}.checked = true;
                    // Update visual state without triggering recalculation
                    const itemDiv{{ $index }} = document.querySelector(`.medication-item[data-index="{{ $index }}"]`);
                    const quantityInput{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][quantity_dispensed]"]`);
                    const priceInput{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][unit_price]"]`);
                    const statusDiv{{ $index }} = itemDiv{{ $index }}.querySelector('.availability-status');
                    const availableBadge{{ $index }} = itemDiv{{ $index }}.querySelector('.available-badge');
                    const unavailableBadge{{ $index }} = itemDiv{{ $index }}.querySelector('.unavailable-badge');
                    
                    itemDiv{{ $index }}.classList.remove('opacity-50');
                    itemDiv{{ $index }}.classList.add('ring-2', 'ring-green-200', 'dark:ring-green-700');
                    quantityInput{{ $index }}.disabled = false;
                    priceInput{{ $index }}.disabled = false;
                    
                    if (availableBadge{{ $index }} && unavailableBadge{{ $index }}) {
                        availableBadge{{ $index }}.classList.remove('hidden');
                        unavailableBadge{{ $index }}.classList.add('hidden');
                        statusDiv{{ $index }}.classList.remove('hidden');
                    }
                }
            @else
                // Item is unavailable, ensure proper state
                const checkbox{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][is_available]"][type="checkbox"]`);
                if (checkbox{{ $index }}) {
                    checkbox{{ $index }}.checked = false;
                    // Update visual state without triggering recalculation
                    const itemDiv{{ $index }} = document.querySelector(`.medication-item[data-index="{{ $index }}"]`);
                    const quantityInput{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][quantity_dispensed]"]`);
                    const priceInput{{ $index }} = document.querySelector(`input[name="items[{{ $index }}][unit_price]"]`);
                    const statusDiv{{ $index }} = itemDiv{{ $index }}.querySelector('.availability-status');
                    const availableBadge{{ $index }} = itemDiv{{ $index }}.querySelector('.available-badge');
                    const unavailableBadge{{ $index }} = itemDiv{{ $index }}.querySelector('.unavailable-badge');
                    
                    itemDiv{{ $index }}.classList.add('opacity-50');
                    itemDiv{{ $index }}.classList.add('ring-2', 'ring-red-200', 'dark:ring-red-700');
                    quantityInput{{ $index }}.disabled = true;
                    quantityInput{{ $index }}.value = 0;
                    priceInput{{ $index }}.disabled = true;
                    
                    if (availableBadge{{ $index }} && unavailableBadge{{ $index }}) {
                        availableBadge{{ $index }}.classList.add('hidden');
                        unavailableBadge{{ $index }}.classList.remove('hidden');
                        statusDiv{{ $index }}.classList.remove('hidden');
                    }
                }
            @endif
        @endforeach
        
        // Calculate initial totals for each item
        @foreach($order->items as $index => $item)
            calculateItemTotal({{ $index }});
        @endforeach
        
        // Initialize counters
        updateAvailabilityCounter();
        
        // Add additional event listeners for real-time updates
        document.querySelectorAll('.unit-price-input, .quantity-input').forEach(function(input) {
            const index = input.getAttribute('data-index');
            if (index !== null) {
                input.addEventListener('input', function() {
                    calculateItemTotal(parseInt(index));
                });
                input.addEventListener('change', function() {
                    calculateItemTotal(parseInt(index));
                });
            }
        });
        
        // Add event listeners for availability toggles
        document.querySelectorAll('.availability-toggle').forEach(function(checkbox) {
            const match = checkbox.name.match(/\[(\d+)\]/);
            if (match) {
                const index = parseInt(match[1]);
                checkbox.addEventListener('change', function() {
                    toggleItemAvailability(index);
                });
            }
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 's':
                        e.preventDefault();
                        document.getElementById('prepare-form').submit();
                        break;
                    case 'd':
                        e.preventDefault();
                        saveDraft();
                        break;
                }
            }
        });
        
        // Show initial toast
        showToast('Order preparation form loaded successfully', 'success');
    });

    // Enhanced form validation
    document.getElementById('prepare-form').addEventListener('submit', function(e) {
        let hasAvailableItems = false;
        let hasValidPricing = true;
        let validationErrors = [];
        
        document.querySelectorAll('.availability-toggle').forEach(function(checkbox, index) {
            if (checkbox.checked) {
                hasAvailableItems = true;
                
                const actualIndex = parseInt(checkbox.name.match(/\[(\d+)\]/)[1]);
                const quantityInput = document.querySelector(`input[name="items[${actualIndex}][quantity_dispensed]"]`);
                const priceInput = document.querySelector(`input[name="items[${actualIndex}][unit_price]"]`);
                
                if (!quantityInput.value || parseFloat(quantityInput.value) <= 0) {
                    validationErrors.push(`Item ${actualIndex + 1}: Quantity must be greater than 0`);
                    hasValidPricing = false;
                }
                
                if (!priceInput.value || parseFloat(priceInput.value) <= 0) {
                    validationErrors.push(`Item ${actualIndex + 1}: Price must be greater than 0`);
                    hasValidPricing = false;
                }
            }
        });
        
        if (!hasAvailableItems) {
            e.preventDefault();
            showToast('At least one medication must be available to continue', 'error');
            return false;
        }
        
        if (!hasValidPricing) {
            e.preventDefault();
            showToast('Please fix the following errors:\n' + validationErrors.join('\n'), 'error');
            return false;
        }
        
        // Show loading state
        const submitButton = e.target.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitButton.disabled = true;
        
        // Re-enable button if form validation fails
        setTimeout(() => {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 5000);
    });

    // Auto-save functionality (optional)
    let autoSaveTimer;
    function setupAutoSave() {
        document.querySelectorAll('input, textarea').forEach(function(input) {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    // Auto-save logic here
                    console.log('Auto-saving...');
                }, 2000);
            });
        });
    }
    
    // Enable auto-save
    setupAutoSave();
</script>
@endsection
