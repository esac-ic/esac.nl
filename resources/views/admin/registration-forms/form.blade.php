@extends('layouts.admin')

@section('title', $registrationForm->exists ? 'Edit registration form' : 'New registration form')

@section('content')
    <x-validation-errors />

    <div id="app">
        {{ html()->form($registrationForm->exists ? 'PUT' : 'POST', $registrationForm->exists ? route('admin.registration-forms.update', $registrationForm) : route('admin.registration-forms.store'))->open() }}
            <x-card :title="$registrationForm->exists ? 'Edit registration form' : 'New registration form'">
                <div class="form-row">
                    <x-field name="name" label="Name" :value="$registrationForm->name" col="6" required />
                </div>
            </x-card>
            <application-form :rows="{{ json_encode($fields) }}"></application-form>

            <div class="my-4">
                {{ html()->submit('Save')->class('btn btn-primary') }}
                <a class="btn btn-danger" href="{{ $registrationForm->exists ? route('admin.registration-forms.show', $registrationForm) : route('admin.registration-forms.index') }}">
                    Cancel
                </a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
