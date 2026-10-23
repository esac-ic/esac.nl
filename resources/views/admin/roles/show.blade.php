@extends('layouts.admin')

@section('title')
Roles
@endsection

@section('content')
    <x-page-header title="Roles">
        <x-edit-button :href="route('admin.roles.edit', $role)" />
        <x-back-button :href="route('admin.roles.index')" />
        <x-delete-button :action="route('admin.roles.destroy', $role)" confirm="Are you sure you want to delete this role?" />
    </x-page-header>
    <x-card title="Role" class="mt-4">
        Name: {{ $role->name }}
    </x-card>
@endsection
