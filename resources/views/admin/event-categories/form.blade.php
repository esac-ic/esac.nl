@extends('layouts.admin')

@php($title = $eventCategory->exists ? 'Edit event category' : 'New event category')

@section('title', $title)

@section('content')
    <x-form-card :model="$eventCategory" resource="admin.event-categories" :$title>
        <x-field name="name" label="Name" :value="$eventCategory->name" required />
    </x-form-card>
@endsection
