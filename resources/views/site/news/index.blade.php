@extends('layouts.app')
@section('content')
<x-page-intro :title="$pageTitle" :$content />

<section class="py-3">
    <div class="container">
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($newsItems as $newsItem)
            <div class="col-lg-4 col-md-6 d-flex flex-wrap">
                <div class="card w-100 position-relative">
                    @if($newsItem->thumbnail_path != "")
                    <a href="{{ route('news.show', $newsItem) }}">
                        <img class="card-img-top" src="{{$newsItem->thumbnail_url}}" alt="">
                    </a>
                    @endif
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded"><x-local-time :value="$newsItem->created_at" format="d M" /></span>
                    <div class="card-body">
                        <a href="{{ route('news.show', $newsItem) }}">
                            <h4 class="card-title">{{$newsItem->title}}</h4>
                            <p class="card-text text-body">{{ str($newsItem->body)->stripTags()->pipe('html_entity_decode')->limit(150) }}</p>
                        </a>
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <x-heroicon-o-user /> {{$newsItem->author}}
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-outline-primary" href="{{ route('news.show', $newsItem) }}">Read more</a>
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
