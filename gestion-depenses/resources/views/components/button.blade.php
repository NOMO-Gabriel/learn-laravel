<!-- resources/views/components/button.blade.php -->
<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'font-bold py-2 px-4 rounded text-white ' . $colorClasses()]) }}
>
    {{ $slot }}
</button>