@extends('layouts.patient')

@section('title', 'Order Details')

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
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">Order #{{ $pharmacyOrder->order_number }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center justify-between mt-2">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Order #{{ $pharmacyOrder->order_number }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Order placed on {{ $pharmacyOrder->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pharmacyOrder->getStatusBadgeColor() }}">
                        {{ ucfirst($pharmacyOrder->status) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pharmacyOrder->getPaymentStatusBadgeColor() }}">
                        <i class="fas fa-credit-card mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $pharmacyOrder->payment_status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Payment Action -->
        @if($pharmacyOrder->canProcessPayment())
            <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Payment Required</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300">Complete your payment to confirm this order.</p>
                        </div>
                    </div>
                    <a href="{{ route('patient.pharmacy-orders.payment', $pharmacyOrder) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>Pay Now (${{ number_format($pharmacyOrder->total_amount, 2) }})
                    </a>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Pharmacy Information -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-store mr-2 text-blue-600 dark:text-blue-400"></i>
                            Pharmacy Information
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        @if($pharmacyOrder->pharmacy)
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Pharmacy Name</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->pharmacy->pharmacy_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->pharmacy->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Address</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->pharmacy->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No pharmacy assigned yet</p>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-pills mr-2 text-blue-600 dark:text-blue-400"></i>
                            Order Items
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pharmacyOrder->items as $item)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->prescriptionMedication->medication->name ?? 'Unknown Medication' }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->prescriptionMedication->medication->generic_name ?? '' }}
                                        </p>
                                        <div class="mt-1 flex items-center space-x-4">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                Quantity: {{ $item->quantity }}
                                            </span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                Unit Price: ${{ number_format($item->unit_price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${{ number_format($item->total_price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No items in this order</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-truck mr-2 text-blue-600 dark:text-blue-400"></i>
                            Delivery Information
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Method</p>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    <i class="fas fa-{{ $pharmacyOrder->delivery_method === 'pickup' ? 'store' : 'truck' }} mr-1"></i>
                                    {{ ucfirst($pharmacyOrder->delivery_method) }}
                                </p>
                            </div>
                            @if($pharmacyOrder->delivery_method === 'delivery' && $pharmacyOrder->delivery_address)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Address</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->delivery_address }}</p>
                                </div>
                            @endif
                            @if($pharmacyOrder->estimated_ready_at)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Ready At</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->estimated_ready_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="space-y-6">
                <!-- Price Summary -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-receipt mr-2 text-blue-600 dark:text-blue-400"></i>
                            Order Summary
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
                                <span class="text-base font-medium text-gray-900 dark:text-white">Total</span>
                                <span class="text-base font-bold text-green-600 dark:text-green-400">${{ number_format($pharmacyOrder->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-cog mr-2 text-blue-600 dark:text-blue-400"></i>
                            Actions
                        </h2>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        @if($pharmacyOrder->canProcessPayment())
                            <a href="{{ route('patient.pharmacy-orders.payment', $pharmacyOrder) }}" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                            </a>
                        @endif

                        @if($pharmacyOrder->canBeCancelled())
                            <form action="{{ route('patient.pharmacy-orders.cancel', $pharmacyOrder) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg border border-red-200 dark:border-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel Order
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('patient.pharmacy-orders.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                        </a>
                    </div>
                </div>

                <!-- Notes -->
                @if($pharmacyOrder->notes || $pharmacyOrder->pharmacy_notes)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-sticky-note mr-2 text-blue-600 dark:text-blue-400"></i>
                                Notes
                            </h2>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            @if($pharmacyOrder->notes)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Your Notes</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->notes }}</p>
                                </div>
                            @endif
                            @if($pharmacyOrder->pharmacy_notes)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Pharmacy Notes</p>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $pharmacyOrder->pharmacy_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
