@extends('layouts.app')
@section('content')
<x-page-intro :title="$curPageName" :$content />

<section class="py-3">
    <div class="container">
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($newsItems as $newsItem)
            <div class="col-lg-4 col-md-6 d-flex flex-wrap">
                <div class="card w-100 position-relative">
                    @if($newsItem->thumbnail_url != "")
                    <a href="{{ route('front-end.news.detail', $newsItem) }}">
                        <img class="card-img-top" src="{{$newsItem->getThumbnailUrl()}}" alt="Card image cap">
                    </a>
                    @endif
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded"><x-local-time :value="$newsItem->created_at" format="d M" /></span>
                    <div class="card-body">
                        <a href="{{ route('front-end.news.detail', $newsItem) }}">
                            <h4 class="card-title">{{$newsItem->title}}</h4>
                            <p class="card-text text-body">{{ str($newsItem->text)->limit(150, '...')->stripTags() }}</p>
                        </a>
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <span class="ion-person"></span> {{$newsItem->author}}
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-outline-primary" href="{{ route('front-end.news.detail', $newsItem) }}">Read more</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex justify-content-center">{{ $newsItems->links() }}</div>
</section>
@endsection
