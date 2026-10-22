@extends('layouts.beheer')

@php($title = $certificate ? 'Edit certificate' : 'Add certificate')

@section('title', $title)

@section('content')
<div class="container">
    <x-form-card :$title
        :action="$certificate ? route('beheer.users.certificates.update', [$user, $certificate]) : route('beheer.users.certificates.store', $user)"
        :method="$certificate ? 'PATCH' : 'POST'"
        :cancel="url()->previous()">
        <x-field name="certificate_id" label="Certificate" :options="$certificates" :value="$certificate?->id" required />
    </x-form-card>
</div>
@endsection
