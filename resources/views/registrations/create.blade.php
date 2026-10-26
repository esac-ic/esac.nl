@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <x-validation-errors />
    {{ html()->form('POST', $action)->open() }}
        <x-card :title="$registrationForm->name">
            <div data-vue="registration-form" data-props="{{ json_encode(['rows' => $fields, 'old' => (object) session()->getOldInput()]) }}"></div>
        </x-card>

        <div class="my-4">
            {{ html()->submit('Register')->class('btn btn-primary') }}
            <a class="btn btn-outline-secondary" href="{{ $cancelUrl }}">Cancel</a>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
