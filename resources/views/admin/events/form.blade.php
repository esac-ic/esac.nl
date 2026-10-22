@extends('layouts.admin')

@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
    <x-validation-errors />

    {{ html()->form($event->exists ? 'PATCH' : 'POST', $event->exists ? route('admin.events.update', $event) : route('admin.events.store'))->acceptsFiles()->open() }}
    <x-card title="General information">
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-date-picker name="start_date" id="startDate" label="Start date" :value="($event->start_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i')" />
            </div>
            <div class="form-group col-md-6">
                <x-date-picker name="end_date" id="endDate" label="End date" :value="($event->end_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i')" />
            </div>
        </div>
        <div class="form-row">
            <x-field name="registration_form_id" label="Registration form" :options="['' => 'No registration form'] + $registrationForms" :value="$event->registration_form_id" col="6" />
            <div class="form-group col-md-6" id="registration-deadline-group">
                <x-date-picker name="registration_deadline" id="registration_deadline" label="Registration deadline" :value="($event->exists ? ($event->registration_deadline ?? now(config('app.display_timezone'))) : now(config('app.display_timezone'))->subDays(7))->format('d-m-Y H:i')" />
            </div>
        </div>
        <div class="form-row">
            <x-field name="event_category_id" label="Category" :options="$eventCategories" :value="$event->event_category_id" col="6" required />
            <div class="form-group col-md-6">
                {{ html()->label('Show the members\' certificates', 'shows_certificates') }}
                {{ html()->checkbox('shows_certificates', ! session()->hasOldInput() && ($event->shows_certificates ?? true), 1)->class('form-control') }}
            </div>
        </div>
    </x-card>
    <x-card title="Image" class="mt-4">
        <span>Image</span>
        <div class="form-group mt-2">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" @required(! $event->exists)>
                <label class="custom-file-label" for="thumbnail">Choose file</label>
            </div>
        </div>
    </x-card>
    <x-card title="Content" class="mt-4">
        <x-field name="title" label="Title" :value="$event->title" required />
        <x-field name="short_description" label="Short description (max. 100 characters)" :value="$event->short_description" required maxlength="100" />
        <x-field name="text" label="Content" type="textarea" :value="$event->text" />
    </x-card>

    <div class="my-4">
        {{ html()->submit('Save')->class('btn btn-primary') }}
        {{ html()->form()->close() }}
        <a class="btn btn-danger" href="{{ $event->exists ? route('admin.events.show', $event) : route('admin.events.index') }}">Cancel</a>
    </div>
@endsection

@push('scripts')
@vite(['resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])
<script type="module">
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    $('#startDateBox, #endDateBox, #registration_deadlineBox').datetimepicker({
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
        $('#text').summernote(summernoteSettings);
    });

    $('#registration_form_id').change(function(){
        if($('#registration_form_id').val() === ""){
            $('#registration-deadline-group').hide();
        } else {
            $('#registration-deadline-group').show();
        }
    })
    $("#registration_form_id").change();
</script>
@endpush
