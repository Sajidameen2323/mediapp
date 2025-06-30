# Lab Test Components

This folder contains all reusable components related to laboratory tests in the medical application.

## Component Structure

### 📁 `/components/lab-test/`

#### Core Components:
- **`card.blade.php`** - Lab test card display for listings
- **`details.blade.php`** - Detailed view of a single lab test
- **`filters.blade.php`** - Filter and search interface for lab tests
- **`empty-state.blade.php`** - Empty state when no lab tests are found

#### UI Components:
- **`status-badge.blade.php`** - Status indicator badges (pending, completed, etc.)
- **`priority-badge.blade.php`** - Priority level badges (low, normal, high, urgent)

## Usage Examples

### Status Badge
```blade
<x-lab-test.status-badge :status="$labTest->status" />
<x-lab-test.status-badge :status="'completed'" class="text-lg" />
```

### Priority Badge
```blade
<x-lab-test.priority-badge :priority="$labTest->priority" />
<x-lab-test.priority-badge :priority="'urgent'" />
```

### Lab Test Card
```blade
<x-lab-test.card :lab-test="$labTest" />
```

### Lab Test Details
```blade
<x-lab-test.details :lab-test="$labTest" />
```

### Filters
```blade
<x-lab-test.filters :current-status="request('status')" />
```

### Empty State
```blade
<x-lab-test.empty-state />
<x-lab-test.empty-state 
    title="No pending tests" 
    description="All your lab tests have been completed." />
```

## Features

- **Responsive Design**: All components work on mobile and desktop
- **Dark Mode Support**: Full dark mode compatibility
- **Accessibility**: ARIA labels and semantic HTML
- **Interactive**: Hover effects and transitions
- **Customizable**: Props for different states and content

## Dependencies

- **Tailwind CSS**: For styling
- **Font Awesome**: For icons
- **Laravel Blade**: Component framework

## Contributing

When adding new lab test components:
1. Follow the existing naming convention
2. Ensure dark mode compatibility
3. Add responsive design support
4. Include proper documentation
5. Test with different data states
