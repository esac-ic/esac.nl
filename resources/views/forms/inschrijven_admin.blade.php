@extends('layouts.beheer')

@section('title')
{{$applicationForm->name}}
@endsection

@section('content')
    <div id="app">
        {{ html()->form('POST', "forms/admin/" . $agendaItem->id)->open() }}
            <div class="card">
                <div class="card-header">
                    <h3>{{$applicationForm->name}}</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ html()->label('Member who will be registered:', 'user') }}
                        {{ html()->select('user', $users)->class('form-control')->required() }}
                    </div>
                    <registration-form rows="{{ json_encode($rows) }}"></registration-form>
                </div>
            </div>

            <div class="my-4">
                {{ html()->submit('Submit')->class('btn btn-primary') }}
                <a class="btn btn-danger btn-close" href="{{url('/forms/users/'. $agendaItem->id)}}">{{'Cancel'}}</a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
