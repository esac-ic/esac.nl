@extends('layouts.admin')

@section('title')
    Edit settings
@endsection

@section('content')
    <x-card title="Edit settings">
        {{ html()->form('PUT', route('admin.settings.update'))->open() }}
        @foreach($settings as $setting)
            <x-field :name="'setting['.$setting->name->value.']'" :label="$setting->name->label()" :value="$setting->value" />
        @endforeach
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger btn-close" href="{{ route('admin.settings.show') }}">Cancel</a>
        {{ html()->form()->close() }}
    </div>
@endsection
