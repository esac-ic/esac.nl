@extends('layouts.beheer')

@section('title')
{{$fields['title_content']}}
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
@endpush

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif

    {!! Form::open(['method' => $fields['method'], 'url' => $fields['url'], 'enctype' => 'multipart/form-data', 'id' => 'agenda-form'])  !!}
    @include('beheer.agendaItem.create_edit_default_info')
    @include('beheer.agendaItem.create_edit_image')
    @include('beheer.agendaItem.create_edit_content')

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($agendaItem == null) ? ('/agendaItems') : ('/agendaItems/' . $agendaItem->id)}}">{{trans('menu.cancel')}}</a>
    </div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/moment.js")}}" type="text/javascript"></script>
<script src="{{mix("js/fileHandler.js")}}" type="text/javascript"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
<script type="text/javascript">
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

    $('#agenda-form').on('submit', function(event){
        if(false === validateFileSize()){
            alert('{{ trans('validation.fileToLarge') }}');
            return false;
        }
    })

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
        $('#content_nl').summernote(summernoteSettings);
        $('#content_en').summernote(summernoteSettings);
    });

    $('#applicationForm').change(function(){
        if($('#applicationForm').val() === "0"){
            $('#subscription_endDate_box').hide();
        } else {
            $('#subscription_endDate_box').show();
        }
    })
    $("#applicationForm").prepend('<option value="0">{{trans('AgendaItems.noapplicationForm')}}</option>');
    @if($agendaItem === null || $agendaItem->application_form_id === null)
        $("#applicationForm").val('0');
    @endif
    $("#applicationForm").change();
</script>
@endpush