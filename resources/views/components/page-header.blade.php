@props(['title', 'back' => null, 'backLabel' => 'Back'])
{{-- Back is a link above the title, away from the actions, so it can't be mistaken for one of them. --}}
@if ($back)
    <a href="{{ $back }}" class="d-inline-block mb-2 text-muted"><span class="ion-android-arrow-back" aria-hidden="true"></span> {{ $backLabel }}</a>
@endif
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div class="mr-3">
        <h1>{{ $title }}</h1>
        {{ $intro ?? '' }}
    </div>
    @if ($slot->isNotEmpty())
        <div class="page-actions d-flex flex-wrap align-items-center" role="group" aria-label="Actions">
            {{ $slot }}
        </div>
    @endif
</div>
