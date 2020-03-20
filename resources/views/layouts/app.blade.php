@extends('layouts.master')

@section('title')
{{ $curPageName }}
@endsection

@section('main')
@component('includes.menu')
    @slot('navClass', 'position-fixed bg-dark')
    @slot('navId', 'scrollNav')
@endcomponent
@include('includes.header')

@yield('content')

@include('includes.footer')
@endsection