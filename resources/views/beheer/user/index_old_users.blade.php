@extends('layouts.beheer')

@section('title')
{{'Old members'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Old members">
        @can('manage-members')
            <x-button-link :href="route('beheer.users.old-members.export')" icon="android-download">Export old members</x-button-link>
        @endcan
    </x-page-header>
    <x-data-table :columns="['Name', 'Email address', 'Type of member', 'Management']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @isset($user->kind_of_member)
                    {{trans('kind_of_member.' . $user->kind_of_member)}}
                    @endisset
                </td>
                <td>
                    <x-icon-link :href="route('beheer.users.edit', $user)" icon="edit" title="Edit user" />
                    <x-icon-link :href="route('beheer.users.show', $user)" icon="eye" title="Show user information" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
