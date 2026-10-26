@extends('layouts.admin')

@section('title')
{{$registrationForm->name}}
@endsection

@section('content')
    <x-page-header :title="'New registration: '.$event->title" />
    <x-validation-errors />
    {{ html()->form('POST', route('admin.events.registrations.store', $event))->open() }}
        <x-card :title="$registrationForm->name">
            <x-field name="user_id" label="Member" :options="$users" required />
            <div data-vue="registration-form" data-props="{{ json_encode(['rows' => $fields]) }}"></div>
        </x-card>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
            <a class="btn btn-outline-secondary" href="{{route('admin.events.registrations.index', $event)}}">Cancel</a>
        </div>
    {{ html()->form()->close() }}
@endsection
