@props(['title'])
<div class="row mb-3">
    <div class="col-md-6">
        <h1>{{ $title }}</h1>
        {{ $intro ?? '' }}
    </div>
    @if ($slot->isNotEmpty())
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                {{ $slot }}
            </div>
        </div>
    @endif
</div>
