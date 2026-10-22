@extends('layouts.beheer')

@section('title')
Old members
@endsection

@section('content')
    <x-page-header title="Old members">
        @can('manage-members')
            <x-button-link :href="route('beheer.old-members.export')" icon="android-download">Export old members</x-button-link>
        @endcan
    </x-page-header>
    <x-data-table :columns="['Name', 'Email address', 'Type of member', 'Management']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>
                    {{ $user->kind_of_member?->label() }}
                </td>
                <td>
                    <x-icon-link :href="route('beheer.users.edit', $user)" icon="edit" title="Edit user" />
                    <x-icon-link :href="route('beheer.users.show', $user)" icon="eye" title="Show user information" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
