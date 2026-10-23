@extends('layouts.admin')

@php($title = $certificate ? 'Edit certificate' : 'Add certificate')

@section('title', $title)

@section('content')
<div class="container">
    <x-form-card :$title
        :action="$certificate ? route('admin.members.certificates.update', [$user, $certificate]) : route('admin.members.certificates.store', $user)"
        :method="$certificate ? 'PATCH' : 'POST'"
        :cancel="route('admin.members.show', $user)">
        <x-field name="certificate_id" label="Certificate" :options="$certificates" :value="$certificate?->id" required />
    </x-form-card>
</div>
@endsection
