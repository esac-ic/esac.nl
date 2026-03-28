@extends('layouts.beheer')

@section('title')
{{'Penno export'}}
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
            <h3>Penno export</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => 'GET', 'url' => '/users/pennoExport']) !!}
            <div class="form-group">
                {!! Form::label('daysAgo', 'Amount of days in the past') !!}
                {!! Form::number('daysAgo', 5, ['class' => 'form-control','required' => 'required']) !!}
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit('Get', ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="/users">{{'Cancel'}}</a>
    </div>
@endsection