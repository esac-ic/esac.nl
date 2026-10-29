@extends('layouts.admin')

@section('title')
Certificate
@endsection

@section('content')
    <x-page-header title="Certificate" :back="route('admin.certificates.index')" back-label="Certificates">
        <x-button-link :href="route('admin.certificates.edit', $certificate)" icon="pencil-square">Edit</x-button-link>
        <x-delete-button :action="route('admin.certificates.destroy', $certificate)" title="Remove certificate" :confirm="'Remove '.$certificate->name.'? It is no longer shown on members\' profiles.'" />
    </x-page-header>
    <x-card title="Certificate" class="mt-4">
        <table class="table table-striped mb-0">
            <x-detail-row label="Name">{{ $certificate->name }}</x-detail-row>
            <x-detail-row label="Abbreviation">{{ $certificate->abbreviation }}</x-detail-row>
        </table>
    </x-card>
@endsection
