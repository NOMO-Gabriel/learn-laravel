<!-- resources/views/components/form/input.blade.php -->
@props(['name', 'label', 'type' => 'text', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded ' . ($errors->has($name) ? 'border-red-500' : '')]) }}
    >
    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>