@extends('layouts.master')

@section('title')
{{ $pageTitle }}
@endsection

@section('main')
<x-menu transparent-at-top>
    <div class="flash-messages">
        @include('includes.flash')
    </div>
</x-menu>
@include('includes.header')

@yield('content')

@include('includes.footer')
@endsection