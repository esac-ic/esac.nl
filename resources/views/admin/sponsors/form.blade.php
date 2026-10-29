@extends('layouts.admin')

@php($title = $sponsor->exists ? 'Edit sponsor' : 'New sponsor')

@section('title', $title)

@section('content')
    <x-model-form :model="$sponsor" resource="admin.sponsors" :cancel="route('admin.sponsors.index')" files>
        <x-card :$title>
            <x-field name="name" label="Name" :value="$sponsor->name" required />
            <x-field name="url" label="Website" type="url" :value="$sponsor->url" required />
            <div class="form-group mb-0">
                <label for="logo">Logo</label>
                <input type="file" @class(['form-control-file', 'is-invalid' => $errors->has('logo')]) id="logo" name="logo" accept="image/*" @required(! $sponsor->exists)>
                <small class="form-text text-muted">Shown on the dark footer, so a light or transparent logo works best.@if ($sponsor->exists) Leave empty to keep the current logo.@endif</small>
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </x-card>
    </x-model-form>
@endsection
