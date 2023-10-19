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
                <h3>{{'Latest news'}}</h3>
            </div>
            <div class="col-auto">
                <a href="{{url('news')}}" class="btn btn-outline-primary">{{'See more'}}</a>
            </div>
        </div>
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($newsItems as $newsItem)
            <div class="col-sm-12 d-flex flex-wrap">
                <div class="card w-100">
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
            @endforeach
        </div>
    </div>
    <div class="col-sm-4 mt-5 mt-sm-0">
        <div class="row justify-content-between mb-4">
            <div class="col-auto">
                <h3>{{'Activities'}}</h3>
            </div>
            <div class="col-auto">
                <a href="{{url('agenda')}}" class="btn btn-outline-primary">{{'See more'}}</a>
            </div>
        </div>
        <div class="row d-flex align-items-stretch align-items-center">
            @foreach($agendaItems as $agendaItem)
            <div class="col-sm-12 d-flex flex-wrap">
                <div class="card w-100 position-relative">
                    <a href="/agenda/{{$agendaItem->id}} ">
                        <img class="card-img-top " src="{{$agendaItem->getImageUrl()}}" alt="Card image cap">
                    </a>
                    <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{\Carbon\Carbon::parse($agendaItem->startDate)->format('d M')}}</span>
                    <div class="card-body">
                    <a href="/agenda/{{$agendaItem->id}} ">

                            <h4 class="card-title">{{$agendaItem->title}}</h4>
                            <p class="card-text text-body">{{$agendaItem->shortDescription}}</p>
                    </a>
                    </div>
                    @if($agendaItem->application_form_id != null)
                    <div class="card-footer bg-white p-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto text-muted">
                                <span class="ion-person-stalker"></span> {{count($agendaItem->getApplicationFormResponses)}}
                            </div>
                            <div class="col-auto">
                                @if(Auth::guest())
                                    {{'Please log in to register'}}
                                @else
                                    @if($agendaItem->canRegister())
                                        <a class="btn btn-outline-primary" href="{{url('')}}/forms/{{$agendaItem->id}}">{{'Register now'}}</a>
                                    @else
                                        {{'Registration not possible'}}
                                    @endif
                                @endif
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
