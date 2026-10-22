@extends('layouts.beheer')

@section('title')
{{'Penno export'}}
@endsection

@section('content')
    @include('includes.flash')
    <x-validation-errors />

    <x-card title="Penno export">
        {{ html()->form('GET', route('users.pennoMemberExport'))->open() }}
        <x-field name="days_ago" label="Amount of days in the past" type="number" value="7" required />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Download')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ route("users.index") }}">{{'Back'}}</a>
    </div>
@endsection