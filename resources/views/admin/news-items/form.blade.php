@extends('layouts.admin')

@php($title = $newsItem->exists ? 'Edit news item' : 'New news item')

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
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('image')]) id="image" name="image" accept="image/*">
                @if ($newsItem->hasMedia('image'))
                    <small class="form-text text-muted">Leave empty to keep the current image.</small>
                @endif
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <x-field name="body" label="Content" type="textarea" :data-images-url="route('admin.editor-images.store')" :value="$newsItem->body" />
        </x-card>
    </x-model-form>
@endsection

