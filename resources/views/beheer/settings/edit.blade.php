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
            {{ html()->form('PUT', '/beheer/settings')->attribute('files', true)->open() }}
            @foreach($settings as $setting)
                <div class="form-group">
                    {{ html()->label(trans('settings.' . $setting->name), 'setting['. $setting->name . ']') }}
                    {{ html()->text('setting['. $setting->name . ']')->value($setting->value)->class('form-control') }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger btn-close" href="{{url('/beheer/settings')}}">{{'Cancel'}}</a>
        {{ html()->form()->close() }}
    </div>
@endsection
