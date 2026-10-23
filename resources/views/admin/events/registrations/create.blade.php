@extends('layouts.admin')

@section('title')
{{$registrationForm->name}}
@endsection

@section('content')
    <x-page-header :title="'New registration: '.$event->title" />
    <x-validation-errors />
    <div id="app">
        {{ html()->form('POST', route('admin.events.registrations.store', $event))->open() }}
            <x-card :title="$registrationForm->name">
                <x-field name="user_id" label="Member" :options="$users" required />
                <registration-form rows="{{ json_encode($fields) }}"></registration-form>
            </x-card>

            <div class="my-4">
                {{ html()->submit('Save')->class('btn btn-primary') }}
                <a class="btn btn-outline-secondary" href="{{route('admin.events.registrations.index', $event)}}">Cancel</a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
