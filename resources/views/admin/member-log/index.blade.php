@extends('layouts.admin')

@section('title')
Member log
@endsection

@section('content')
    <x-page-header title="Member log">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#export-member-log-modal"><span title="Export the member log" class="ion-android-download" aria-hidden="true"></span> Export</button>
    </x-page-header>
    <x-validation-errors />
    <x-data-table datatable :data-order="json_encode([[1, 'desc']])" :columns="['Type', 'Time', 'Details']">
        @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry->type->label() }}</td>
                <td><x-local-time :value="$entry->created_at" format="Y-m-d H:i:s" /></td>
                <td>{{$entry->details}}</td>
            </tr>
        @endforeach
    </x-data-table>
    
    @include("admin.member-log.export-modal")
@endsection

