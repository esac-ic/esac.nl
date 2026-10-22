@extends('layouts.beheer')

@php($title = $rol->exists ? 'Edit role' : 'Add a role')

@section('title', $title)

@section('content')
    <x-form-card :model="$rol" resource="beheer.roles" :$title>
        <x-field name="name" label="Name" :value="$rol->name" required />
    </x-form-card>
@endsection
