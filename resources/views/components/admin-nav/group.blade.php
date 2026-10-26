@props(['label'])
<div class="admin-nav-group">
    <h2 class="admin-nav-heading">{{ $label }}</h2>
    <ul class="list-unstyled mb-0">
        {{ $slot }}
    </ul>
</div>
