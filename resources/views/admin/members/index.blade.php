@extends('layouts.admin')

@section('title')
Current members
@endsection

@section('content')
    <x-page-header title="Current members">
        @can('manage-members')
            <a href="{{route('admin.members.create')}}" class="btn btn-primary" title="New member">
                <span class="ion-person-add" aria-hidden="true"></span>
                New member
            </a>
            <a href="{{route('admin.penno-export.create')}}" class="btn btn-primary" title="Export only the fields needed for the financial administration" >
                <span class="ion-android-download" aria-hidden="true"></span>
                Penno export
            </a>
            <a href="{{route('admin.members.export')}}" class="btn btn-primary" title="Export all active members">
                <span class="ion-android-download" aria-hidden="true"></span>
                Export members
            </a>
        @endcan
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email address', 'Member type', 'Roles*', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->member_type?->label() }}</td>
                <td>@foreach($user->roles as $role) {{$role->id}}@endforeach</td>
                <td>
                    @can('manage-members')
                        <x-icon-link :href="route('admin.members.edit', $user)" icon="edit" title="Edit member" />
                    @endcan
                    <x-icon-link :href="route('admin.members.show', $user)" icon="eye" title="Show member" />
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
