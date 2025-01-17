@extends('layouts.master')

@section('title')
    {{'Reset password'}}
@endsection

@component('includes.menu')
    @slot('navClass', 'bg-dark position-fixed')
    @slot('navId', '')
@endcomponent

@section('main')
<section>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card card-lg text-center mt-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <h1 class="h2 mb-2">{{'Welcome back'}}</h1>
                            <span>{{'Reset password'}}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            {{ html()->form('POST', '/password/email')->class('text-left col-lg-8')->open() }}
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    {{ html()->label('Email adress')->for('email') }}
                                    {{ html()->email('email')
                                        ->class('form-control form-control-lg')
                                        ->placeholder('Email adress')
                                        ->required()
                                        ->autofocus() }}
                                    
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ 'Email address not known' }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="text-center mt-3">
                                    {{ html()->button('Send password reset link')
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
</section>
@endsection
