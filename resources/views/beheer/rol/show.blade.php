@extends('layouts.beheer')

@section('title')
{{'Roles'}}
@endsection

@section('content')
    <x-page-header title="Roles">
        <x-edit-button :href="route('rols.edit', $rol)" />
        <x-back-button :href="route('rols.index')" />
        <x-delete-button :action="route('rols.destroy', $rol)" confirm="Are you sure you want to delete this role?" />
    </x-page-header>
    <x-card title="Role" class="mt-4">
        Name: {{ $rol->name }}
    </x-card>
@endsection
