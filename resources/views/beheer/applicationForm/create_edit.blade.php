@extends('layouts.beheer')

@section('title', 'Add an application form')

@section('content')
    @include('includes.flash')
    <x-validation-errors />

    <div id="app">
        {{ html()->form($applicationForm->exists ? 'PUT' : 'POST', $applicationForm->exists ? route('beheer.application-forms.update', $applicationForm) : route('beheer.application-forms.store'))->open() }}
            <x-card title="Add an application form">
                {{--hidden field with the amount of formrows--}}
                <input name="amount_of_formrows" type="hidden" id="amount_of_formrows" value="0">
                <div class="form-row">
                    <x-field name="name" label="Name" :value="$applicationForm->name" col="6" required />
                </div>
            </x-card>
            <application-form :rows="{{ json_encode($rows) }}"></application-form>

            <div class="my-4">
                {{ html()->submit('Save')->class('btn btn-primary') }}
                <a class="btn btn-danger btn-close" href="{{ $applicationForm->exists ? route('beheer.application-forms.show', $applicationForm) : route('beheer.application-forms.index') }}">
                    {{'Cancel'}}
                </a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
