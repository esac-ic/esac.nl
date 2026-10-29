@extends('layouts.app')

@section('content')
<div class="container intro-container pb-3">
    {{ html()->form('PATCH', route('account.update'))->attribute('data-warn-unsaved')->open() }}
    <x-card :title="$pageTitle">
        <p class="mb-0">
            Keep your address, phone numbers and emergency contact up to date, so the association can reach you and your family.
            To change your email address or password, go to <a href="{{ route('account.login.edit') }}">email and password</a>.
        </p>
    </x-card>
    <div class="mt-4">
        <x-validation-errors />
    </div>
    <x-member-fields :$user :$memberTypes locked />
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-outline-secondary" href="{{ route('account.show') }}">Cancel</a>
    </div>
    {{ html()->form()->close() }}
</div>
@endsection
