@extends('layouts.master')

@section('title', 'Login')

@section('main')
    <x-auth-card heading="Welcome back" subheading="Sign in below">
        <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div @class(['form-group', 'has-error' => $errors->has('email')])>
                <label for="email">Email adress</label>
                <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="Email adress" value="{{ old('email') }}" required autofocus>
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
                <p class="text-muted text-small mt-2">Forgot password? <a href="{{ route('password.request') }}">Reset password</a></p>
            </div>
            <div>
                <div class="custom-control custom-checkbox align-items-center">
                    <input type="checkbox" class="custom-control-input" value="1" name="remember" checked id="remember">
                    <label class="custom-control-label text-small" for="remember">Remember me</label>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-lg btn-primary">Login</button>
            </div>
        </form>
    </x-auth-card>
@endsection
