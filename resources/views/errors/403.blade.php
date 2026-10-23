@extends('layouts.app',[
    'pageTitle' => 'No access'
])

@section('content')
    <x-page-intro title="No access">
        <p>You do not have access to this page.</p>
        <a class="btn btn-outline-primary" href="{{ route('home') }}">Back to home</a>
    </x-page-intro>
@endsection

