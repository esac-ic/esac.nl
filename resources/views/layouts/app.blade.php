@extends('layouts.master')

@section('title')
{{ $pageTitle }}
@endsection

@section('main')
<x-menu nav-class="position-fixed bg-dark" transparent-at-top />
@include('includes.header')

{{-- Floats under the menu, so it shows on every page layout --}}
<div class="position-fixed" style="top: 80px; right: 1rem; z-index: 1050;">
    @include('includes.flash')
</div>

@yield('content')

@include('includes.footer')
@endsection