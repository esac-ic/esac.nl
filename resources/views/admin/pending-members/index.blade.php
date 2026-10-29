@extends('layouts.admin')

@section('title')
Pending members
@endsection

@section('content')
    <x-page-header title="Pending members" />
    <x-data-table datatable :columns="['Name', 'Email address', 'Member type', 'Actions']">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{ $user->member_type?->label() }}</td>
                <td>
                    <x-icon-link :href="route('admin.members.edit', $user)" icon="edit" title="Edit member" />
                    <x-icon-link :href="route('admin.members.show', $user)" icon="eye" title="Show member" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
