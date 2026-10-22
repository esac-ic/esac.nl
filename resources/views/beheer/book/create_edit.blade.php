@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <x-form-card :$fields :cancel="$book === null ? route('books.index') : route('books.show', $book)">
        <x-field name="title" label="Title" :value="$book?->title" required />
        <div class="form-row">
            <x-field name="year" label="Year" type="number" :value="$book?->year" col="3" required />
            <x-field name="country" label="Country" :value="$book?->country" col="3" required />
            <x-field name="type" label="Type" :value="$book?->type" col="3" required />
            <x-field name="code" label="Code" :value="$book?->code" col="3" required />
        </div>
    </x-form-card>
@endsection
