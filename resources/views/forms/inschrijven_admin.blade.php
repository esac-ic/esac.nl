@extends('layouts.beheer')

@section('title')
{{$applicationForm->name}}
@endsection

@section('content')
    <div id="app">
        {{ html()->form('POST', route('beheer.agenda-items.registrations.store', $agendaItem))->open() }}
            <x-card :title="$applicationForm->name">
                <x-field name="user" label="Member who will be registered:" :options="$users" required />
                <registration-form rows="{{ json_encode($rows) }}"></registration-form>
            </x-card>

            <div class="my-4">
                {{ html()->submit('Submit')->class('btn btn-primary') }}
                <a class="btn btn-danger btn-close" href="{{route('beheer.agenda-items.registrations.index', $agendaItem)}}">Cancel</a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
