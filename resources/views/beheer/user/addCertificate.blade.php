@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
<div class="container">
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}}</li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header"><h3>{{$fields['title']}}</h3></div>
        <div class="card-body">
            {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            <div class="form-group">
                {!! Form::label('certificate_id', trans('certificate.certificaat')) !!}
                {!! Form::select('certificate_id',$certificates, ($userCertificate != null) ? $userCertificate->certificate_id : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('startDate', trans('certificate.certificaat')) !!}
                <div class='input-group date'>
                    <input type='text' class="form-control" id="datetimepicker1" name="startDate"  value="{{($userCertificate != null) ? \Carbon\Carbon::parse($userCertificate->pivot->startDate)->format('d-m-Y') : \Carbon\Carbon::now()->format('d-m-Y')}}"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ URL::previous() }}">{{trans('menu.cancel')}}</a>
    </div>
</div>
@endsection