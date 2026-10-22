@extends('layouts.beheer')

@section('title')
{{'Penno export'}}
@endsection

@section('content')
    @include('includes.flash')
    <x-validation-errors />

    <div class="card">
        <div class="card-header">
            <h3>Penno export</h3>
        </div>
        <div class="card-body">
            {{ html()->form('GET', route('users.pennoMemberExport'))->open() }}
            <div class="form-group">
                {{ html()->label('Amount of days in the past', 'days_ago') }}
                {{ html()->number('days_ago', 7)->class('form-control')->required() }}
            </div>
        </div>
    </div>

    <div class="my-4">
        {{ html()->submit('Download')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ route("users.index") }}">{{'Back'}}</a>
    </div>
@endsection