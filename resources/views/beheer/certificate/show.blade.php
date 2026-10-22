@extends('layouts.beheer')

@section('title')
Certificate
@endsection

@section('content')
    <x-page-header title="Certificate">
        <x-edit-button :href="route('beheer.certificates.edit', $certificate)" />
        <x-back-button :href="route('beheer.certificates.index')" />
        <x-delete-button :action="route('beheer.certificates.destroy', $certificate)" confirm="Are you sure you want to delete this certificate?" />
    </x-page-header>
    <x-card title="Role" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Name">{{ $certificate->name }}</x-detail-row>
            <x-detail-row label="Abbreviation">{{ $certificate->abbreviation }}</x-detail-row>
        </table>
    </x-card>
@endsection
