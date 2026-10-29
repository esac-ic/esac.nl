@extends('layouts.admin')

@section('title')
Pending members
@endsection

@section('content')
    <x-page-header title="Pending members" />
    <x-data-table datatable :columns="['Name', 'Email address', 'Member type', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td><a href="{{ route('admin.members.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{$user->email}}</td>
                <td>{{ $user->member_type?->label() }}</td>
                <td>
                    <x-actions-menu :label="$user->name">
                        <x-actions-menu.item :href="route('admin.members.show', $user)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.members.edit', $user)" icon="pencil-square">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
