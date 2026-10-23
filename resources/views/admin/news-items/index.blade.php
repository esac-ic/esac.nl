@extends('layouts.admin')

@section('title')
News items
@endsection

@section('content')
    <x-page-header title="News items">
        <x-button-link :href="route('admin.news-items.create')" icon="plus">New news item</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Title', 'Created by', 'Created at', 'Actions']">
        @foreach ($newsItems as $newsItem)
            <tr>
                <td>{{$newsItem->title}}</td>
                <td>{{$newsItem->author}}</td>
                <td data-order="{{ $newsItem->created_at->timestamp }}"><x-local-time :value="$newsItem->created_at" /></td>
                <td>
                    <x-icon-link :href="route('admin.news-items.edit', $newsItem)" icon="edit" title="Edit news item" />
                    <x-icon-link :href="route('admin.news-items.show', $newsItem)" icon="eye" title="Show news item" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

