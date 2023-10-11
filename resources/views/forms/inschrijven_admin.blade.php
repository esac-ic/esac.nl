@extends('layouts.beheer')

@section('title')
{{$applicationForm->name}}
@endsection

@section('content')
    <div id="app">
        {!! Form::open(['method' => 'POST', 'url' => "forms/admin/" . $agendaItem->id]) !!}
            {{ csrf_field() }}
            <div class="card">
                <div class="card-header">
                    <h3>{{$applicationForm->name}}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {!! Form::label('user', 'Member who will be registered:') !!}
                        {!! Form::select('user',$users,null, ['class' => 'form-control','required' => 'required']) !!}
                    </div>
                    <registration-form rows="{{ json_encode($rows) }}"></registration-form>
                </div>
            </div>

            <div class="my-4">
                {!! Form::submit('Submit', ['class'=> 'btn btn-primary'] ) !!}
                <a class="btn btn-danger btn-close" href="{{url('/forms/users/'. $agendaItem->id)}}">{{'Cancel'}}</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
