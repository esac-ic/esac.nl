@extends('layouts.admin')

@section('title')
Certificate
@endsection

@section('content')
    <x-page-header title="Certificate">
        <x-button-link :href="route('admin.certificates.edit', $certificate)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.certificates.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.certificates.destroy', $certificate)" confirm="Are you sure you want to delete this certificate?" />
    </x-page-header>
    <x-card title="Certificate" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Name">{{ $certificate->name }}</x-detail-row>
            <x-detail-row label="Abbreviation">{{ $certificate->abbreviation }}</x-detail-row>
        </table>
    </x-card>
@endsection
