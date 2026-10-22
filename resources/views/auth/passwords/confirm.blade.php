@extends('layouts.master')

@section('title')
    {{'Confirm password'}}
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
                            <h1 class="h2 mb-2">{{'Confirm password'}}</h1>
                            <span>{{'Please confirm your password before continuing'}}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            <form class="text-left col-lg-8" role="form" method="POST" action="{{ route('password.confirm.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="password">{{'Password'}}</label>
                                    <input class="form-control form-control-lg" id="password" type="password" name="password" required autofocus autocomplete="current-password">
                                    @error('password')
                                    <span class="help-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-lg btn-primary">{{'Confirm password'}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
