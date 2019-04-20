@extends('layouts.beheer')

@section('title')
    {{ trans('settings.settings') }}
@endsection

@section('content')
    <div class="col-12" >

        <a href="{{ url('beheer/settings/edit') }}" class="btn btn-primary float-right" style="margin-bottom: 5px;">{{ trans('settings.edit') }}</a>

        <table id="settings-table" class="table table-striped table-bordered">
            <thead>
                <th>{{ trans('settings.name') }}</th>
                <th>{{ trans('settings.value') }}</th>
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
    </div>
@endsection
