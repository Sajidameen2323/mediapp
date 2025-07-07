# Pharmacy Order Payment Processing Update

## Summary
Updated the payment processing logic for pharmacy orders to ensure payments can only be processed at the appropriate times in the order workflow.

## Changes Made

### 1. Updated `canProcessPayment()` Method in PharmacyOrder Model

**Previous Logic:**
```php
public function canProcessPayment(): bool
{
    return $this->payment_status === 'pending' && in_array($this->status, ['pending', 'confirmed', 'preparing', 'ready']);
}
```

**New Logic:**
```php
public function canProcessPayment(): bool
{
    return $this->payment_status === 'pending' && in_array($this->status, ['ready', 'delivered']);
}
```

### 2. Business Logic Rationale

**Payment Processing is now only allowed when:**
- Order status is "ready" (price is finalized by pharmacy)
- OR order status is "delivered" (but payment is still pending)

**Payment Processing is NOT allowed when:**
- Order is in "pending" status (not yet confirmed by pharmacy)
- Order is in "confirmed" status (pharmacy working on pricing)
- Order is in "preparing" status (pharmacy still preparing, price may change)
- Order is "cancelled"
- Payment is already "paid" or "refunded"

### 3. Order Status Workflow

1. **pending** → Customer places order, waiting for pharmacy confirmation
2. **confirmed** → Pharmacy confirms order, starts working on pricing and preparation
3. **preparing** → Pharmacy is preparing the order, finalizing quantities and pricing
4. **ready** → ✅ **Order ready for pickup/delivery, final price set - PAYMENT ALLOWED**
5. **delivered** → ✅ **Order delivered but payment might still be pending - PAYMENT ALLOWED**
6. **cancelled** → Order cancelled at any stage

### 4. Impact

This change ensures that:
- Customers cannot pay for orders until the final price is determined
- Pharmacies have time to adjust quantities and pricing based on availability
- Payment is still possible for delivered orders if payment was missed
- The "Pay Now" button only appears when payment can actually be processed

### 5. Files Affected

- `app/Models/PharmacyOrder.php` - Updated `canProcessPayment()` method
- All views using `canProcessPayment()` will automatically reflect this change:
  - Patient dashboard pharmacy orders
  - Prescription show page (main orders and latest order sections)
  - Pharmacy order index and detail pages
- Payment controller already checks `canProcessPayment()` so no additional changes needed

## Verification

- ✅ PHP syntax check passed
- ✅ No lint errors
- ✅ Logic updated consistently across all usage points
- ✅ Payment workflow now aligns with business requirements

Date: July 7, 2025
