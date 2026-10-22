@extends('layouts.master')

@section('title', 'Reset password')

@section('main')
    <x-auth-card heading="Welcome back" subheading="Reset password">
        <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div @class(['form-group', 'has-error' => $errors->has('email')])>
                <label for="email">Email adress</label>
                <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="Email adress" value="{{ old('email') }}" required autofocus>
                @error('email')
                <span class="help-block">
                <strong>Email address not known</strong>
                </span>
                @enderror
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-lg btn-primary">Send password reset link</button>
            </div>
        </form>
    </x-auth-card>
@endsection
