@extends('layouts.app')

@section('content')
    <div class="container intro-container pb-3">
        <div class="row d-flex align-items-stretch">
            <div class="col-lg-4 d-flex flex-wrap">
                <div class="card w-100">
                    <img class="card-img-top" src="{{$agendaItem->getImageUrl()}}">
                    <div class="card-body">
                        <h3 class="card-title">{{$agendaItem->agendaItemTitle->text()}}</h3>
                        <p class="card-text">{{trans('menu.from')}} <span class="badge badge-secondary font-size-100">{{trans(\Carbon\Carbon::parse($agendaItem->startDate)->format('d M H:i'))}}</span> {{trans('menu.to')}} <span class="badge badge-secondary font-size-100">{{trans(\Carbon\Carbon::parse($agendaItem->endDate)->format('d M H:i'))}}</span></p>
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
                                        {{trans('front-end/agenda.loginNeeded')}}
                                    @else
                                        @if($agendaItem->canRegister())
                                        <a class="btn btn-outline-primary" href="{{url('')}}/forms/{{$agendaItem->id}}">{{trans('front-end/agenda.register')}}</a>
                                        @else
                                        {{trans('front-end/agenda.cantregister')}}
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
                        <h4 class="card-title">{{trans('front-end/agenda.info')}}</h4>
                        <p class="card-text">{!! clean($agendaItem->agendaItemText->text()) !!}</p>
                        @if($agendaItem->application_form_id != null && !Auth::guest())
                            <h4 class="card-title">{{trans('front-end/agenda.registrations')}}</h2>
                            <ol class="column-count-lg-3">
                                @foreach($users as $user)
                                    <li>{{$user['name']}}
                                        @if($user["certificate_names"] != null)
                                            ({{$user["certificate_names"]}})
                                        @endif
                                        @if($agendaItem->subscription_endDate > \Carbon\Carbon::now())
                                            <a href="{{ url('forms/' . $agendaItem->id .'/unregister') }}" style="color: red"><i class="ion-trash-a"></i> </a>
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
