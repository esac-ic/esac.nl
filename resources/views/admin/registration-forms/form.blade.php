@extends('layouts.admin')

@section('title', $registrationForm->exists ? 'Edit registration form' : 'New registration form')

@section('content')
    <x-model-form :model="$registrationForm" resource="admin.registration-forms">
        <x-card :title="$registrationForm->exists ? 'Edit registration form' : 'New registration form'">
            <div class="form-row">
                <x-field name="name" label="Name" :value="$registrationForm->name" col="6" required />
            </div>
        </x-card>
        <application-form :rows="{{ json_encode($fields) }}"></application-form>
    </x-model-form>
@endsection
