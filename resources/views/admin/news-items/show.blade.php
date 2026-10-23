@extends('layouts.admin')

@section('title')
News item
@endsection

@section('content')
    <x-page-header title="News item">
        <x-button-link :href="route('admin.news-items.edit', $newsItem)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.news-items.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.news-items.destroy', $newsItem)" confirm="Are you sure you want to delete this news item?" />
    </x-page-header>
    <x-card title="General information" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Title">{{ $newsItem->title }}</x-detail-row>
            <x-detail-row label="Created at"><x-local-time :value="$newsItem->created_at" /></x-detail-row>
            <x-detail-row label="Author">{{ $newsItem->author }}</x-detail-row>
            @if($newsItem->image_path != "")
                <x-detail-row label="Image"><img src="{{$newsItem->image_url}}" style="max-width: 240px" class="img-fluid"></x-detail-row>
            @endif
        </table>
    </x-card>
    <x-card title="Message" class="mt-4">
        {!! $newsItem->body !!}
    </x-card>
@endsection
