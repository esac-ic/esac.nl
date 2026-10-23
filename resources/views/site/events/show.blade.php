@extends('layouts.app')

@section('content')
    <div class="container intro-container pb-3">
        <div class="row d-flex align-items-stretch">
            <div class="col-lg-4 d-flex flex-wrap">
                <div class="card w-100">
                    <img class="card-img-top" src="{{$event->image_url}}">
                    <div class="card-body">
                        <h3 class="card-title">{{$event->title}}</h3>
                        <p class="card-text">
                            From
                            <span class="badge badge-secondary font-size-100">{{ $event->start_date->format('d M H:i') }}</span>
                            to
                            <span class="badge badge-secondary font-size-100">{{ $event->end_date->format('d M H:i') }}</span>
                        </p>
                        @if($event->isRegistrationOpen())
                            <p class="card-text">
                                Registration closes
                                <span class="badge badge-secondary font-size-100">{{ $event->registration_deadline->format('d M H:i') }}</span>
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                @if($event->registration_form_id != null)
                                    <span class="ion-person-stalker"></span> {{ $event->registrations_count }}
                                @endif
                            </div>
                            <div class="col-auto">
                                @if($event->registration_form_id != null)
                                    @guest
                                        Please log in to register
                                    @else
                                        @if($event->isRegistrationOpen())
                                            @if($registrations->contains('user_id', auth()->id()))
                                                <form method="POST" action="{{ route('events.registration.destroy', $event) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">Unregister</button>
                                                </form>
                                            @else
                                                <a class="btn btn-outline-primary" href="{{ route('events.registration.create', $event) }}">Register now</a>
                                            @endif
                                        @else
                                        Registration not possible
                                        @endif
                                    @endguest
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h4 class="card-title">General information</h4>
                        <p class="card-text">{!! $event->body !!}</p>
                        @if($event->registration_form_id != null && auth()->check())
                            <h4 class="card-title">Registrations</h4>
                            <ol class="column-count-lg-3">
                                @foreach($registrations as $registration)
                                    <li>{{ $registration->user->name }}
                                        @if($event->shows_certificates && $registration->user->certificates->isNotEmpty())
                                            ({{ $registration->user->certificate_abbreviations }})
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <a class="btn btn-outline-primary" href="{{ route('events.index') }}"><span class="ion-android-arrow-back"></span> Back to the events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
