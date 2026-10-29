@extends('layouts.admin')

@section('title')
Books
@endsection

@section('content')
    <x-page-header title="Books">
        <x-button-link :href="route('admin.books.create')" icon="plus">New book</x-button-link>
        <x-button-link :href="route('admin.books.export')" icon="arrow-down-tray">Export books</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Title', 'Year', 'Country', 'Type', 'Code', 'Actions']">
        @foreach ($books as $book)
            <tr>
                <td><a href="{{ route('admin.books.show', $book) }}">{{ $book->title }}</a></td>
                <td>{{$book->year}}</td>
                <td>{{$book->country}}</td>
                <td>{{$book->type}}</td>
                <td>{{$book->code}}</td>
                <td>
                    <x-actions-menu :label="$book->title">
                        <x-actions-menu.item :href="route('admin.books.show', $book)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.books.edit', $book)" icon="pencil-square">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
