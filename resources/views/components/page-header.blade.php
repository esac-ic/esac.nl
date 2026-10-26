@props(['title', 'back' => null, 'backLabel' => 'Back'])
{{-- Back is a link above the title, away from the actions, so it can't be mistaken for one of them. --}}
@if ($back)
    <a href="{{ $back }}" class="d-inline-block mb-2 text-muted"><x-heroicon-o-arrow-left /> {{ $backLabel }}</a>
@endif
<div class="page-header mb-3">
    <div>
        <h1>{{ $title }}</h1>
        {{ $intro ?? '' }}
    </div>
    @if ($slot->isNotEmpty())
        <div class="page-actions" role="group" aria-label="Actions">
            {{ $slot }}
        </div>
    @endif
</div>
