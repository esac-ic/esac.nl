@props(['title'])
<div {{ $attributes->class('card') }}>
    <div class="card-header">
        <h3>{{ $title }}</h3>
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
