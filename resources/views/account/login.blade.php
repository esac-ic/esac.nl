@extends('layouts.app')

@section('content')
<div class="container intro-container pb-3">
    {{ html()->form('PUT', route('user-profile-information.update'))->attribute('data-warn-unsaved')->open() }}
    <x-card title="Email address">
        @if (session('status') === 'profile-information-updated')
            <div class="alert alert-success" role="status">Your email address is saved.</div>
        @endif
        <div class="form-row">
            <x-field name="email" label="Email address" type="email" :value="auth()->user()->email" col="6" bag="updateProfileInformation" required autocomplete="email" />
            <x-field name="current_password" id="email_current_password" label="Current password" type="password" col="6" bag="updateProfileInformation" required autocomplete="current-password" />
        </div>
        {{ html()->submit('Save email address')->class('btn btn-primary') }}
    </x-card>
    {{ html()->form()->close() }}

    {{ html()->form('PUT', route('user-password.update'))->class('mt-4')->open() }}
    <x-card title="Password">
        @if (session('status') === 'password-updated')
            <div class="alert alert-success" role="status">Your password is saved.</div>
        @endif
        <div class="form-row">
            <x-field name="current_password" id="password_current_password" label="Current password" type="password" col="12" bag="updatePassword" required autocomplete="current-password" />
        </div>
        <div class="form-row">
            <x-field name="password" label="New password" type="password" col="6" bag="updatePassword" required autocomplete="new-password" />
            <x-field name="password_confirmation" label="Confirm new password" type="password" col="6" bag="updatePassword" required autocomplete="new-password" />
        </div>
        {{ html()->submit('Save password')->class('btn btn-primary') }}
    </x-card>
    {{ html()->form()->close() }}

    <div class="my-4">
        <a class="btn btn-outline-secondary" href="{{ route('account.show') }}">Back to your account</a>
    </div>
</div>
@endsection
