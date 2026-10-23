@extends('layouts.admin')

@section('title')
{{ $event->title }}
@endsection

@section('content')
    <x-page-header title="Event">
        <x-button-link :href="route('admin.events.edit', $event)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.events.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.events.destroy', $event)" confirm="Are you sure you want to delete this event?" />
    </x-page-header>
    <x-card title="General information" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Title">{{$event->title}}</x-detail-row>
            <x-detail-row label="Short description">{{$event->short_description}}</x-detail-row>
            <x-detail-row label="Start date">{{ $event->start_date->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="End date">{{ $event->end_date->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="Registration deadline">{{ $event->registration_deadline?->format('d-m-Y H:i') }}</x-detail-row>
            <x-detail-row label="Category">{{$event->category->name}}</x-detail-row>
            <x-detail-row label="Show the members' certificates">{{$event->shows_certificates ? 'Yes' : 'No'}}</x-detail-row>
            <x-detail-row label="Created by">{{$event->creator->name}}</x-detail-row>
            <x-detail-row label="Registration form">{{ $event->registrationForm?->name }}</x-detail-row>
            <x-detail-row label="Image"><img src="{{$event->image_url}}" style="max-width: 240px" class="img-fluid"></x-detail-row>
        </table>
    </x-card>
    <x-card title="Content" class="my-4">
        {!! $event->body !!}
    </x-card>
@endsection
