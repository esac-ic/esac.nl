@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

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
            {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            <div class="form-group">
                {!! Form::label('name', trans('certificate.nameNL')) !!}
                {!! Form::text('NL_text', ($certificate != null) ? $certificate->certificateName->NL_text : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('name', trans('certificate.nameEN')) !!}
                {!! Form::text('EN_text', ($certificate != null) ? $certificate->certificateName->EN_text : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('abbreviation', trans('certificate.abbreviation')) !!}
                {!! Form::text('abbreviation', ($certificate != null) ? $certificate->abbreviation : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('duration', trans('certificate.duration')) !!}
                {!! Form::number('duration', ($certificate != null) ? $certificate->duration === 0 ? "0" : $certificate->duration : "", ['class' => 'form-control', 'min' => 0]) !!}
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($certificate == null) ? ('/certificates') : ('/certificates/' . $certificate->id)}}">{{trans('menu.cancel')}}</a>
    </div>
@endsection