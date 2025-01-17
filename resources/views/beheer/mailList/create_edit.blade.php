@extends('layouts.beheer')

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif
    <div class="card">
        <div class="card-header">
            <h3>{{$fields['title']}}</h3>
        </div>
        <div class="card-body">
            {{ html()->form($fields['method'], $fields['url'])->open() }}
            {{ html()->hidden('id')->value(($mailList != null) ? $mailList->getAddress() : '') }}
            <div class="form-group">
                {{ html()->label('Email', 'address') }}
                <div class="input-group">
                    {{ html()->text('address')
                        ->value(($mailList != null) ? explode('@',$mailList->getAddress())[0] : '')
                        ->class('form-control')
                        ->required()
                        ->attribute('aria-describedby', 'basic-addon3') }}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon3">{{env("MAIL_MAN_DOMAIN")}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->button('Save')->type('submit')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ ($mailList == null) ? ('/mailList') : ('/mailList/' . $mailList->address)}}">{{'Cancel'}}</a>
    </div>
@endsection
