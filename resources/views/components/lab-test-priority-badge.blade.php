@props(['priority'])

@php
    $priorityClasses = [
        'low' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'normal' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'high' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
        'urgent' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
    ];

    $priorityIcons = [
        'low' => 'fas fa-arrow-down',
        'normal' => 'fas fa-minus',
        'high' => 'fas fa-arrow-up',
        'urgent' => 'fas fa-exclamation',
    ];

    $classes = $priorityClasses[$priority] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    $icon = $priorityIcons[$priority] ?? 'fas fa-minus';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {$classes}"]) }}>
    <i class="{{ $icon }} mr-1"></i>
    {{ ucfirst($priority) }}
</span>
