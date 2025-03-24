<!-- resources/views/components/button.blade.php -->
@props(['type' => 'button', 'color' => 'primary'])

@php
    $baseClasses = 'font-bold py-2 px-4 rounded focus:outline-none transition';
    $colorClasses = match($color) {
        'primary' => 'bg-primary-500 hover:bg-primary-700 text-white',
        'success' => 'bg-green-500 hover:bg-green-700 text-white',
        'danger' => 'bg-red-500 hover:bg-red-700 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-700 text-white',
        'gray' => 'bg-gray-500 hover:bg-gray-700 text-white',
        default => 'bg-primary-500 hover:bg-primary-700 text-white',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }}
>
    {{ $slot }}
</button>