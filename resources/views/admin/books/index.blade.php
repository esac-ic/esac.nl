@extends('layouts.admin')

@section('title')
Books
@endsection

@section('content')
    <x-page-header title="Books">
        <x-button-link :href="route('admin.books.create')" icon="plus">New book</x-button-link>
        <x-button-link :href="route('admin.books.export')" icon="android-download">Export books</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Title', 'Year', 'Country', 'Type', 'Code', 'Management']">
        @foreach ($books as $book)
            <tr>
                <td>{{$book->title}}</td>
                <td>{{$book->year}}</td>
                <td>{{$book->country}}</td>
                <td>{{$book->type}}</td>
                <td>{{$book->code}}</td>
                <td>
                    <x-icon-link :href="route('admin.books.edit', $book)" icon="edit" title="Edit book" />
                    <x-icon-link :href="route('admin.books.show', $book)" icon="eye" title="Show book" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
