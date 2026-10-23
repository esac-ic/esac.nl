@extends('layouts.app')

@section('content')
<div class="container intro-container pb-3">
    {{ html()->form('PUT', route('account.login.update'))->attribute('data-warn-unsaved')->open() }}
    <x-card :title="$pageTitle">
        <div class="form-row">
            <x-field name="email" label="Email address" type="email" :value="$user->email" col="12" required autocomplete="email" />
        </div>
        <div class="form-row">
            <x-field name="password" label="New password" type="password" col="6" autocomplete="new-password" placeholder="Leave empty to keep your password" />
            <x-field name="password_confirmation" label="Confirm new password" type="password" col="6" autocomplete="new-password" />
        </div>
        <hr class="mt-0">
        <div class="form-row">
            <x-field name="current_password" label="Current password" type="password" col="6" required autocomplete="current-password" />
        </div>
        <p class="text-muted text-small mb-0">Your current password is needed to change your email address or password.</p>
    </x-card>
    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        <a class="btn btn-outline-secondary" href="{{ route('account.show') }}">Cancel</a>
    </div>
    {{ html()->form()->close() }}
</div>
@endsection
