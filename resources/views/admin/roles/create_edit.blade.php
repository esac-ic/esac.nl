@extends('layouts.admin')

@php($title = $role->exists ? 'Edit role' : 'Add a role')

@section('title', $title)

@section('content')
    <x-form-card :model="$role" resource="admin.roles" :$title>
        <x-field name="name" label="Name" :value="$role->name" required />
    </x-form-card>
@endsection
