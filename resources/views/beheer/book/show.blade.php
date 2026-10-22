@extends('layouts.beheer')

@section('title')
{{'Books'}}
@endsection

@section('content')
    <x-page-header title="Books">
        <x-edit-button :href="route('books.edit', $book)" />
        <x-back-button :href="route('books.index')" />
        <x-delete-button :action="route('books.destroy', $book)" confirm="Are you sure you want to delete this book?" />
    </x-page-header>
    <x-card title="Book" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Title">{{ $book->title }}</x-detail-row>
            <x-detail-row label="Year">{{ $book->year }}</x-detail-row>
            <x-detail-row label="Country">{{ $book->country }}</x-detail-row>
            <x-detail-row label="Type">{{ $book->type }}</x-detail-row>
            <x-detail-row label="Code">{{ $book->code }}</x-detail-row>
        </table>
    </x-card>
@endsection
