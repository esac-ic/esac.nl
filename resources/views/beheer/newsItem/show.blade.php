@extends('layouts.beheer')

@section('title')
News item
@endsection

@section('content')
    <x-page-header title="News item">
        <x-edit-button :href="route('beheer.news-items.edit', $newsItem)" />
        <x-back-button :href="route('beheer.news-items.index')" :block="false" />
        <x-delete-button :action="route('beheer.news-items.destroy', $newsItem)" confirm="Are you sure you want to delete this news item?" />
    </x-page-header>
    <x-card title="General information" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Title">{{ $newsItem->title }}</x-detail-row>
            <x-detail-row label="Created at"><x-local-time :value="$newsItem->created_at" /></x-detail-row>
            <x-detail-row label="Created by">{{ $newsItem->author }}</x-detail-row>
            @if($newsItem->image_url != "")
                <x-detail-row label="Image"><img src="{{$newsItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></x-detail-row>
            @endif
        </table>
    </x-card>
    <x-card title="News message" class="mt-4">
        {!! $newsItem->text !!}
    </x-card>
@endsection
