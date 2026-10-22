@extends('layouts.admin')

@section('title')
Roles
@endsection

@section('content')
    <x-page-header title="Roles">
        <x-slot:intro><p>Warning: don't add or remove roles without discussing with the IC</p></x-slot:intro>
        <x-button-link :href="route('admin.roles.create')" icon="plus">New role</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Actions']">
        @foreach ($roles as $role)
            <tr>
                <td>{{$role->name}}</td>
                <td>
                    <x-icon-link :href="route('admin.roles.edit', $role)" icon="edit" title="Edit role" />
                    <x-icon-link :href="route('admin.roles.show', $role)" icon="eye" title="Show role" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
