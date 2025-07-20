# Timezone Configuration Guide

## Overview
The Laravel application has been configured to use a specific timezone instead of UTC. This ensures that all time-related operations, including appointment slot generation, use your local timezone.

## Current Configuration

### Timezone Setting
- **Current Timezone**: `Asia/Colombo` (Sri Lanka Standard Time)
- **Configuration Location**: `.env` file (`APP_TIMEZONE` variable)
- **Config File**: `config/app.php`

### Time Comparison
- **UTC Time**: `2025-07-20 11:23:51`
- **Local Time (SLST)**: `2025-07-20 16:53:51` (+5:30 hours)

## How to Change Timezone

### Method 1: Using Artisan Command
```bash
php artisan app:set-timezone Asia/Colombo
php artisan app:set-timezone America/New_York
php artisan app:set-timezone Europe/London
```

### Method 2: Manual Configuration
1. Edit the `.env` file:
   ```env
   APP_TIMEZONE=Asia/Colombo
   ```

2. Clear the configuration cache:
   ```bash
   php artisan config:clear
   ```

## Common Timezones

### Pakistan
- `Asia/Karachi` - Pakistan Standard Time (PKT, UTC+5)

### Sri Lanka
- `Asia/Colombo` - Sri Lanka Standard Time (SLST, UTC+5:30)

### United States
- `America/New_York` - Eastern Time
- `America/Chicago` - Central Time
- `America/Denver` - Mountain Time
- `America/Los_Angeles` - Pacific Time

### Europe
- `Europe/London` - Greenwich Mean Time
- `Europe/Paris` - Central European Time
- `Europe/Berlin` - Central European Time

### Middle East
- `Asia/Dubai` - Gulf Standard Time (GST, UTC+4)
- `Asia/Riyadh` - Arabia Standard Time (AST, UTC+3)

### Asia
- `Asia/Kolkata` - India Standard Time (IST, UTC+5:30)
- `Asia/Shanghai` - China Standard Time (CST, UTC+8)
- `Asia/Tokyo` - Japan Standard Time (JST, UTC+9)

## Impact on Application Features

### Appointment Slot Service
- **Minimum Booking Hours**: Now calculated from your local time
- **Available Slots**: Filtered based on your timezone
- **Logging**: All timestamps in logs will show your local time

### Example with 3-Hour Minimum
If current time is `16:10 PKT` and minimum booking hours is set to 3:
- **Earliest Booking Time**: `19:10 PKT`
- **Available Slots**: Only slots starting at 19:10 PKT or later

## Technical Implementation

### Configuration Files Updated
1. **config/app.php**: 
   ```php
   'timezone' => env('APP_TIMEZONE', 'Asia/Karachi'),
   ```

2. **.env file**:
   ```env
   APP_TIMEZONE=Asia/Karachi
   ```

3. **AppServiceProvider.php**:
   ```php
   public function boot(): void
   {
       // Set timezone for Carbon and PHP
       if (config('app.timezone')) {
           date_default_timezone_set(config('app.timezone'));
       }
   }
   ```

### New Artisan Command
- **Command**: `app:set-timezone {timezone}`
- **File**: `app/Console/Commands/SetTimezone.php`
- **Usage**: Automatically updates .env and clears config cache

## Verification Commands

### Check Current Configuration
```bash
php artisan tinker --execute="
echo 'Laravel timezone: ' . config('app.timezone') . PHP_EOL;
echo 'PHP timezone: ' . date_default_timezone_get() . PHP_EOL;
echo 'Current time: ' . now()->format('Y-m-d H:i:s T') . PHP_EOL;
"
```

### Test Appointment Slot Logic
```bash
php artisan tinker --execute="
use Carbon\Carbon;
echo 'Current time: ' . Carbon::now()->format('Y-m-d H:i:s T') . PHP_EOL;
echo 'Time + 3 hours: ' . Carbon::now()->addHours(3)->format('Y-m-d H:i:s T') . PHP_EOL;
"
```

## Database Considerations

### Important Notes
- **Database Storage**: Timestamps are still stored in UTC in the database
- **Display**: Times are converted to your timezone when displayed
- **API Responses**: Will show times in your configured timezone
- **Logging**: Application logs will use your timezone

### Existing Data
- No migration needed for existing appointments
- Laravel automatically converts between UTC (database) and your timezone (application)

## Troubleshooting

### Issue: Times Still Showing UTC
**Solution**: 
1. Clear config cache: `php artisan config:clear`
2. Restart web server if using PHP-FPM or similar
3. Check .env file has correct `APP_TIMEZONE` setting

### Issue: Invalid Timezone Error
**Solution**: 
1. Use valid PHP timezone identifiers
2. Check list at: https://www.php.net/manual/en/timezones.php
3. Use the artisan command for validation

### Issue: Appointment Slots Not Filtering Correctly
**Solution**:
1. Verify timezone is set correctly
2. Check AppointmentSlotService logs
3. Ensure `min_booking_hours_ahead` is configured properly

## Best Practices

1. **Consistent Configuration**: Ensure all environments (local, staging, production) use appropriate timezones
2. **User Display**: Consider showing timezone in UI for user clarity
3. **API Documentation**: Document which timezone API responses use
4. **Testing**: Test appointment booking across different times of day
5. **Logging**: Monitor logs during timezone transitions (DST changes)

## Related Files
- `config/app.php` - Main timezone configuration
- `.env` - Environment-specific timezone setting
- `app/Providers/AppServiceProvider.php` - Bootstrap timezone setting
- `app/Console/Commands/SetTimezone.php` - Timezone management command
- `app/Services/AppointmentSlotService.php` - Uses timezone for slot calculations
