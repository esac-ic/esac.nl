@props(['title', 'content' => ''])
<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $title }}</h2>
            {!! $content !!}
            {{ $slot }}
        </div>
    </div>
</div>
