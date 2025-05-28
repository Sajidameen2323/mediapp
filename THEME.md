# Theme

This file outlines the theme of the project.
Details about the theme will be added here later.

## Font Families

- The primary font family is `Inter`, as defined in `tailwind.config.js`.
- An additional font family `Instrument Sans` is defined in `resources/css/app.css` using the `@theme` directive.

## Dark Mode

The application supports a class-based dark mode. This is configured in the `tailwind.config.js` file by the `darkMode: 'class'` setting. This system uses Tailwind's `dark:` variant to apply different styles when dark mode is active.

## Application Color Palette

The application primarily utilizes the **default Tailwind CSS color palette**. The following list details the specific color names and shades that have been identified in use across the application's UI components.

### Amber
- **Shades:** 300
- **Typical Usage:** Used for warm highlights or specific accent elements.

### Black
- **Shades:** Special (Direct usage, e.g., `bg-black`)
- **Typical Usage:** Core text color, dark theme backgrounds, or specific UI elements requiring a solid black.

### Blue
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Primary color for call-to-action buttons (often in gradients), links, focus indicators, icons, and informational blocks. Frequently used for primary UI elements and to convey trust or standard actions.

### Cyan
- **Shades:** 50
- **Typical Usage:** Light accent color, often for subtle backgrounds or highlights.

### Emerald
- **Shades:** 50, 600, 700
- **Typical Usage:** Can be used as an accent color, potentially for success-adjacent indicators or unique UI elements. Part of some gradient button styles.

### Fuchsia
- **Shades:** 600
- **Typical Usage:** Vibrant accent color for specific highlights or thematic elements.

### Gray
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Core color for neutral UI elements. Extensively used for text (various shades for hierarchy), backgrounds (pages, cards, inputs), borders, and secondary button states. Darker grays are prominent in dark mode.

### Green
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Primarily for success indicators, success alerts, positive action buttons (e.g., "Add New", "Create"), and sometimes as part of gradient button styles.

### Indigo
- **Shades:** 50, 100, 500, 600, 700, 800, 900
- **Typical Usage:** Often paired with Blue for primary action button gradients. Also used for links, icons, and other accent elements.

### Lime
- **Shades:** 500
- **Typical Usage:** Bright accent color, used sparingly for specific highlights.

### Orange
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Used for accents, highlights, and sometimes for "warning" or attention-grabbing (though not strictly error) elements like specific icons or section backgrounds. Part of some gradient styles.

### Pink
- **Shades:** 50, 600, 700
- **Typical Usage:** Accent color, often used in gradients with Purple or other warm colors for buttons or highlights.

### Purple
- **Shades:** 50, 100, 200, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Accent color used in primary action button gradients (often with Indigo or Pink), icons, and thematic highlights.

### Red
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Standard color for error messages (text, borders, backgrounds of error alerts), destructive action buttons (e.g., "Delete"), and critical alert indicators.

### Rose
- **Shades:** 400
- **Typical Usage:** Accent color, potentially for highlights or specific UI elements.

### Sky
- **Shades:** 600
- **Typical Usage:** Accent color, likely for specific highlights or thematic elements.

### Slate
- **Shades:** 50, 700
- **Typical Usage:** Neutral color, similar to Gray, used for text, backgrounds, or borders. `slate-700` is noted for ring offsets.

### Teal
- **Shades:** 500, 600, 700
- **Typical Usage:** Accent color, used for specific UI elements or highlights, including some button styles.

### Transparent
- **Shades:** Special (Direct usage, e.g., `border-transparent`)
- **Typical Usage:** Utility color, primarily for borders or backgrounds where no visible color is desired but the element needs to occupy space or have other border/background properties.

### Violet
- **Shades:** 50
- **Typical Usage:** Light accent color, often for subtle backgrounds or highlights.

### White
- **Shades:** Special (Direct usage, e.g., `bg-white`, `text-white`)
- **Typical Usage:** Core color for text (especially on dark backgrounds or primary buttons), backgrounds of pages and components, and borders.

### Yellow
- **Shades:** 50, 100, 200, 300, 400, 500, 600, 700, 800, 900
- **Typical Usage:** Used for highlights, informational icons (like stars in ratings), some alert-like informational blocks, and accent elements. Can sometimes indicate a mild warning or attention.

## Form Styling

Form styling is achieved by applying Tailwind CSS utility classes directly to HTML elements within the Blade templates.

**Key Characteristics:**

