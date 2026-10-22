@extends('layouts.admin')

@section('title')
Penno export
@endsection

@section('content')
    <x-validation-errors />

    <x-card title="Penno export">
        {{ html()->form('POST', route('admin.penno-export.store'))->open() }}
        <x-field name="days_ago" label="Amount of days in the past" type="number" value="7" required />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Download')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ route("admin.users.index") }}">Back</a>
    </div>
@endsection