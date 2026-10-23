@props(['title', 'actions' => null])
<div {{ $attributes->class('card') }}>
    <div @class(['card-header', 'd-flex flex-wrap justify-content-between align-items-center' => $actions])>
        <h3 @class(['mb-0' => $actions])>{{ $title }}</h3>
        {{ $actions }}
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
