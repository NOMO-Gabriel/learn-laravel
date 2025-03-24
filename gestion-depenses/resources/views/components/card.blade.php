<!-- resources/views/components/card.blade.php -->
@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow overflow-hidden']) }}>
    @if($header)
        <div class="px-6 py-4 border-b bg-gray-50">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t bg-gray-50">
            {{ $footer }}
        </div>
    @endif
</div>