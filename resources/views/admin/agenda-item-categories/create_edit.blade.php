@extends('layouts.admin')

@php($title = $agendaItemCategory->exists ? 'Edit category' : 'Add an event category')

@section('title', $title)

@section('content')
    <x-form-card :model="$agendaItemCategory" resource="admin.agenda-item-categories" :$title>
        <x-field name="name" label="Name" :value="$agendaItemCategory->name" required />
    </x-form-card>
@endsection
