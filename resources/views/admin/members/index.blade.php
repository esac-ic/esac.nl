@extends('layouts.admin')

@section('title')
Current members
@endsection

@section('content')
    <x-page-header title="Current members">
        @can('manage-members')
            <x-button-link :href="route('admin.members.create')" icon="person-add">New member</x-button-link>
            <x-button-link :href="route('admin.penno-export.create')" icon="android-download" title="Export only the fields needed for the financial administration">Penno export</x-button-link>
            <x-button-link :href="route('admin.members.export')" icon="android-download" title="Export all active members">Export members</x-button-link>
        @endcan
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email address', 'Member type', 'Roles', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->member_type?->label() }}</td>
                <td>{{ $user->roles->map->label()->implode(', ') }}</td>
                <td>
                    @can('manage-members')
                        <x-icon-link :href="route('admin.members.edit', $user)" icon="edit" title="Edit member" />
                    @endcan
                    <x-icon-link :href="route('admin.members.show', $user)" icon="eye" title="Show member" />
                </td>
            </tr>
        @endforeach
    </x-data-table>

@endsection
