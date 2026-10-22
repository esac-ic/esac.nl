@extends('layouts.master')

@section('title', 'Reset password')

@section('main')
    <x-auth-card heading="Welcome back" subheading="Reset password">
        <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div @class(['form-group', 'has-error' => $errors->has('email')])>
                <label for="email">Email adress</label>
                <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="Email adress" value="{{ $email ?? old('email') }}" required autofocus>
                @error('email')
                <span class="help-block">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div @class(['form-group', 'has-error' => $errors->has('password')])>
                <label for="password">Password</label>
                <input class="form-control form-control-lg" id="password" type="password" name="password" placeholder="Password" required>
                @error('password')
                <span class="help-block">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div @class(['form-group', 'has-error' => $errors->has('password_confirmation')])>
                <label for="password-confirm">Confirm password</label>
                <input class="form-control form-control-lg" placeholder="Confirm password" id="password-confirm" type="password" name="password_confirmation" required>
                @error('password')
                <span class="help-block">
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
