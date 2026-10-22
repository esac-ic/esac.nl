@extends('layouts.admin')

@section('title')
Event category
@endsection

@section('content')
    <x-page-header title="Event category">
        <x-edit-button :href="route('admin.event-categories.edit', $eventCategory)" />
        <x-back-button :href="route('admin.event-categories.index')" />
        <x-delete-button :action="route('admin.event-categories.destroy', $eventCategory)" confirm="Are you sure you want to delete this event category?" />
    </x-page-header>
    <x-card title="Event category" class="mt-4">
        Name: {{ $eventCategory->name }}
    </x-card>
@endsection
