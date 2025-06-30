@extends('layouts.app')

@section('title', 'Order Details - Medi App')

@section('content')
<x-pharmacy-navigation />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('pharmacy.orders.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300">
                            Orders
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right mx-2 text-gray-400"></i>
                        <span class="text-gray-900 dark:text-white font-medium">{{ $order->order_number }}</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mt-2">Order Details</h1>
        </div>
        
        <div class="flex items-center space-x-3">
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $order->getStatusBadgeColor() }}">
                {{ ucfirst($order->status) }}
            </span>
            
            @if($order->status === 'confirmed')
                <a href="{{ route('pharmacy.orders.prepare', $order) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                    <i class="fas fa-cogs mr-2"></i>
                    Prepare Order
                </a>
            @endif
            
            @if($order->status === 'preparing')
                <form action="{{ route('pharmacy.orders.mark-ready', $order) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Mark this order as ready for pickup/delivery?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        <i class="fas fa-check-circle mr-2"></i>
                        Mark as Ready
                    </button>
                </form>
            @endif
            
            @if($order->status === 'ready')
                <form action="{{ route('pharmacy.orders.dispense', $order) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Dispense this order to the patient?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        <i class="fas fa-hand-holding-medical mr-2"></i>
                        Dispense Order
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-pills mr-2 text-blue-500"></i>
                        Order Items
                    </h2>
                </div>
                <div class="p-6">
                    @if($order->items->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-prescription-bottle-alt text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">No items have been prepared yet.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-900 dark:text-white">
                                                {{ $item->medication_name }}
                                            </h3>
                                            <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                                <div><strong>Dosage:</strong> {{ $item->dosage }}</div>
                                                <div><strong>Frequency:</strong> {{ $item->frequency }}</div>
                                                <div><strong>Duration:</strong> {{ $item->duration }}</div>
                                                @if($item->instructions)
                                                    <div><strong>Instructions:</strong> {{ $item->instructions }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $item->getStatusBadgeColor() }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Prescribed:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->quantity_prescribed }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Dispensed:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $item->quantity_dispensed }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Unit Price:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">${{ number_format($item->unit_price, 2) }}</div>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Total:</span>
                                            <div class="font-medium text-gray-900 dark:text-white">${{ number_format($item->total_price, 2) }}</div>
                                        </div>
                                    </div>
                                    
                                    @if($item->pharmacy_notes)
                                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                                            <div class="text-sm">
                                                <strong class="text-yellow-800 dark:text-yellow-200">Pharmacy Notes:</strong>
                                                <p class="text-yellow-700 dark:text-yellow-300 mt-1">{{ $item->pharmacy_notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prescription Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-file-prescription mr-2 text-green-500"></i>
                        Prescription Information
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prescription Number</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->prescription->prescription_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prescribed Date</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->prescription->prescribed_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->prescription->valid_until->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Doctor</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->prescription->doctor->user->name }}</p>
                    </div>
                    @if($order->prescription->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prescription Notes</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->prescription->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <!-- Patient Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Patient Information
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->patient->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->patient->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->patient->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-receipt mr-2 text-green-500"></i>
                        Order Summary
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="text-gray-900 dark:text-white">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->delivery_fee > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Delivery Fee</span>
                            <span class="text-gray-900 dark:text-white">${{ number_format($order->delivery_fee, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Tax</span>
                        <span class="text-gray-900 dark:text-white">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <div class="flex justify-between font-semibold text-lg">
                            <span class="text-gray-900 dark:text-white">Total</span>
                            <span class="text-gray-900 dark:text-white">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-truck mr-2 text-purple-500"></i>
                        Delivery Information
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Method</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ $order->delivery_method }}</p>
                    </div>
                    @if($order->delivery_address)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->delivery_address }}</p>
                        </div>
                    @endif
                    @if($order->estimated_ready_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Ready</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->estimated_ready_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes || $order->pharmacy_notes)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                            Notes
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($order->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Notes</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->notes }}</p>
                            </div>
                        @endif
                        @if($order->pharmacy_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pharmacy Notes</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $order->pharmacy_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Cancel Order -->
            @if(in_array($order->status, ['confirmed', 'preparing']))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <button onclick="showCancelModal()" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel Order
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <form action="{{ route('pharmacy.orders.cancel', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Cancel Order</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Please provide a reason for cancelling this order.
                        </p>
                        <textarea name="cancellation_reason" 
                                  required
                                  rows="3"
                                  class="mt-3 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Enter cancellation reason..."></textarea>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between px-4 py-3">
                <button type="button" 
                        onclick="hideCancelModal()"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }
    
    function hideCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endsection
