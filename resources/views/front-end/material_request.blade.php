@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/tempusdominus.css")}}">
@endpush

@section('title')
{{$curPageName}}
@endsection

@section('content')
<div class="container intro-container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{$curPageName}}</h2>
            {!!$content!!}
        </div>
    </div>
</div>
<div class="container">
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li class="ml-2">{{ $error}}</li>
            @endforeach
        </ul>
    @endif

    {!! Form::open(['url' => 'huren']) !!}
    <div class="card mt-4" id="materiaalsoort">
        <div class="card-header">
            <h3>{{trans('front-end/material.title')}}</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-5">
                    {!! Form::label('itemtype', trans('front-end/material.type')) !!}
                    {!! Form::text('itemtypeTest', '', ['class' => 'form-control','required' => 'required']) !!}                    
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('rentStart', trans('front-end/material.rentStart')) !!}
                    <div class="input-group date" id="rentStartBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="rentStart" name="rentStart" data-target="#rentStartBox" value="" required="required">
                        <div class="input-group-append" data-target="#rentStartBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
		</div>
                <div class="form-group col-md-6">
                    {!! Form::label('rentEnd', trans('front-end/material.rentEnd')) !!}
                    <div class="input-group date" id="birthDayBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="birthDay" name="birthDay" data-target="#birthDayBox" value="" required="required">
                        <div class="input-group-append" data-target="#birthDayBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div
        </div>
    </div>
        <div class="my-4">
        {!! Form::submit(trans('front-end/subscribe.submit'), ['class'=> 'btn btn-primary'] ) !!}
    </div>
    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
    <script src="{{asset("js/vendor/moment.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
    <script>
        $('#birthDayBox').datetimepicker({
            locale: 'nl',
            format: 'L',
            icons: {
                time: 'ion-clock',
                date: 'ion-calendar',
                up: 'ion-android-arrow-up',
                down: 'ion-android-arrow-down',
                previous: 'ion-chevron-left',
                next: 'ion-chevron-right',
                today: 'ion-calendar',
                clear: 'ion-trash-a',
                close: 'ion-close'
            }
        });
        $(function(){
            $('#birthDayBox').datetimepicker();
        })
        $(function () {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush
