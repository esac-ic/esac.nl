@extends('layouts.admin')

@section('title')
Registrations: {{ $event->title }}
@endsection

@section('content')
    <x-page-header :title="'Registrations: '.$event->title">
        <a href="{{route('admin.events.registrations.create', $event)}}" class="btn btn-primary">
            <em class="ion-person-add"></em> New registration
        </a>
        <a href="{{route('admin.events.registrations.export', $event)}}" class="btn btn-primary">
            <em class="ion-android-download"></em> Export
        </a>
        <a href="{{route('admin.events.index')}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em> Back
        </a>
    </x-page-header>
    <table class="table table-striped dt-responsive nowrap js-datatable" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email address</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->user->name }}</td>
                <td>{{ $registration->user->email }}</td>
                <td>{{ $registration->user->address }}</td>

                <td>
                    <a href="{{ route('admin.events.registrations.show', [$event, $registration]) }}"><span title="Show entered information" class="ion-eye" aria-hidden="true"></span></a>
                    <form class="d-inline" method="post" action="{{ route('admin.events.registrations.destroy', [$event, $registration]) }}" onsubmit="return confirm('Are you sure you want to remove this registration?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0"><span title="Remove registration" class="ion-trash-a" aria-hidden="true"></span></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

