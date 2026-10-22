@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <div id="app">
        @include('includes.flash')
        <x-validation-errors />

        {{ html()->form($fields['method'], $fields['url'])->acceptsFiles()->open() }}
        <x-card :title="$fields['title']">
            <x-field name="title" label="Title" :value="$newsItem?->title" required />
            <div class="form-group">
                <label for="author">Author</label>
                <auto-complete-field
                        name="author"
                        url="{{ route('user.autoComplete') }}"
                        placeholder="Author"
                        value="{{ $newsItem?->author }}">
                </auto-complete-field>
            </div>
            <span>{{'Image'}}</span>
            <div class="form-group mt-2">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <x-field name="text" id="content" label="News message" type="textarea" :value="$newsItem?->text" />
        </x-card>

        <div class="my-4">
            {{ html()->submit('Save')->class('btn btn-primary') }}
            {{ html()->form()->close() }}
            <a class="btn btn-danger btn-close" href="{{ $newsItem === null ? route('newsItems.index') : route('newsItems.show', $newsItem) }}">Cancel</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.custom-file-input').on('change', function() {
           let fileName = $(this).val().split('\\').pop();
           $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#content').summernote(summernoteSettings);
        });
    </script>
@endpush
