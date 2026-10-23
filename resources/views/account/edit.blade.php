@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <x-validation-errors />

    <h1>{{ $pageTitle }}</h1>

    {{ html()->form('PATCH', route('account.update'))->open() }}
    <x-member-fields :$user :$memberTypes locked />
    <div class="form-row">
        <x-field name="current_password" label="Current password, to change your email address or password" type="password" col="6" />
    </div>
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-danger" href="{{ route('account.show') }}">Cancel</a>
    </div>
    {{ html()->form()->close() }}
</div>
@endsection
