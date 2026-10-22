@extends('layouts.beheer')

@section('title')
{{'Roles'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Roles">
        <x-slot:intro><p>Warning: don't add or remove roles without discussing with the IC</p></x-slot:intro>
        <x-button-link :href="route('beheer.roles.create')" icon="plus">New role</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Name', 'Management']">
        @foreach ($rols as $rol)
            <tr>
                <td>{{$rol->name}}</td>
                <td>
                    <x-icon-link :href="route('beheer.roles.edit', $rol)" icon="edit" title="Edit role" />
                    <x-icon-link :href="route('beheer.roles.show', $rol)" icon="eye" title="Show role" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
