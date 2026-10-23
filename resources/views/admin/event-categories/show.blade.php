@extends('layouts.admin')

@section('title')
Event category
@endsection

@section('content')
    <x-page-header title="Event category" :back="route('admin.event-categories.index')" back-label="Event categories">
        <x-button-link :href="route('admin.event-categories.edit', $eventCategory)" icon="edit">Edit</x-button-link>
        <x-delete-button :action="route('admin.event-categories.destroy', $eventCategory)" title="Remove event category" :confirm="'Remove the event category '.$eventCategory->name.'?'" />
    </x-page-header>
    <x-card :title="$eventCategory->name" class="mt-4">
        <table class="table table-striped mb-0">
            <x-detail-row label="Name">{{ $eventCategory->name }}</x-detail-row>
        </table>
    </x-card>
@endsection
