@extends('layouts.app')

@section('content')
    <div class="container intro-container pb-3">
        <div class="row d-flex align-items-stretch">
            <div class="col-lg-4 d-flex flex-wrap">
                <div class="card w-100">
                    <img class="card-img-top" src="{{$agendaItem->getImageUrl()}}">
                    <div class="card-body">
                        <h3 class="card-title">{{$agendaItem->title}}</h3>
                        <p class="card-text">
                            From
                            <span class="badge badge-secondary font-size-100">{{ $agendaItem->start_date->format('d M H:i') }}</span>
                            to
                            <span class="badge badge-secondary font-size-100">{{ $agendaItem->end_date->format('d M H:i') }}</span>
                        </p>
                        @if($agendaItem->canRegister())
                            <p class="card-text">
                                Subscriptions close
                                <span class="badge badge-secondary font-size-100">{{ $agendaItem->subscription_end_date->format('d M H:i') }}</span>
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                @if($agendaItem->application_form_id != null)
                                    <span class="ion-person-stalker"></span> {{ $registrations->count() }}
                                @endif
                            </div>
                            <div class="col-auto">
                                @if($agendaItem->application_form_id != null)
                                    @guest
                                        Please log in to register
                                    @else
                                        @if($agendaItem->canRegister())
                                            @if($registrations->contains('user_id', auth()->id()))
                                                <form method="POST" action="{{ route('forms.unregister', $agendaItem) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">Unregister</button>
                                                </form>
                                            @else
                                                <a class="btn btn-outline-primary" href="{{ route('forms.create', $agendaItem) }}">Register now</a>
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
                        <p class="card-text">{!! $agendaItem->text !!}</p>
                        @if($agendaItem->application_form_id != null && auth()->check())
                            <h4 class="card-title">Registrations</h4>
                            <ol class="column-count-lg-3">
                                @foreach($registrations as $registration)
                                    <li>{{ $registration->user->getName() }}
                                        @if($agendaItem->climbing_activity && $registration->user->certificates->isNotEmpty())
                                            ({{ $registration->user->getCertificationsAbbreviations() }})
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                    <div class="card-footer bg-white p-3">
                        <a class="btn btn-outline-primary" href="{{ route('front-end.agenda') }}"><span class="ion-android-arrow-back"></span> Back to the agenda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
