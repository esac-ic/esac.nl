@extends('layouts.beheer')

@section('title')
    {{ 'Setting' }}
@endsection

@section('content')
    @include('includes.flash')

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Setting'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a class="mr-1 ml-1" href="{{ route('beheer.settings.edit') }}" class="btn btn-primary">
                    <span title="{{ 'Edit settings' }}" class="ion-edit font-size-120" aria-hidden="true"></span>
                    {{ 'Edit settings' }}
                </a>
            </div>
        </div>
    </div>
    <table id="settings-table" class="table table-striped table-bordered">
        <thead>
            <th>{{ 'Name' }}</th>
            <th>{{ 'Value' }}</th>
        </thead>
        <tbody>
        @foreach($settings as $setting)
            <tr>
                <td>{{trans('settings.' . $setting->name)}}</td>
                <td>
                    {{$setting->value}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
