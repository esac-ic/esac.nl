@props(['label'])
{{-- The row's actions in one labelled menu, so a stray click can't trigger one; destructive ones go last. --}}
<div class="dropdown">
    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" aria-label="Actions for {{ $label }}">
        Actions
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        {{ $slot }}
    </div>
</div>
