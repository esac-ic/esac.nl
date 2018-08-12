@extends('layouts.master')

@section('title')
    {{trans("auth.resetPw")}}
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
                            <h1 class="h2 mb-2">{{trans("auth.welcome")}}</h1>
                            <span>{{trans("auth.resetPw")}}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form class="text-left col-lg-8" role="form" method="POST" action="{{ trans('/password/email') }}">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">{{trans("auth.email")}}</label>
                                    <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="{{trans("auth.email")}}" value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ trans("auth.emailFailed") }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-lg btn-primary">{{trans("auth.sendPwResetLing")}}</button>
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