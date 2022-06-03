@extends('layouts.beheer')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/bootstrap-select.css")}}">
@endpush

@php
$create = $package == null;
@endphp

@section('title')
{{ $create ? trans('intro.packageCreate') : trans('intro.packageEdit') }}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{ $create ? trans('intro.packageCreate') : trans('intro.packageEdit') }}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => $create ? 'POST' : 'PATCH', 'url' => route('beheer.intro.packages.' . ($create ? 'store' : 'update'), $package)]) !!}
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('NL_text', trans('intro.packageName') . ' NL') !!}
                    {!! Form::text('NL_text', $create ? '' : $package->packageName->NL_text, ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('EN_text', trans('intro.packageName') . ' EN') !!}
                    {!! Form::text('EN_text', $create ? '' : $package->packageName->EN_text, ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('startDate', trans('intro.packageDeadline')) !!}
                    <div class="input-group date" id="deadlineBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="deadline"
                               name="deadline"
                               data-target="#deadlineBox"
                               value="{{ ($create ? \Carbon\Carbon::now() : $package->deadline)->format('d-m-Y') }}" required/>
                        <div class="input-group-append" data-target="#deadlineBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('startDate', trans('intro.packageForm')) !!}
                    <div class="input-group date" id="deadlineBox" data-target-input="nearest">
                        <select class="selectpicker w-100" id="application-select" data-live-search="true" name="application_form_id">
                            @foreach($applicationForms as $applicationForm)
                                <option value="{{ $applicationForm->id }}">
                                    {{ $applicationForm->applicationFormName->text() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ route('beheer.intro.packages.' . ($create ? 'index' : 'show'), $package) }}">{{trans('menu.cancel')}}</a>
    </div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/moment.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/bootstrap-select.js")}}" type="text/javascript"></script>
<script>
    $('#deadlineBox').datetimepicker({
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

    $(document).ready(function() {
        //$.fn.selectpicker.Constructor.BootstrapVersion = '4';
        $('#deadlineBox').datetimepicker();
        $('#application-select').selectpicker();
    });
</script>
@endpush
