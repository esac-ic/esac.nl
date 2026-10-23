@extends('layouts.admin')

@php($title = $newsItem->exists ? 'Edit news item' : 'Add a news item')

@section('title', $title)

@section('content')
    <x-validation-errors />

    {{ html()->form($newsItem->exists ? 'PATCH' : 'POST', $newsItem->exists ? route('admin.news-items.update', $newsItem) : route('admin.news-items.store'))->acceptsFiles()->open() }}
    <x-card :$title>
        <x-field name="title" label="Title" :value="$newsItem->title" required />
        <x-field name="author" label="Author" :value="$newsItem->author" list="member-names" required />
        <datalist id="member-names">
            @foreach ($memberNames as $memberName)
                <option value="{{ $memberName }}">
            @endforeach
        </datalist>
        <span>Image</span>
        <div class="form-group mt-2">
            <input type="file" class="form-control-file" id="thumbnail" name="thumbnail">
        </div>
        <x-field name="body" id="content" label="Message" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$newsItem->body" />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger" href="{{ $newsItem->exists ? route('admin.news-items.show', $newsItem) : route('admin.news-items.index') }}">Cancel</a>
    </div>
@endsection

