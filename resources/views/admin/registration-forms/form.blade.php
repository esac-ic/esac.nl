@extends('layouts.admin')

@section('title', $registrationForm->exists ? 'Edit registration form' : 'New registration form')

@section('content')
    <x-model-form :model="$registrationForm" resource="admin.registration-forms">
        <x-card :title="$registrationForm->exists ? 'Edit registration form' : 'New registration form'">
            <div class="form-row">
                <x-field name="name" label="Name" :value="$registrationForm->name" col="6" required />
            </div>
        </x-card>
        <div data-vue="application-form" data-props="{{ json_encode(['rows' => $fields, 'types' => $types]) }}"></div>
    </x-model-form>
@endsection
