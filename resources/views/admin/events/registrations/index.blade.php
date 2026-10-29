@extends('layouts.admin')

@section('title')
Registrations: {{ $event->title }}
@endsection

@section('content')
    <x-page-header :title="'Registrations: '.$event->title" :back="route('admin.events.index')" back-label="Events">
        <x-button-link :href="route('admin.events.registrations.create', $event)" icon="user-plus">New registration</x-button-link>
        <x-button-link :href="route('admin.events.registrations.export', $event)" icon="arrow-down-tray">Export</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email address', 'Address', 'Actions']">
        @foreach ($registrations as $registration)
            <tr>
                <td><a href="{{ route('admin.events.registrations.show', [$event, $registration]) }}">{{ $registration->user->name }}</a></td>
                <td>{{ $registration->user->email }}</td>
                <td>{{ $registration->user->address }}</td>

                <td>
                    <x-actions-menu :label="$registration->user->name">
                        <x-actions-menu.item :href="route('admin.events.registrations.show', [$event, $registration])" icon="eye">View answers</x-actions-menu.item>
                        <x-actions-menu.delete :action="route('admin.events.registrations.destroy', [$event, $registration])" title="Remove registration" :confirm="'Remove the registration of '.$registration->user->name.' for '.$event->title.'? Their answers are deleted.'">Remove registration</x-actions-menu.delete>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

