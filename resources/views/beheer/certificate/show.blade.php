@extends('layouts.beheer')

@section('title')
{{trans('certificate.certificaat')}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans('certificate.certificaat')}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/certificates/'.$certificate->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{url('/certificates/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'certificates/' . $certificate->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('rol.rol')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>
                        {{trans('certificate.name')}}
                    </td>
                    <td>
                        {{$certificate->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('certificate.abbreviation')}}
                    </td>
                    <td>
                        {{$certificate->abbreviation}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{trans('certificate.duration')}}
                    </td>
                    <td>
                        {{$certificate->duration === 0 ? "" : $certificate->duration}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
