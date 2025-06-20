@extends('layouts.app')

@section('title', 'Pharmacy Orders - Medi App')

@section('content')
<x-pharmacy-navigation />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pharmacy Orders</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage prescription orders and billing</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('pharmacy.orders.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Orders
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Order number or patient name..."
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="min-w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select id="status" 
                            name="status"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        @if($orders->isEmpty())
            <div class="p-12 text-center">
                <i class="fas fa-prescription-bottle-alt text-4xl text-gray-400 dark:text-gray-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Orders Found</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    @if(request()->hasAny(['search', 'status']))
                        No orders match your current filters.
                    @else
                        No orders have been placed yet.
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Order Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Patient
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->items->count() }} item(s)
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $order->patient->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->patient->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $order->getStatusBadgeColor() }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('pharmacy.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($order->status === 'confirmed')
                                            <a href="{{ route('pharmacy.orders.prepare', $order) }}" 
                                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                               title="Prepare Order">
                                                <i class="fas fa-cogs"></i>
                                            </a>
                                        @endif
                                        
                                        @if($order->status === 'preparing')
                                            <form action="{{ route('pharmacy.orders.mark-ready', $order) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Mark this order as ready?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300"
                                                        title="Mark as Ready">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($order->status === 'ready')
                                            <form action="{{ route('pharmacy.orders.dispense', $order) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Dispense this order?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                        title="Dispense Order">
                                                    <i class="fas fa-hand-holding-medical"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
