@extends('layouts.admin')

@section('title')
Current members
@endsection

@section('content')
    <x-page-header title="Current members">
        @can('manage-members')
            <x-button-link :href="route('admin.members.create')" icon="user-plus">New member</x-button-link>
            <x-button-link :href="route('admin.penno-export.create')" icon="arrow-down-tray" title="Export only the fields needed for the financial administration">Penno export</x-button-link>
            <x-button-link :href="route('admin.members.export')" icon="arrow-down-tray" title="Export all active members">Export members</x-button-link>
        @endcan
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email address', 'Member type', 'Roles', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td><a href="{{ route('admin.members.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{$user->email}}</td>
                <td>{{ $user->member_type?->label() }}</td>
                <td>{{ $user->roles->map->label()->implode(', ') }}</td>
                <td>
                    <x-actions-menu :label="$user->name">
                        <x-actions-menu.item :href="route('admin.members.show', $user)" icon="eye">View</x-actions-menu.item>
                        @can('manage-members')
                            <x-actions-menu.item :href="route('admin.members.edit', $user)" icon="pencil-square">Edit</x-actions-menu.item>
                        @endcan
                        @can('manage-certificates')
                            <x-actions-menu.item :href="route('admin.members.certificates.create', $user)" icon="plus">Add certificate</x-actions-menu.item>
                        @endcan
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>

@endsection
