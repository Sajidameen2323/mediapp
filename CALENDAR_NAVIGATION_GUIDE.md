# Calendar Navigation Restrictions - Implementation Guide

## Overview

The appointment calendar system now includes comprehensive navigation restrictions based on configurable min/max advance days. This ensures users can only book appointments within the allowed time window.

## Features Implemented

### 1. Navigation Button Restrictions

- **Previous Month Button**: Disabled when current month contains no days within the allowed booking range
- **Next Month Button**: Disabled when current month would be beyond the maximum advance days
- **Visual Feedback**: Disabled buttons show reduced opacity and cursor changes
- **Tooltips**: Informative messages explain why navigation is restricted

### 2. Date Range Configuration

```javascript
// Default configuration
this.config = {
    minAdvanceDays: 0,    // Can book starting today
    maxAdvanceDays: 30    // Can book up to 30 days ahead
};
```

### 3. API Integration

The calendar automatically receives configuration from the backend API:

```javascript
// API response format
{
    "success": true,
    "config": {
        "min_advance_days": 2,    // Minimum 2 days advance notice
        "max_advance_days": 21    // Maximum 21 days ahead
    },
    "selectable_dates": [...]
}
```

### 4. Dynamic Configuration Updates

The calendar supports runtime configuration changes:

```javascript
// Update configuration programmatically
calendar.updateConfig({
    minAdvanceDays: 1,
    maxAdvanceDays: 14
});
```

### 5. Keyboard Navigation

- **Arrow Left/Right**: Navigate months (respects restrictions)
- **Home**: Jump to current month
- **Escape**: Clear current selection
- **Tab Navigation**: Proper focus management

### 6. Accessibility Features

- **ARIA Labels**: Screen reader support for navigation buttons
- **Focus Management**: Keyboard-friendly interaction
- **Status Messages**: Clear feedback about restrictions

## Technical Implementation

### Date Limit Calculation

```javascript
calculateDateLimits() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    // Calculate minimum date
    this.minDate = new Date(today);
    this.minDate.setDate(today.getDate() + this.config.minAdvanceDays);
    
    // Calculate maximum date
    this.maxDate = new Date(today);
    this.maxDate.setDate(today.getDate() + this.config.maxAdvanceDays);
}
```

### Navigation Restriction Logic

```javascript
updateNavigationButtons() {
    const currentYear = this.currentDate.getFullYear();
    const currentMonth = this.currentDate.getMonth();
    
    // Check if previous month is allowed
    let canGoPrevious = true;
    if (this.minDate) {
        const minYear = this.minDate.getFullYear();
        const minMonth = this.minDate.getMonth();
        
        canGoPrevious = currentYear > minYear || 
                       (currentYear === minYear && currentMonth > minMonth);
    }
    
    // Similar logic for next month...
}
```

### Year Boundary Handling

The implementation properly handles navigation across year boundaries by comparing both year and month values rather than just month offsets.

## Usage Examples

### Basic Initialization

```javascript
const calendar = new AppointmentCalendar('calendar-container', {
    doctorId: 123,
    serviceId: 456,
    onDateSelect: (date, data) => {
        console.log('Date selected:', date);
    }
});
```

### Dynamic Doctor/Service Selection

```javascript
// Change doctor and reload restrictions
calendar.setDoctor(789, 101);

// Update configuration only
calendar.updateConfig({
    minAdvanceDays: 0,
    maxAdvanceDays: 60
});
```

### Programmatic Navigation

```javascript
// Navigate to specific month (respects restrictions)
const success = calendar.navigateToMonth(2025, 11); // December 2025
if (!success) {
    console.log('Navigation blocked - outside allowed range');
}
```

## API Integration Points

### Backend Configuration

Ensure your API endpoint returns the config object:

```php
// Example Laravel controller response
return response()->json([
    'success' => true,
    'config' => [
        'min_advance_days' => $doctor->min_advance_days ?? 0,
        'max_advance_days' => $doctor->max_advance_days ?? 30,
    ],
    'selectable_dates' => $selectableDates
]);
```

### Database Schema

```sql
-- Add columns to doctors table or create separate config table
ALTER TABLE doctors ADD COLUMN min_advance_days INT DEFAULT 0;
ALTER TABLE doctors ADD COLUMN max_advance_days INT DEFAULT 30;
```

## Testing Scenarios

### 1. Edge Cases

- **Same Day Booking**: minAdvanceDays = 0
- **Long Advance**: maxAdvanceDays = 365
- **Narrow Window**: minAdvanceDays = 7, maxAdvanceDays = 14

### 2. Year Boundaries

- Navigate from December to January with restrictions
- Handle leap years correctly
- Cross-year date range calculations

### 3. Real-time Updates

- Configuration changes during active session
- Doctor/service switching
- Network connectivity issues

## Browser Compatibility

- **Modern Browsers**: Full support (Chrome 60+, Firefox 55+, Safari 12+)
- **Internet Explorer**: Not supported (uses modern JS features)
- **Mobile**: Full touch and keyboard support

## Performance Considerations

- **Lazy Loading**: Only load dates for visible months
- **Caching**: Cache configuration to reduce API calls
- **Debouncing**: Prevent rapid navigation clicks

## Security Notes

- Server-side validation of all date selections
- Configuration validation on backend
- Sanitization of user inputs

## Future Enhancements

1. **Time-based Restrictions**: Hour-level booking windows
2. **Holiday Integration**: Automatic restriction during holidays
3. **Slot-based Navigation**: Navigate based on availability
4. **Multi-timezone Support**: Handle different time zones
5. **Batch Operations**: Multiple date selection with restrictions

## Troubleshooting

### Common Issues

1. **Navigation Buttons Not Updating**
   - Ensure `updateNavigationButtons()` is called after configuration changes
   - Check that min/max dates are correctly calculated

2. **Keyboard Navigation Not Working**
   - Verify calendar container has focus
   - Check for event listener conflicts

3. **API Configuration Not Applied**
   - Confirm API response format matches expected structure
   - Check network requests in browser developer tools

### Debug Mode

Enable debug logging:

```javascript
// Add to calendar initialization
calendar.debug = true;
```

This will log navigation restrictions and configuration changes to the console.
