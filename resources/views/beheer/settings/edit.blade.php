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
            {{ html()->form('POST', route('beheer.settings.update'))->acceptsFiles()->open() }}
            @method('PUT')
            @foreach($settings as $setting)
                <div class="form-group">
                    {{ html()->label(trans('settings.' . $setting->name), 'setting['. $setting->name . ']') }}
                    {{ html()->text('setting['. $setting->name . ']', $setting->value)->class('form-control') }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger btn-close" href="{{ route('beheer.settings.index') }}">{{'Cancel'}}</a>
        {{ html()->form()->close() }}
    </div>
@endsection
