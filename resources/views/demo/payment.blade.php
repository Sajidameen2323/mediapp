@extends('layouts.app')

@section('title', 'Payment System Demo - MediCare')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                Payment System Demo
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Test the payment modal with different configurations
            </p>
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg">
                <p class="text-blue-800 dark:text-blue-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    This is a demo version. No actual payments will be processed.
                </p>
            </div>
        </div>

        {{-- Payment Examples --}}
        <div class="mt-8">
            <x-payment-examples />
        </div>

        {{-- Technical Information --}}
        <div class="mt-12 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Technical Information</h2>
            
            <div class="prose dark:prose-invert max-w-none">
                <h3>Payment Configuration</h3>
                <p>The payment modal is configured based on the <code>AppointmentConfig</code> model settings:</p>
                
                <ul>
                    <li><strong>Accepted Payment Methods:</strong> Dynamically loaded from <code>AppointmentConfig::getActive()->accepted_payment_methods</code></li>
                    <li><strong>Require Payment on Booking:</strong> Configurable via <code>require_payment_on_booking</code> setting</li>
                    <li><strong>Booking Deposit Percentage:</strong> Configurable via <code>booking_deposit_percentage</code> setting</li>
                </ul>
                
                <h3>How to Use</h3>
                <p>To use the payment modal in your pages:</p>
                
                <ol>
                    <li>Include the component: <code>&lt;x-payment-modal id="payment-modal" /&gt;</code></li>
                    <li>Include the helper script: <code>&lt;script src="{{ asset('js/payment-modal-helper.js') }}"&gt;&lt;/script&gt;</code></li>
                    <li>Open the modal: <code>showPaymentModal(amount, appointmentId, title)</code></li>
                </ol>
                
                <h3>Dark Mode Support</h3>
                <p>The payment modal fully supports dark mode with appropriate Tailwind classes.</p>
            </div>
        </div>
    </div>
</div>

<!-- Include the payment modal component -->
<x-payment-modal id="payment-modal" />

@endsection

@section('scripts')
<script src="{{ asset('js/payment-modal-helper.js') }}"></script>
@endsection
