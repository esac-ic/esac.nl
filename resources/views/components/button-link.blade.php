@props(['href', 'icon', 'variant' => 'primary'])
<a href="{{ $href }}" {{ $attributes->class("btn btn-$variant text-nowrap") }}>
    <span class="ion-{{ $icon }}" aria-hidden="true"></span>
    {{ $slot }}
</a>
