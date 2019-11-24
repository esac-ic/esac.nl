@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset("css/vendor/tempusdominus.css")}}">
@endpush

@section('title')
{{$curPageName}}
@endsection

@section('content')

<div class="container-fluid">

    <div id="materiaal">
        <materiaal></materiaal>
    </div>
    <h1>TEST</h1>

</div>

@endsection

@push('scripts')
    <script src="{{asset("js/vendor/moment.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
    <script>
        $('#birthDayBox').datetimepicker({
            locale: 'nl',
            format: 'L',
            icons: {
                time: 'ion-clock',
                date: 'ion-calendar',
                up: 'ion-android-arrow-up',
                down: 'ion-android-arrow-down',
                previous: 'ion-chevron-left',
                next: 'ion-chevron-right',
                today: 'ion-calendar',
                clear: 'ion-trash-a',
                close: 'ion-close'
            }
        });
        $(function(){
            $('#birthDayBox').datetimepicker();
        })
        $(function () {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <script src="{{mix('js/materiaal.js')}}"></script>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
@endpush
