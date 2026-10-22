@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <x-form-card :$fields :cancel="$rol === null ? route('rols.index') : route('rols.show', $rol)">
        <x-field name="name" label="Name" :value="$rol?->name" required />
    </x-form-card>
@endsection
