@extends('layouts.app')

@section('content')

<section class="intro-container">
    <div class="container intro-container">
        <div class="card">
            <div class="card-body">
                {!! $content !!}
            </div>
        </div>
    </div>
</section>

<section>
<div class="container">
    <div class="row">
    <div class="col-sm-8 pr-sm-5">
        <div class="row justify-content-between mb-4">
            <div class="col-auto">
                <h3>Latest news</h3>
            </div>
            <div class="col-auto">
                <a href="{{ route('news.index') }}" class="btn btn-outline-primary">See more</a>
            </div>
        </div>
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($newsItems as $newsItem)
            <div class="col-sm-12 d-flex flex-wrap">
                <x-news-card :$newsItem class="w-100" />
            </div>
            @endforeach
        </div>
    </div>
    <div class="col-sm-4 mt-5 mt-sm-0">
        <div class="row justify-content-between mb-4">
            <div class="col-auto">
                <h3>Events</h3>
            </div>
            <div class="col-auto">
                <a href="{{ route('events.index') }}" class="btn btn-outline-primary">See more</a>
            </div>
        </div>
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($events as $event)
            <div class="col-sm-12 d-flex flex-wrap">
                <div class="card w-100 position-relative">
                    <a href="{{ route('events.show', $event) }}">
                        <img class="card-img-top " src="{{$event->image_url}}" alt="">
                    </a>
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{ $event->start_date->format('d M') }}</span>
                    <div class="card-body">
                    <a href="{{ route('events.show', $event) }}">

                            <h4 class="card-title">{{$event->title}}</h4>
                            <p class="card-text text-body">{{$event->short_description}}</p>
                    </a>
                    </div>
                    @if($event->registration_form_id != null)
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <span class="ion-person-stalker"></span> {{ $event->registrations_count }}
                            </div>
                            <div class="col-auto">
                                @guest
                                    Please log in to register
                                @else
                                    @if($event->isRegistrationOpen())
                                        <a class="btn btn-outline-primary" href="{{ route('events.registration.create', $event) }}">Register now</a>
                                    @else
                                        Registration not possible
                                    @endif
                                @endguest
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
</div>
</section>
@endsection
