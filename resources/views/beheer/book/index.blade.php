@extends('layouts.beheer')

@section('title')
{{'Books'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Books">
        <x-button-link :href="route('books.create')" icon="plus">New book</x-button-link>
        <x-button-link :href="route('books.export')" icon="android-download">Export books</x-button-link>
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
                    <x-icon-link :href="route('books.edit', $book)" icon="edit" title="Edit book" />
                    <x-icon-link :href="route('books.show', $book)" icon="eye" title="Show book" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
