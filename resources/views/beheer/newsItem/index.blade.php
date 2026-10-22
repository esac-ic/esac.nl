@extends('layouts.beheer')

@section('title')
{{'News items'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="News items">
        <x-button-link :href="route('beheer.news-items.create')" icon="plus">New news item</x-button-link>
    </x-page-header>
    <x-data-table id="newsItems" :columns="['Title', 'Created by', 'Created at', 'Management']">
        @foreach ($newsItems as $newsItem)
            <tr>
                <td>{{$newsItem->title}}</td>
                <td>{{$newsItem->author}}</td>
                <td>{{ $newsItem->created_at->tz(config('app.display_timezone'))->format('d-m-Y H:i') }}</td>
                <td>
                    <x-icon-link :href="route('beheer.news-items.edit', $newsItem)" icon="edit" title="Edit news item" />
                    <x-icon-link :href="route('beheer.news-items.show', $newsItem)" icon="eye" title="Show news item" />
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
        $('#newsItems').DataTable({
            columnDefs: [
                {type: 'de_datetime', targets: 1},
                {type: 'de_datetime', targets: 2},
            ]
        })
    </script>
@endpush
