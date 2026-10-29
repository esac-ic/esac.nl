@extends('layouts.admin')

@php($title = $certificate->exists ? 'Edit certificate' : 'New certificate')

@section('title', $title)

@section('content')
    <x-form-card :model="$certificate" resource="admin.certificates" :$title>
        <x-field name="name" label="Name" :value="$certificate->name" required />
        <x-field name="abbreviation" label="Abbreviation" :value="$certificate->abbreviation" required />
    </x-form-card>
@endsection
