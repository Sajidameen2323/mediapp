@extends('layouts.patient')

@section('title', 'My Pharmacy Orders')

@section('content')


<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Pharmacy Orders</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Track and manage your pharmacy orders</p>
            
            @if(!request()->filled('status') || request('status') !== 'cancelled')
                <div class="mt-4 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                        <span class="text-sm text-blue-800 dark:text-blue-200">
                            Showing active orders only. 
                            <a href="{{ route('patient.pharmacy-orders.index', ['status' => 'cancelled']) }}" class="underline hover:text-blue-900 dark:hover:text-blue-100">View cancelled orders</a>
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('patient.pharmacy-orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}" 
                               placeholder="Order number or pharmacy..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Order Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Active Orders</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled Only</option>
                        </select>
                    </div>

                    <!-- Payment Status Filter -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Payment Status</option>
                            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        <a href="{{ route('patient.pharmacy-orders.index') }}" class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100 transition-colors">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Order Header -->
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                Order #{{ $order->order_number }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->getStatusBadgeColor() }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->getPaymentStatusBadgeColor() }}">
                                                <i class="fas fa-credit-card mr-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                        </div>
                                    </div>

                                    <!-- Order Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Pharmacy</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-store mr-1"></i>
                                                {{ $order->pharmacy->pharmacy_name ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Method</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-{{ $order->delivery_method === 'pickup' ? 'store' : 'truck' }} mr-1"></i>
                                                {{ ucfirst($order->delivery_method) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Amount</p>
                                            <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                                ${{ number_format($order->total_amount, 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Items Summary -->
                                    @if($order->items->count() > 0)
                                        <div class="mb-4">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Items ({{ $order->items->count() }})</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($order->items->take(3) as $item)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                        {{ $item->prescriptionMedication->medication->name ?? 'Unknown' }}
                                                        ({{ $item->quantity }})
                                                    </span>
                                                @endforeach
                                                @if($order->items->count() > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                        +{{ $order->items->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('patient.pharmacy-orders.show', $order) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>View Details
                                        </a>

                                        @if($order->canProcessPayment())
                                            <a href="{{ route('patient.pharmacy-orders.payment', $order) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 transition-colors">
                                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                                            </a>
                                        @endif

                                        @if($order->canBeCancelled())
                                            <form action="{{ route('patient.pharmacy-orders.cancel', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors">
                                                    <i class="fas fa-times mr-2"></i>Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-4">
                        <i class="fas fa-pills text-6xl"></i>
                    </div>
                    @if(request()->filled('status') || request()->filled('payment_status') || request()->filled('search'))
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No orders match your filters</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Try adjusting your search criteria or 
                            <a href="{{ route('patient.pharmacy-orders.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">clear all filters</a>.
                        </p>
                    @elseif(request('status') === 'cancelled')
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No cancelled orders</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">You don't have any cancelled pharmacy orders.</p>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No active pharmacy orders</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            You haven't placed any pharmacy orders yet. 
                            <span class="text-sm block mt-2 text-gray-500 dark:text-gray-400">Note: Cancelled orders are hidden by default.</span>
                        </p>
                    @endif
                    
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('patient.prescriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors">
                            <i class="fas fa-prescription-bottle-alt mr-2"></i>View Prescriptions
                        </a>
                        @if(!request()->filled('status') && !request()->filled('payment_status') && !request()->filled('search'))
                            <a href="{{ route('patient.pharmacy-orders.index', ['status' => 'cancelled']) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>View Cancelled Orders
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
