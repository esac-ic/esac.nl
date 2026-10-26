@extends('layouts.admin')

@php($title = $headerSlide->exists ? 'Edit slide' : 'New slide')

@section('title', $title)

@section('content')
    <x-model-form :model="$headerSlide" resource="admin.header-slides" :cancel="route('admin.header-slides.index')" files>
        <x-card :$title>
            <x-field name="heading" label="Heading" :value="$headerSlide->heading" required />
            <x-field name="message" label="Message" :value="$headerSlide->message" required />
            <x-field name="link" label="Link (a page on this site, e.g. /events, or a full URL)" :value="$headerSlide->link" required />
            <div class="form-group mb-0">
                <label for="image">Photo</label>
                <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('image')]) id="image" name="image" accept="image/*" @required(! $headerSlide->exists)>
                @if ($headerSlide->exists)
                    <small class="form-text text-muted">Leave empty to keep the current photo.</small>
                @endif
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </x-card>
    </x-model-form>
@endsection
