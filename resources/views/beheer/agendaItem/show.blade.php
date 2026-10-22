@extends('layouts.beheer')

@section('title')
Event general information
@endsection

@section('content')
    <x-page-header title="Agenda item">
        <x-edit-button :href="route('beheer.agenda-items.edit', $agendaItem)" />
        <x-back-button :href="route('beheer.agenda-items.index')" :block="false" />
        <x-delete-button :action="route('beheer.agenda-items.destroy', $agendaItem)" confirm="Are you sure you want to delete this agenda item?" />
    </x-page-header>
    <x-card title="Event general information" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Title">{{$agendaItem->title}}</x-detail-row>
            <x-detail-row label="Short description">{{$agendaItem->short_description}}</x-detail-row>
            <x-detail-row label="Start date">{{ $agendaItem->start_date->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="End date">{{ $agendaItem->end_date->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="Last possible registration date">{{ $agendaItem->subscription_end_date?->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="Category">{{$agendaItem->agendaItemCategory->name}}</x-detail-row>
            <x-detail-row label="Show certificates in the event item">{{$agendaItem->climbing_activity ? 'Yes' : 'No'}}</x-detail-row>
            <x-detail-row label="Created by">{{$agendaItem->creator->getName()}}</x-detail-row>
            <x-detail-row label="Application form">{{ $agendaItem->applicationForm?->name }}</x-detail-row>
            <x-detail-row label="Thumbnail image"><img src="{{$agendaItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></x-detail-row>
        </table>
    </x-card>
    <x-card title="Event item content" class="my-4">
        {!! $agendaItem->text !!}
    </x-card>
@endsection
