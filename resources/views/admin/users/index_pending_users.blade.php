@extends('layouts.admin')

@section('title')
Pending users
@endsection

@section('content')
    <x-page-header title="Pending users" />
    <x-data-table :columns="['Name', 'Email address', 'Type of member', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->kind_of_member?->label() }}</td>
                <td>
                    <x-icon-link :href="route('admin.users.edit', $user)" icon="edit" title="Edit user" />
                    <x-icon-link :href="route('admin.users.show', $user)" icon="eye" title="Show user information" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
