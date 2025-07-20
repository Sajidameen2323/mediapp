# Appointment Slot Minimum Booking Hours Feature

## Overview
This feature implements time-based filtering for appointment slots based on the `min_booking_hours_ahead` setting in the AppointmentConfig. Users can only book appointment slots that are at least the specified number of hours from the current time.

## Implementation Details

### New Method: `removeSlotsTooEarly()`
**Location**: `app/Services/AppointmentSlotService.php`

**Purpose**: Filters out appointment slots that fall within the minimum booking hours ahead restriction.

**Parameters**:
- `Collection $slots` - The collection of available time slots
- `AppointmentConfig $config` - The appointment configuration containing the minimum booking hours ahead setting
- `Carbon $date` - The date for which slots are being generated

**Logic**:
1. Retrieves the `min_booking_hours_ahead` setting from config (defaults to 2 hours if not set)
2. Calculates the earliest allowed booking time by adding the minimum hours to the current time
3. Filters slots to only include those that start at or after the earliest booking time
4. Provides detailed logging for debugging purposes

### Integration
The method is integrated into the main `getAvailableSlots()` flow:

```php
// Generate time slots based on schedule
$slots = $this->generateTimeSlotsFromSchedule($schedule, $config, $service, $date);

// Remove slots that are within minimum booking hours ahead
$slots = $this->removeSlotsTooEarly($slots, $config, $date);

// Continue with other filters...
```

## Configuration
The feature uses the `min_booking_hours_ahead` field from the `appointment_configs` table:

- **Field**: `min_booking_hours_ahead`
- **Type**: Integer
- **Default**: 2 hours
- **Purpose**: Minimum number of hours before a slot can be booked

## Example Scenarios

### Scenario 1: 2-Hour Minimum
- **Current Time**: 10:00 AM
- **Setting**: `min_booking_hours_ahead = 2`
- **Earliest Booking Time**: 12:00 PM
- **Available Slots**: Only slots starting at 12:00 PM or later

### Scenario 2: Same-Day Booking
- **Current Time**: 2:00 PM
- **Setting**: `min_booking_hours_ahead = 2`
- **Earliest Booking Time**: 4:00 PM
- **Available Slots**: Only slots starting at 4:00 PM or later (if any exist for that day)

## Benefits
1. **Prevents Last-Minute Bookings**: Ensures adequate preparation time for appointments
2. **Realistic Scheduling**: Accounts for travel time and preparation requirements
3. **Administrative Control**: Configurable setting allows flexibility based on practice needs
4. **Hour-Level Precision**: More granular than day-based restrictions

## Logging
The method includes comprehensive logging:
- Current minimum hours setting
- Earliest allowed booking time
- Individual slots being filtered out
- Total number of slots removed

## Testing
To test the functionality:
1. Set `min_booking_hours_ahead` in appointment config
2. Generate slots for the current day
3. Verify only slots beyond the minimum time are returned
4. Check logs for detailed filtering information

## Related Files
- `app/Services/AppointmentSlotService.php` - Main implementation
- `app/Models/AppointmentConfig.php` - Configuration model
- `database/migrations/*_appointment_configs_table.php` - Database schema
- `resources/views/admin/appointment-config/edit.blade.php` - Admin interface
