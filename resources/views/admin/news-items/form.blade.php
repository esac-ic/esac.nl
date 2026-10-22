@extends('layouts.admin')

@php($title = $newsItem->exists ? 'Edit news item' : 'Add a news item')

@section('title', $title)

@section('content')
    <div id="app">
        <x-validation-errors />

        {{ html()->form($newsItem->exists ? 'PATCH' : 'POST', $newsItem->exists ? route('admin.news-items.update', $newsItem) : route('admin.news-items.store'))->acceptsFiles()->open() }}
        <x-card :$title>
            <x-field name="title" label="Title" :value="$newsItem->title" required />
            <div class="form-group">
                <label for="author">Author</label>
                <auto-complete-field
                        name="author"
                        url="{{ route('api.members.autocomplete') }}"
                        placeholder="Author"
                        value="{{ $newsItem->author }}">
                </auto-complete-field>
            </div>
            <span>Image</span>
            <div class="form-group mt-2">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail">
                    <label class="custom-file-label" for="thumbnail">Choose file</label>
                </div>
            </div>
            <x-field name="body" id="content" label="Message" type="textarea" :value="$newsItem->body" />
        </x-card>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
            {{ html()->form()->close() }}
            <a class="btn btn-danger" href="{{ $newsItem->exists ? route('admin.news-items.show', $newsItem) : route('admin.news-items.index') }}">Cancel</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        $('.custom-file-input').on('change', function() {
           let fileName = $(this).val().split('\\').pop();
           $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>

    <script type="module">
        $(document).ready(function() {
            $('#content').summernote(summernoteSettings);
        });
    </script>
@endpush
