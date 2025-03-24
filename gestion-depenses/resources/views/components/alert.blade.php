<!-- resources/views/components/alert.blade.php -->
@php
    $colors = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'danger' => 'bg-red-100 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-500 text-blue-700',
        'default' => 'bg-gray-100 border-gray-500 text-gray-700' 
    ];

    $alertClass = $colors[$type] ?? $colors['default'];
@endphp

<div 
    x-data="{ show: true }" 
    x-init="setTimeout(() => show = false, 5000)" 
    x-show="show" 
    x-transition.duration.500ms
    {{ $attributes->merge(['class' => "relative border-l-4 p-4 rounded-md $alertClass shadow-md"]) }}
    role="alert"
>
    <!-- Bouton de fermeture -->
    <button 
        @click="show = false" 
        class="absolute top-2 right-2 text-lg font-bold text-gray-700 hover:text-gray-900"
    >
        &times;
    </button>

    @if(isset($title))
        <h4 class="font-bold">{{ $title }}</h4>
    @endif
    
    <p>{{ $slot }}</p>
</div>
