@extends('layouts.admin')

@section('title')
News items
@endsection

@section('content')
    <x-page-header title="News items">
        <x-button-link :href="route('admin.news-items.create')" icon="plus">New news item</x-button-link>
    </x-page-header>
    <x-data-table id="news-items-table" :columns="['Title', 'Created by', 'Created at', 'Actions']">
        @foreach ($newsItems as $newsItem)
            <tr>
                <td>{{$newsItem->title}}</td>
                <td>{{$newsItem->author}}</td>
                <td><x-local-time :value="$newsItem->created_at" /></td>
                <td>
                    <x-icon-link :href="route('admin.news-items.edit', $newsItem)" icon="edit" title="Edit news item" />
                    <x-icon-link :href="route('admin.news-items.show', $newsItem)" icon="eye" title="Show news item" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}"></script>
    <script>
        $('#news-items-table').DataTable({
            columnDefs: [
                {type: 'de_datetime', targets: 1},
                {type: 'de_datetime', targets: 2},
            ]
        })
    </script>
@endpush
