@extends('layouts.app',[
    'pageTitle' => 'Page not found'
])

@section('content')
    <x-page-intro title="Page not found">
        <p>The page you are looking for does not exist.</p>
        <a class="btn btn-outline-primary" href="{{ route('home') }}">Back to home</a>
    </x-page-intro>
@endsection
