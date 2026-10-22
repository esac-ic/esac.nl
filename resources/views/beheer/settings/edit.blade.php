@extends('layouts.beheer')

@section('title')
    {{ 'Edit settings' }}
@endsection

@section('content')
    <x-card title="Edit settings">
        {{ html()->form('POST', route('beheer.settings.update'))->acceptsFiles()->open() }}
        @method('PUT')
        @foreach($settings as $setting)
            <x-field :name="'setting['.$setting->name.']'" :label="trans('settings.'.$setting->name)" :value="$setting->value" />
        @endforeach
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger btn-close" href="{{ route('beheer.settings.index') }}">{{'Cancel'}}</a>
        {{ html()->form()->close() }}
    </div>
@endsection
