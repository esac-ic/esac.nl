@extends('layouts.admin')

@section('title')
    Setting
@endsection

@section('content')
    <x-page-header title="Setting">
        <a class="mr-1 ml-1" href="{{ route('admin.settings.edit') }}" class="btn btn-primary">
            <span title="Edit settings" class="ion-edit font-size-120" aria-hidden="true"></span>
            Edit settings
        </a>
    </x-page-header>
    <table id="settings-table" class="table table-striped table-bordered">
        <thead>
            <th>Name</th>
            <th>Value</th>
        </thead>
        <tbody>
        @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->name->label() }}</td>
                <td>
                    {{$setting->value}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
