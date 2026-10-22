@extends('layouts.app')

@section('content')
    <x-page-intro :title="$curPageName" :$content />
@endsection
