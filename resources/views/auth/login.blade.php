@extends('layouts.master')

@section('title')
{{trans("auth.login")}}
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
                            <h1 class="h2 mb-2">{{trans("auth.welcome")}}</h1>
                            <span>{{trans("auth.resetPw")}}</span>
                        </div>
                        <div class="row no-gutters justify-content-center">
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            <form class="text-left col-lg-8" role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}
                                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">{{trans("auth.email")}}</label>
                                    <input class="form-control form-control-lg" id="email" type="email" name="email" placeholder="{{trans("auth.email")}}" value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password">{{trans("auth.password")}}</label>
                                    <input class="form-control form-control-lg" id="password" type="password" name="password" placeholder="{{trans("auth.password")}}" required>
                                    @if ($errors->has('password'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                    <p class="text-muted text-small mt-2">{{trans("auth.forgot")}} <a href="{{ url('/password/reset') }}">{{trans("auth.resetPw")}}</a></p>
                                </div>
                                <div>
                                    <div class="custom-control custom-checkbox align-items-center">
                                        <input type="checkbox" class="custom-control-input" value="remember" name="remember" checked id="remember">
                                        <label class="custom-control-label text-small" for="remember">{{trans("auth.remember")}}</label>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-lg btn-primary">{{trans("auth.login")}}</button>
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
