@props(['href', 'icon'])
<a href="{{ $href }}" {{ $attributes->class('btn btn-primary') }}>
    <span title="{{ $slot }}" class="ion-{{ $icon }}" aria-hidden="true"></span>
    {{ $slot }}
</a>
