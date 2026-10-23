@extends('layouts.admin')

@section('title')
Registrations: {{ $event->title }}
@endsection

@section('content')
    <x-page-header :title="'Registrations: '.$event->title">
        <x-button-link :href="route('admin.events.registrations.create', $event)" icon="person-add">New registration</x-button-link>
        <x-button-link :href="route('admin.events.registrations.export', $event)" icon="android-download">Export</x-button-link>
        <x-button-link :href="route('admin.events.index')" icon="android-arrow-back">Back</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email address', 'Address', 'Actions']">
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->user->name }}</td>
                <td>{{ $registration->user->email }}</td>
                <td>{{ $registration->user->address }}</td>

                <td>
                    <a href="{{ route('admin.events.registrations.show', [$event, $registration]) }}"><span title="Show entered information" class="ion-eye" aria-hidden="true"></span></a>
                    <x-delete-button icon :action="route('admin.events.registrations.destroy', [$event, $registration])" confirm="Are you sure you want to remove this registration?">Remove registration</x-delete-button>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

