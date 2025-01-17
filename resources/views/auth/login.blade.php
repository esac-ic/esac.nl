@extends('layouts.master')

@section('title')
{{'Login'}}
@endsection

@section('main')
    @component('includes.menu')
        @slot('navClass', 'bg-dark position-fixed')
        @slot('navId', '')
    @endcomponent

<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card card-lg text-center mt-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h1 class="h2 mb-2">{{'Welcome back'}}</h1>
                            <span>{{'Sign in below'}}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            <div class="text-left col-lg-8">
                                {{ html()->form('POST', url('/login'))->open() }}
                                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                        {{ html()->label('Email adress')->for('email') }}
                                        {{ html()->email('email')
                                            ->class('form-control form-control-lg')
                                            ->placeholder('Email adress')
                                            ->required()
                                            ->autofocus() }}
                                        @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                        {{ html()->label('Password')->for('password') }}
                                        {{ html()->password('password')
                                            ->class('form-control form-control-lg')
                                            ->placeholder('Password')
                                            ->required() }}
                                        @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                        <p class="text-muted text-small mt-2">{{'Forgot password?'}} <a href="{{ url('/password/reset') }}">{{'Reset password'}}</a></p>
                                    </div>
                                    <div>
                                        <div class="custom-control custom-checkbox align-items-center">
                                            {{ html()->checkbox('remember')
                                                ->class('custom-control-input')
                                                ->checked()
                                                ->id('remember') }}
                                            {{ html()->label('Remember me')
                                                ->class('custom-control-label text-small')
                                                ->for('remember') }}
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        {{ html()->button('Login')
                                            ->type('submit')
                                            ->class('btn btn-lg btn-primary') }}
                                    </div>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
