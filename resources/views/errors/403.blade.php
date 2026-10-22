@extends('layouts.app',[
    'curPageName' => 'No access'
])

@section('content')
    <x-page-intro title="No access">
        <p>You do not have sufficient access to view this page</p>
    </x-page-intro>
@endsection

