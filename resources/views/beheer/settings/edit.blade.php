@extends('layouts.beheer')

@section('title')
    {{ 'Edit settings' }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>{{'Edit settings'}}</h3>
        </div>
        <div class="card-body">
            {{Form::open(array('url'=>'/beheer/settings','method'=> 'PUT','files' => 'true'))}}
            @foreach($settings as $setting)
                <div class="form-group">
                    {{ Form::label('setting['. $setting->name . ']', trans('settings.' . $setting->name)) }}
                    {{Form::text('setting['. $setting->name . ']',$setting->value,['class' => 'form-control'])}}
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-4">
        {{Form::submit('Save', array('class' => 'btn btn-primary'))}}
        <a class="btn btn-danger btn-close" href="{{url('/beheer/settings')}}">{{'Cancel'}}</a>
        {!! Form::close() !!}
    </div>
@endsection
