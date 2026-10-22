@extends('layouts.beheer')

@section('title')
{{'Pending users'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Pending users" />
    <x-data-table :columns="['Name', 'Email address', 'Type of member', 'Beheer']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{trans('kind_of_member.' . $user->kind_of_member)}}</td>
                <td>
                    <x-icon-link :href="route('beheer.users.edit', $user)" icon="edit" title="Edit user" />
                    <x-icon-link :href="route('beheer.users.show', $user)" icon="eye" title="Show user information" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
