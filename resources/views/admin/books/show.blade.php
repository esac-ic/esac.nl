@extends('layouts.admin')

@section('title')
Books
@endsection

@section('content')
    <x-page-header title="Book" :back="route('admin.books.index')" back-label="Books">
        <x-button-link :href="route('admin.books.edit', $book)" icon="pencil-square">Edit</x-button-link>
        <x-delete-button :action="route('admin.books.destroy', $book)" title="Remove book" :confirm="'Remove '.$book->title.' from the library?'" />
    </x-page-header>
    <x-card title="Book" class="mt-4">
        <table class="table table-striped mb-0">
            <x-detail-row label="Title">{{ $book->title }}</x-detail-row>
            <x-detail-row label="Year">{{ $book->year }}</x-detail-row>
            <x-detail-row label="Country">{{ $book->country }}</x-detail-row>
            <x-detail-row label="Type">{{ $book->type }}</x-detail-row>
            <x-detail-row label="Code">{{ $book->code }}</x-detail-row>
        </table>
    </x-card>
@endsection
