@extends('layouts.admin')

@section('title')
Penno export
@endsection

@section('content')
    <x-validation-errors />

    <x-card title="Penno export">
        {{ html()->form('POST', route('admin.penno-export.store'))->open() }}
        <x-field name="days_ago" label="Number of days back" type="number" value="7" required />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Download')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger" href="{{ route("admin.members.index") }}">Back</a>
    </div>
@endsection