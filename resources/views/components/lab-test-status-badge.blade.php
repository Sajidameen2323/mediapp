@props(['status'])

@php
    $statusClasses = [
        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
        'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
    ];

    $statusIcons = [
        'pending' => 'fas fa-clock',
        'in_progress' => 'fas fa-spinner fa-spin',
        'completed' => 'fas fa-check-circle',
        'cancelled' => 'fas fa-times-circle',
        'rejected' => 'fas fa-exclamation-triangle',
    ];

    $classes = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    $icon = $statusIcons[$status] ?? 'fas fa-question-circle';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {$classes}"]) }}>
    <i class="{{ $icon }} mr-1.5"></i>
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
