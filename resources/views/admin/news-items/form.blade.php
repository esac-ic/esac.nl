@extends('layouts.admin')

@php($title = $newsItem->exists ? 'Edit news item' : 'Add a news item')

@section('title', $title)

@section('content')
    <x-model-form :model="$newsItem" resource="admin.news-items" files>
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
                <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <x-field name="body" label="Content" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$newsItem->body" />
        </x-card>
    </x-model-form>
@endsection

