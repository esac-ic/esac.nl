@extends('layouts.beheer')

@section('title')
Event category
@endsection

@section('content')
    <x-page-header title="Event category">
        <x-edit-button :href="route('beheer.agenda-item-categories.edit', $agendaItemCategory)" />
        <x-back-button :href="route('beheer.agenda-item-categories.index')" />
        <x-delete-button :action="route('beheer.agenda-item-categories.destroy', $agendaItemCategory)" confirm="Are you sure you want to delete this agenda item category?" />
    </x-page-header>
    <x-card title="Event category" class="mt-4">
        Name: {{ $agendaItemCategory->name }}
    </x-card>
@endsection
