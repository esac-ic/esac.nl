@extends('layouts.admin')

@section('title', 'Mail lists unavailable')

@section('content')
    <x-card title="Mail lists unavailable">
        <p>The mail list server could not be reached, so this page can't be shown right now. Try again in a few minutes.</p>
        <p class="text-muted text-small mb-0">{{ $message }}</p>
    </x-card>
@endsection
