@extends('layouts.admin')

@section('title')
Event category
@endsection

@section('content')
    <x-page-header title="Event category">
        <x-button-link :href="route('admin.event-categories.edit', $eventCategory)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.event-categories.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.event-categories.destroy', $eventCategory)" confirm="Are you sure you want to delete this event category?" />
    </x-page-header>
    <x-card title="Event category" class="mt-4">
        Name: {{ $eventCategory->name }}
    </x-card>
@endsection
