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

{{-- Floats under the menu, so it shows on every page layout --}}
<div class="position-fixed" style="top: 80px; right: 1rem; z-index: 1050;">
    @include('includes.flash')
</div>

@yield('content')

@include('includes.footer')
@endsection