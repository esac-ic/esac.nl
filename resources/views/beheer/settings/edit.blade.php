@extends('layouts.beheer')

@section('title')
    {{ trans('settings.edit') }}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('settings.edit')}}</div>
                    <div class="panel-body">
                        {{Form::open(array('url'=>'/settings','method'=> 'PUT','files' => 'true'))}}
                        <!-- Nav tabs -->
                            @foreach($settings as $setting)
                                <div class="form-group row">
                                    {{ Form::label('setting['. $setting->name . ']', trans('settings.' . $setting->name), array('class' => 'col-md-5 control-label')) }}
                                    <div class="col-lg-4">
                                        {{Form::text('setting['. $setting->name . ']',$setting->value,['class' => 'form-control'])}}
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-group row">
                                <div class="col-lg-offset-4 col-lg-4 pull-right">
                                    {{Form::submit(trans('menu.save'), array('class' => 'btn btn-primary'))}}
                                    <a href="{{url('/beheer/settings')}}" class="btn btn-danger">{{trans('Menu.cancel')}}</a>
                                </div>
                            </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

