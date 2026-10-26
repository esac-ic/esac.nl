@extends('layouts.app')

@section('content')
    <div class="container intro-container pb-3">
        <div class="row d-flex align-items-stretch">
            <div class="col-lg-4 d-flex flex-wrap">
                <div class="card w-100">
                    <img class="card-img-top" src="{{$event->image_url}}" alt="">
                    <div class="card-body">
                        <h3 class="card-title">{{$event->title}}</h3>
                        @php($sameDay = $event->start_date->isSameDay($event->end_date))
                        <p class="card-text mb-2">
                            <x-heroicon-o-calendar class="text-muted mr-1" />
                            {{ $event->start_date->format('D j M Y, H:i') }} –
                            {{ $event->end_date->format($sameDay ? 'H:i' : 'D j M Y, H:i') }}
                        </p>
                        @if($event->isRegistrationOpen())
                            <p class="card-text text-muted">
                                Registration closes {{ $event->registration_deadline->format('D j M, H:i') }}
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                @if($event->registration_form_id != null)
                                    <x-heroicon-o-user-group /> {{ $event->registrations_count }}
                                @endif
                            </div>
                            <div class="col-auto">
                                @if($event->registration_form_id != null)
                                    @guest
                                        @if($event->isRegistrationOpen())
                                            {{-- After logging in, the member lands on the registration page --}}
                                            <a class="btn btn-outline-primary" href="{{ route('events.registration.create', $event) }}">Log in to register</a>
                                        @endif
                                    @else
                                        @if($event->isRegistrationOpen())
                                            @if($registrations->contains('user_id', auth()->id()))
                                                <form method="POST" action="{{ route('events.registration.destroy', $event) }}" data-confirm-title="Unregister" data-confirm="Unregister from {{ $event->title }}?" data-confirm-button="Unregister">
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
                                @forelse($registrations as $registration)
                                    <li>{{ $registration->user->name }}
                                        @if($event->shows_certificates && $registration->user->certificates->isNotEmpty())
                                            ({{ $registration->user->certificate_abbreviations }})
                                        @endif
                                    </li>
                                @empty
                                    <p class="text-muted">No registrations yet.</p>
                                @endforelse
                            </ol>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <a class="btn btn-outline-primary" href="{{ route('events.index') }}"><x-heroicon-o-arrow-left /> Back to events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
