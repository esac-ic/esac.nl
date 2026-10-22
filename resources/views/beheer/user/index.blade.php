@extends('layouts.beheer')

@section('title')
Member
@endsection

@section('content')
    <x-page-header title="Current members">
        @can('manage-members')
            <a href="{{route('beheer.users.create')}}" class="btn btn-primary" title="New user">
                <span class="ion-person-add" aria-hidden="true"></span>
                New user
            </a>
            <a href="{{route('beheer.penno-export.create')}}" class="btn btn-primary" title="Export only the fields needed for the financial administration" >
                <span class="ion-android-download" aria-hidden="true"></span>
                Penno export
            </a>
            <a href="{{route('beheer.users.export')}}" class="btn btn-primary" title="Export all active members">
                <span class="ion-android-download" aria-hidden="true"></span>
                Export members
            </a>
        @endcan
    </x-page-header>
    <x-data-table :columns="['Name', 'Email address', 'Type of member', 'Roles*', 'Management']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->kind_of_member?->label() }}</td>
                <td>@foreach($user->roles as $role) {{$role->id}}@endforeach</td>
                <td>
                    <x-icon-link :href="route('beheer.users.edit', $user)" icon="edit" title="Edit user" />
                    <x-icon-link :href="route('beheer.users.show', $user)" icon="eye" title="Show user information" />
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <h5 class="mt-3 mb-0 bold">*Roles</h5>
    <ol>
    @foreach($roles as $role)
        <li>{{$role->name}}</li>
    @endforeach
    </ol>
@endsection
