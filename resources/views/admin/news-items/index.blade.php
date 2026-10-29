@extends('layouts.admin')

@section('title')
News items
@endsection

@section('content')
    <x-page-header title="News items">
        <x-button-link :href="route('admin.news-items.create')" icon="plus">New news item</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Title', 'Author', 'Created at', 'Actions']">
        @foreach ($newsItems as $newsItem)
            <tr>
                <td><a href="{{ route('admin.news-items.show', $newsItem) }}">{{ $newsItem->title }}</a></td>
                <td>{{$newsItem->author}}</td>
                <td data-order="{{ $newsItem->created_at->timestamp }}"><x-local-time :value="$newsItem->created_at" /></td>
                <td>
                    <x-actions-menu :label="$newsItem->title">
                        <x-actions-menu.item :href="route('admin.news-items.show', $newsItem)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.news-items.edit', $newsItem)" icon="pencil-square">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

