@extends('layouts.patient')

@section('title', 'Order from Pharmacy')

@section('content')


<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('patient.prescriptions.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-300">
                            <i class="fas fa-prescription-bottle-alt"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.prescriptions.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Prescriptions</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Prescription #{{ $prescription->prescription_number ?? $prescription->id }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 mx-2"></i>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">Order from Pharmacy</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Order from Pharmacy</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Choose a pharmacy and place your order</p>
        </div>

        <form action="{{ route('patient.prescriptions.store-pharmacy-order', $prescription) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Prescription Summary -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                        Prescription Summary
                    </h2>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Prescribed by:</span>
                            <p class="text-sm text-gray-900 dark:text-white">
                                @if($prescription->doctor && $prescription->doctor->user)
                                    {{ $prescription->doctor->user->name }}
                                @elseif($prescription->medicalReport && $prescription->medicalReport->doctor && $prescription->medicalReport->doctor->user)
                                    {{ $prescription->medicalReport->doctor->user->name }}
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Doctor information not available</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Medications:</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $prescription->prescriptionMedications->count() }} items</p>
                        </div>
                    </div>
                    
                    @if($prescription->prescriptionMedications->isNotEmpty())
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medications:</h4>
                            <div class="space-y-2">
                                @foreach($prescription->prescriptionMedications as $medication)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                        <div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $medication->medication->name }}</span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">{{ $medication->dosage }}</span>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $medication->quantity_prescribed }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pharmacy Selection -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <i class="fas fa-pharmacy mr-2 text-green-600 dark:text-green-400"></i>
                        Select Pharmacy
                    </h2>
                </div>
                <div class="px-6 py-4">
                    @if($pharmacies->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-pharmacy text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400">No pharmacies available at the moment.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pharmacies as $pharmacy)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="pharmacy_id" value="{{ $pharmacy->id }}" class="sr-only" required>
                                    <div class="pharmacy-card p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-pharmacy text-green-600 dark:text-green-300"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $pharmacy->pharmacy_name }}</h3>
                                                @if($pharmacy->address)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                                        {{ $pharmacy->address }}
                                                    </p>
                                                @endif
                                                @if($pharmacy->home_delivery_available)
                                                    <div class="mt-2 flex items-center">
                                                        <i class="fas fa-truck text-blue-600 dark:text-blue-400 mr-2"></i>
                                                        <span class="text-sm text-blue-600 dark:text-blue-400">Home delivery available</span>
                                                        @if($pharmacy->home_delivery_fee > 0)
                                                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">(Fee: ${{ number_format($pharmacy->home_delivery_fee, 2) }})</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            @if($pharmacies->isNotEmpty())
                <!-- Delivery Method -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-shipping-fast mr-2 text-purple-600 dark:text-purple-400"></i>
                            Delivery Method
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="delivery_method" value="pickup" class="sr-only" checked>
                                <div class="delivery-method-card p-4 border-2 border-blue-500 dark:border-blue-400 rounded-lg bg-blue-50 dark:bg-blue-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-store text-blue-600 dark:text-blue-300 text-2xl mr-3"></i>
                                        <div>
                                            <h3 class="font-medium text-gray-900 dark:text-white">Pickup at Pharmacy</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Pick up your order at the pharmacy</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer">
                                <input type="radio" name="delivery_method" value="delivery" class="sr-only">
                                <div class="delivery-method-card p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-truck text-purple-600 dark:text-purple-400 text-2xl mr-3"></i>
                                        <div>
                                            <h3 class="font-medium text-gray-900 dark:text-white">Home Delivery</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Get your order delivered to your address</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Delivery Address (hidden by default) -->
                        <div id="delivery-address-section" class="mt-4 hidden">
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                Delivery Address
                            </label>
                            <textarea name="delivery_address" 
                                    id="delivery_address" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                    placeholder="Enter your full delivery address...">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <i class="fas fa-sticky-note mr-2 text-orange-600 dark:text-orange-400"></i>
                            Additional Notes (Optional)
                        </h2>
                    </div>
                    <div class="px-6 py-4">
                        <textarea name="notes" 
                                id="notes" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400"
                                placeholder="Any special instructions or notes for the pharmacy...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $error->message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('patient.prescriptions.show', $prescription) }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Prescription
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Place Order
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pharmacy selection handling
        const pharmacyCards = document.querySelectorAll('.pharmacy-card');
        const pharmacyRadios = document.querySelectorAll('input[name="pharmacy_id"]');
        
        pharmacyRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                pharmacyCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
                    card.classList.add('border-gray-200', 'dark:border-gray-600');
                });
                
                if (this.checked) {
                    const card = this.closest('label').querySelector('.pharmacy-card');
                    card.classList.remove('border-gray-200', 'dark:border-gray-600');
                    card.classList.add('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
                }
            });
        });

        // Delivery method handling
        const deliveryCards = document.querySelectorAll('.delivery-method-card');
        const deliveryRadios = document.querySelectorAll('input[name="delivery_method"]');
        const deliveryAddressSection = document.getElementById('delivery-address-section');
        
        deliveryRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                deliveryCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900', 'border-purple-500', 'dark:border-purple-400', 'bg-purple-50', 'dark:bg-purple-900');
                    card.classList.add('border-gray-200', 'dark:border-gray-600');
                });
                
                if (this.checked) {
                    const card = this.closest('label').querySelector('.delivery-method-card');
                    card.classList.remove('border-gray-200', 'dark:border-gray-600');
                    
                    if (this.value === 'pickup') {
                        card.classList.add('border-blue-500', 'dark:border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
                        deliveryAddressSection.classList.add('hidden');
                    } else {
                        card.classList.add('border-purple-500', 'dark:border-purple-400', 'bg-purple-50', 'dark:bg-purple-900');
                        deliveryAddressSection.classList.remove('hidden');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