- **No dedicated form plugin:** The application does not use the `@tailwindcss/forms` plugin.
- **No global custom form CSS:** Custom form styles are not defined in `resources/css/app.css` or `tailwind.config.js`. All styling is done via utility classes.
- **Visual Style:**
    - Inputs, selects, and textareas generally use 'Gray' shades for borders (e.g., `border-gray-300`), 'White' and 'Gray' shades for backgrounds (e.g., `bg-white/50`), and 'Gray' or 'White' for text (e.g., `text-gray-900 dark:text-white`). Placeholders use 'Gray' shades (e.g., `placeholder-gray-500`).
    - Focus states typically use 'Blue-500' for rings (e.g., `focus:ring-blue-500`), as defined in the 'Blue' section of the Application Color Palette.
    - Labels are styled with 'Gray' shades for text (e.g., `text-gray-700 dark:text-gray-300`).
    - Error states are visually indicated using 'Red' shades from the palette for borders and text (e.g., `border-red-500`, `text-red-600`).
- **Consistency:** Styling for similar form elements is largely consistent.
- **Dark Mode:** Form elements have corresponding dark mode styles applied through Tailwind's `dark:` variants, utilizing appropriate shades from the palette.
- **Icons:** Font Awesome icons are frequently used, often styled with 'Blue' or 'Gray' shades depending on the context.

## Button Styling

Button styling is implemented using Tailwind CSS utility classes directly in the Blade templates.

**1. Primary Action Buttons:**
   - **Use:** Main calls to action.
   - **Styling:**
     - Background: support primary color for both light and dark mode prefix `dark:` for dark mode

**2. Secondary/Cancel Buttons:**
   - **Use:** Alternative actions like "Cancel" or "Back".
   - **Styling:**
     - Background: appropriate styling
     - Border: Solid 'Gray' borders (e.g., `border-gray-300`).

**3. Tertiary/Link-style Buttons:**
   - **Use:** Actions in tables, navigation links.
   - **Styling:**
     - Background: 'Transparent'.
     - Text: Uses 'Blue' or 'Red' shades from the palette, with hover color changes.

**4. Small Action/Preset Buttons:**
   - **Use:** Minor form actions.
   - **Styling:**
     - Background: Light shades of 'Green', 'Red', or 'Blue' from the palette (e.g., `bg-green-100`).
     - Text: Corresponding darker shades of 'Green', 'Red', or 'Blue' (e.g., `text-green-700`).

**General Characteristics (Buttons):**
- **Dark Mode:** All button styles have corresponding `dark:` variants, utilizing appropriate shades from the palette.
- **Icons:** Font Awesome icons are widely used and their color often corresponds to the button's text or primary color theme.

## Alert Styling (Global / Session-Based)

Global alerts are constructed directly using Tailwind CSS utility classes in `resources/views/layouts/app.blade.php`.

**1. Session-Based Global Alerts (Success & Error):**
   - **Trigger:** `session('success')` or `session('error')`.
   - **Success Styling:** Uses 'Green' shades from the palette for background, border, and text (e.g., `bg-green-50`, `border-green-200`, `text-green-800`). Icons also use 'Green' shades (e.g., `text-green-600`).
   - **Error Styling:** Uses 'Red' shades from the palette for background, border, and text (e.g., `bg-red-50`, `border-red-200`, `text-red-800`). Icons also use 'Red' shades (e.g., `text-red-600`).

**General Alert Characteristics (Global):**
- **Color Coding:** 'Green' for success, 'Red' for errors, as defined in the Application Color Palette.
- **Structure:** Light background, contrasting text, border, padding, rounded corners, and a Font Awesome icon.
- **Dark Mode:** Styles consistently include `dark:` variants, using appropriate shades from the palette.
- **Missing Styles:** No standard "warning" (using 'Yellow' or 'Orange' shades) or generic "info" (using 'Blue' shades) global alert styles were observed as clearly defined reusable patterns.

## Error Message Styling (Non-Global / Form-Specific)

These error messages are typically related to form validation and are displayed within or near the form, using 'Red' shades from the Application Color Palette.

**1. Form Validation Error Summaries / Specific Section Error Divs:**
   - **Styling:** Uses light 'Red' backgrounds (e.g., `bg-red-50`), 'Red' borders (e.g., `border-red-200`), and darker 'Red' text (e.g., `text-red-800` for headers, `text-red-700` for list items). Icons use 'Red' shades.

**2. Inline Form Field Validation Messages:**
   - **Styling:** Simple 'Red' text (e.g., `text-red-600`). Icons, if present (mainly in Auth views), also use 'Red' shades.

**3. Input Field Highlighting on Error:**
   - **Styling:** Input fields receive 'Red' border classes (e.g., `border-red-500`) and 'Red' focus rings (e.g., `focus:ring-red-500`).

**General Characteristics (Non-Global Errors):**
- **Color:** Consistently 'Red'-themed, as per the Application Color Palette.
- **Implementation:** Built with Tailwind utility classes directly in Blade templates.
- **Dark Mode:** Styles include `dark:` variants, using appropriate 'Red' shades from the palette.
