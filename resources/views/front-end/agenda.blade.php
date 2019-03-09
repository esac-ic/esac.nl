@extends('layouts.app')

@section('content')
    {{--In this div is the agenda mounted by vue--}}
    <div id="agenda">
    </div>
@endsection

@push('scripts')
    <script>
        var APP_URL = "{{env('APP_URL')}}";
        var DESCRIPTION = "{!! $content !!}";
    </script>
    <script src="{{mix('js/agenda.js')}}"></script>
@endpush
