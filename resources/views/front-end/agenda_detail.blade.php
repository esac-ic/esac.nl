@extends('layouts.app')

@section('content')
    <div class="container intro-container pb-3">
        <div class="row d-flex align-items-stretch">
            <div class="col-lg-4 d-flex flex-wrap">
                <div class="card w-100">
                    <img class="card-img-top" src="{{$agendaItem->getImageUrl()}}">
                    <div class="card-body">
                        <h3 class="card-title">{{$agendaItem->title}}</h3>
                        <p class="card-text">{{'From'}} <span class="badge badge-secondary font-size-100">{{trans(\Carbon\Carbon::parse($agendaItem->startDate)->format('d M H:i'))}}</span> {{'to'}} <span class="badge badge-secondary font-size-100">{{trans(\Carbon\Carbon::parse($agendaItem->endDate)->format('d M H:i'))}}</span></p>
                    </div>
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                @if($agendaItem->application_form_id != null)
                                    <span class="ion-person-stalker"></span> {{count($agendaItem->getApplicationFormResponses)}}
                                @endif
                            </div>
                            <div class="col-auto">
                                @if($agendaItem->application_form_id != null)
                                    @if(Auth::guest())
                                        {{'Please log in to register'}}
                                    @else
                                        @if($agendaItem->canRegister())
                                            @if($agendaItem->subscription_endDate > \Carbon\Carbon::now() && array_key_exists(Auth::id(), $users))
                                                <a class="btn btn-outline-danger" href="{{ url('forms/' . $agendaItem->id .'/unregister') }}">{{ 'Unregister' }} </a>
                                            @else
                                                <a class="btn btn-outline-primary" href="{{url('')}}/forms/{{$agendaItem->id}}">{{'Register now'}}</a>
                                            @endif
                                        @else
                                        {{'Registration not possible'}}
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h4 class="card-title">{{'General information'}}</h4>
                        <p class="card-text">{!! $agendaItem->text !!}</p>
                        @if($agendaItem->application_form_id != null && !Auth::guest())
                            <h4 class="card-title">{{'Registrations'}}</h2>
                            <ol class="column-count-lg-3">
                                @foreach($users as $user)
                                    <li>{{$user['name']}}
                                        @if($user["certificate_names"] != null)
                                            ({{$user["certificate_names"]}})
                                        @endif

                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
