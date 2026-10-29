@props(['href', 'icon', 'variant' => 'primary'])
<a href="{{ $href }}" {{ $attributes->class("btn btn-$variant text-nowrap") }}>
    <x-dynamic-component :component="'heroicon-o-'.$icon" />
    {{ $slot }}
</a>
