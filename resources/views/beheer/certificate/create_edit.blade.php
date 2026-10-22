@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <x-form-card :$fields :cancel="$certificate === null ? route('certificates.index') : route('certificates.show', $certificate)">
        <x-field name="name" label="Name" :value="$certificate?->name" required />
        <x-field name="abbreviation" label="Abbreviation" :value="$certificate?->abbreviation" required />
    </x-form-card>
@endsection
