@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
<div class="container">
    <x-form-card :$fields :cancel="url()->previous()">
        <x-field name="certificate_id" label="Certificate" :options="$certificates" :value="$userCertificate?->certificate_id" required />
    </x-form-card>
</div>
@endsection
