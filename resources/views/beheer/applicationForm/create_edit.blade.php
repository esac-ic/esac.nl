@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @include('includes.flash')
    <x-validation-errors />

    <div id="app">
        {{ html()->form($fields['method'], $fields['url'])->open() }}
            <x-card :title="$fields['title']">
                {{--hidden field with the amount of formrows--}}
                <input name="amount_of_formrows" type="hidden" id="amount_of_formrows" value="0">
                <div class="form-row">
                    <x-field name="name" label="Name" :value="$applicationForm?->name" col="6" required />
                </div>
            </x-card>
            <application-form :rows="{{ json_encode($rows) }}"></application-form>

            <div class="my-4">
                {{ html()->submit('Save')->class('btn btn-primary') }}
                <a class="btn btn-danger btn-close" href="{{ ($applicationForm == null) ? route('beheer.applicationForms.index') : route('beheer.applicationForms.show', $applicationForm->id)}}">
                    {{'Cancel'}}
                </a>
            </div>
        {{ html()->form()->close() }}
    </div>
@endsection
