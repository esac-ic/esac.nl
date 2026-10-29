@extends('layouts.app')

@section('content')
<div class="container intro-container" id="app">
    <x-validation-errors />
    {{ html()->form('POST', $action)->open() }}
        <x-card :title="$registrationForm->name">
            <registration-form rows="{{ json_encode($fields) }}"></registration-form>
        </x-card>

        <div class="my-4">
            {{ html()->submit('Register')->class('btn btn-primary') }}
            <a class="btn btn-outline-secondary" href="{{ $cancelUrl }}">Cancel</a>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
