<!-- resources/views/components/alert.blade.php -->
@props(['type' => 'info', 'title' => null])

@php
    $classes = match($type) {
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        default => 'bg-blue-100 border-blue-400 text-blue-700',
    };
@endphp

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 5000)" 
    x-show="show" 
    x-transition.duration.500ms
    {{ $attributes->merge(['class' => "border px-4 py-3 rounded relative $classes shadow-md"]) }} 
    role="alert"
>
    <!-- Bouton de fermeture -->
    <button 
        @click="show = false" 
        class="absolute top-2 right-2 text-lg font-bold text-gray-700 hover:text-gray-900"
    >
        &times;
    </button>

    @if($title)
        <strong class="font-bold">{{ $title }}</strong>
        <span class="block sm:inline">{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</div>
