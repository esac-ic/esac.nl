@extends('layouts.master')

@section('title', 'Reset password')

@section('main')
    <x-auth-card heading="Welcome back" subheading="Reset password">
        <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">Email address</label>
                <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="Email address" value="{{ $email ?? old('email') }}" required autofocus>
                @error('email')
                <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control form-control-lg" id="password" type="password" name="password" placeholder="Password" required>
                @error('password')
                <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input class="form-control form-control-lg" placeholder="Confirm password" id="password_confirmation" type="password" name="password_confirmation" required>
                @error('password')
                <span class="invalid-feedback d-block">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-lg btn-primary">Reset password</button>
            </div>
        </form>
    </x-auth-card>
@endsection
