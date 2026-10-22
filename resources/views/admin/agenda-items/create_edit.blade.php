@extends('layouts.admin')

@section('title', 'Event item content')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{mix("css/vendor/tempusdominus.css")}}">
@endpush

@section('content')
    <x-validation-errors />

    {{ html()->form($agendaItem->exists ? 'PATCH' : 'POST', $agendaItem->exists ? route('admin.agenda-items.update', $agendaItem) : route('admin.agenda-items.store'))->acceptsFiles()->open() }}
    <x-card title="Event general information">
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-date-picker name="start_date" id="startDate" label="Start date" :value="($agendaItem->start_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i')" />
            </div>
            <div class="form-group col-md-6">
                <x-date-picker name="end_date" id="endDate" label="End date" :value="($agendaItem->end_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i')" />
            </div>
        </div>
        <div class="form-row">
            <x-field name="applicationForm" label="Application form" :options="['' => 'No application form'] + $applicationForms" :value="$agendaItem->application_form_id" col="6" />
            <div class="form-group col-md-6" id="subscription_endDate_box">
                <x-date-picker name="subscription_end_date" id="subscription_endDate" label="Last possible registration date" :value="($agendaItem->exists ? ($agendaItem->subscription_end_date ?? now(config('app.display_timezone'))) : now(config('app.display_timezone'))->subDays(7))->format('d-m-Y H:i')" />
            </div>
        </div>
        <div class="form-row">
            <x-field name="agenda_item_category_id" label="Category" :options="$agendaItemCategories" :value="$agendaItem->agenda_item_category_id" col="6" required />
            <div class="form-group col-md-6">
                {{ html()->label('Show certificates in the event item', 'climbing_activity') }}
                {{ html()->checkbox('climbing_activity', ! session()->hasOldInput() && ($agendaItem->climbing_activity ?? true), 1)->class('form-control') }}
            </div>
        </div>
    </x-card>
    <x-card title="Event item thumbnail" class="mt-4">
        <span>Thumbnail image</span>
        <div class="form-group mt-2">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" @required(! $agendaItem->exists)>
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
    </x-card>
    <x-card title="Event item content" class="mt-4">
        <x-field name="title" label="Title" :value="$agendaItem->title" required />
        <x-field name="short_description" label="Short description (max. 100 characters)" :value="$agendaItem->short_description" required maxlength="100" />
        <x-field name="text" id="content_en" label="Content" type="textarea" :value="$agendaItem->text" />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger btn-close" href="{{ $agendaItem->exists ? route('admin.agenda-items.show', $agendaItem) : route('admin.agenda-items.index') }}">Cancel</a>
    </div>
@endsection

@push('scripts')
<script src="{{mix("js/vendor/moment.js")}}"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}"></script>
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    $('#startDateBox, #endDateBox, #subscription_endDateBox').datetimepicker({
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
