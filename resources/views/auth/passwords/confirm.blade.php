@extends('layouts.master')

@section('title', 'Confirm password')

@section('main')
    <x-auth-card heading="Confirm password" subheading="Please confirm your password before continuing">
        <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('password.confirm.store') }}">
            @csrf
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control form-control-lg" id="password" type="password" name="password" required autofocus autocomplete="current-password">
                @error('password')
                <span class="help-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-lg btn-primary">Confirm password</button>
            </div>
        </form>
    </x-auth-card>
@endsection
