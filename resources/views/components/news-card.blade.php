@props(['newsItem'])
<div {{ $attributes->class('card') }}>
    <div class="position-relative">
        @if($newsItem->image_path != "")
            <img class="card-img-top" src="{{ $newsItem->image_url }}" alt="">
        @endif
        <span class="card-date position-absolute bg-light py-1 px-3 rounded"><x-local-time :value="$newsItem->created_at" format="d M" /></span>
    </div>
    <div class="card-body">
        <h4 class="card-title">{{ $newsItem->title }}</h4>
        <p class="card-text text-body">
            {!! $newsItem->body !!}
        </p>
    </div>
    <div class="card-footer">
        <div class="row justify-content-between">
            <div class="col-auto text-muted">
                <x-heroicon-o-user /> {{ $newsItem->author }}
            </div>
        </div>
    </div>
</div>
