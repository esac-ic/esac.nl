@extends('layouts.app')
@section('content')
<div class="container intro-container pb-3">
    <div class="card">
        <div class="position-relative">
            @if($newsItem->image_url != "")
            <img class="card-img-top" src="{{$newsItem->getImageUrl()}}" alt="Card image cap">
            @endif
            <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{\Carbon\Carbon::parse($newsItem->created_at)->format('d M')}}</span>
        </div>
        <div class="card-body">
            <h4 class="card-title">{{$newsItem->title}}</h4>
            <p class="card-text text-body">
                {!! $newsItem->text !!}
            </p>
        </div>
        <div class="card-footer bg-white p-3">
            <div class="row justify-content-between">
                <div class="col-auto text-muted">
                    <span class="ion-person"></span> {{$newsItem->author}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
