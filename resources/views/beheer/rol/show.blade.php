@extends('layouts.beheer')

@section('title')
Roles
@endsection

@section('content')
    <x-page-header title="Roles">
        <x-edit-button :href="route('beheer.roles.edit', $rol)" />
        <x-back-button :href="route('beheer.roles.index')" />
        <x-delete-button :action="route('beheer.roles.destroy', $rol)" confirm="Are you sure you want to delete this role?" />
    </x-page-header>
    <x-card title="Role" class="mt-4">
        Name: {{ $rol->name }}
    </x-card>
@endsection
