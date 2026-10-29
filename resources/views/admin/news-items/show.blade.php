@extends('layouts.admin')

@section('title')
News item
@endsection

@section('content')
    <x-page-header title="News item" :back="route('admin.news-items.index')" back-label="News items">
        <x-button-link :href="route('admin.news-items.edit', $newsItem)" icon="edit">Edit</x-button-link>
        <x-delete-button :action="route('admin.news-items.destroy', $newsItem)" title="Remove news item" :confirm="'Remove '.$newsItem->title.'? It disappears from the website.'" />
    </x-page-header>
    <x-card title="General information" class="mt-4">
        <table class="table table-striped mb-0">
            <x-detail-row label="Title">{{ $newsItem->title }}</x-detail-row>
            <x-detail-row label="Created at"><x-local-time :value="$newsItem->created_at" /></x-detail-row>
            <x-detail-row label="Author">{{ $newsItem->author }}</x-detail-row>
            @if($newsItem->image_path != "")
                <x-detail-row label="Image"><img src="{{$newsItem->image_url}}" style="max-width: 240px" class="img-fluid"></x-detail-row>
            @endif
        </table>
    </x-card>
    <x-card title="Content" class="mt-4">
        {!! $newsItem->body !!}
    </x-card>
@endsection
