@extends('layouts.beheer')

@section('title')
{{$fields['title_content']}}
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
@endpush

@section('content')
    @include('includes.flash')
    <x-validation-errors />

    {{ html()->form($fields['method'], $fields['url'])->acceptsFiles()->open() }}
    @include('beheer.agendaItem.create_edit_default_info')
    @include('beheer.agendaItem.create_edit_image')
    @include('beheer.agendaItem.create_edit_content')

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ $agendaItem === null ? route('agendaItems.index') : route('agendaItems.show', $agendaItem) }}">{{'Cancel'}}</a>
    </div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/moment.js")}}"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}"></script>
<script>
    $('#startDateBox, #endDateBox, #subscription_endDateBox').datetimepicker({
        locale: 'nl',
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

    $(function () {
        $('#startDateBox').datetimepicker();
        $('#startDateBox').datetimepicker();
        $('#endDateBox').datetimepicker({
            useCurrent: false
        });
        $("#startDateBox").on("change.datetimepicker", function (e) {
            $('#endDateBox').datetimepicker('minDate', e.date);
        });
        $("#endDateBox").on("change.datetimepicker", function (e) {
            $('#startDateBox').datetimepicker('maxDate', e.date);
        });
    });

    $(document).ready(function() {
        $('#content_en').summernote(summernoteSettings);
    });

    $('#applicationForm').change(function(){
        if($('#applicationForm').val() === ""){
            $('#subscription_endDate_box').hide();
        } else {
            $('#subscription_endDate_box').show();
        }
    })
    $("#applicationForm").change();
</script>
@endpush
