@extends('layouts.app')

@section('content')

<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            {!! $content !!}
        </div>
    </div>
</div>

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
        <div class="row">
            @foreach($newsItems as $newsItem)
            <div class="col-sm-12 d-flex">
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
        <div class="row">
            @foreach($events as $event)
            <div class="col-sm-12 d-flex">
                <div class="card w-100 position-relative">
                    <a href="{{ route('events.show', $event) }}">
                        <img class="card-img-top" src="{{$event->image_url}}" alt="">
                    </a>
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{ $event->start_date->format('d M') }}</span>
                    <div class="card-body">
                    <a href="{{ route('events.show', $event) }}">

                            <h4 class="card-title">{{$event->title}}</h4>
                            <p class="card-text text-body">{{$event->short_description}}</p>
                    </a>
                    </div>
                    @if($event->registration_form_id != null)
                    <div class="card-footer">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <x-heroicon-o-user-group /> {{ $event->registrations_count }}
                            </div>
                            <div class="col-auto">
                                @guest
                                    @if($event->isRegistrationOpen())
                                        {{-- After logging in, the member lands on the registration page --}}
                                        <a class="btn btn-outline-primary" href="{{ route('events.registration.create', $event) }}">Log in to register</a>
                                    @endif
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
