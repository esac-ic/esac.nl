@extends('layouts.beheer')

@section('title')
    {{'Events'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Events">
        <x-button-link :href="route('beheer.agenda-items.create')" icon="plus">New event</x-button-link>
    </x-page-header>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="form-group">
                <x-date-picker name="startDate" id="startDate" label="Start date" :value="now(config('app.display_timezone'))->format('d-m-Y')" />
            </div>
        </div>
    </div>

    <x-data-table id="agenda-items" :columns="['Title', 'Start date', 'End date', 'Management']" />
@endsection

@push('styles')
    <style>
        .icon {
            margin-left: 5px;
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js', 'resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])
    <script type="module">
        $('#startDateBox').datetimepicker({
            locale: 'nl',
            format: "DD-MM-YYYY",
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

        let agendaTable = $('#agenda-items').DataTable({
            "order": [[1, "asc"]],
            "ajax": {
                'url': getUrl(),
                "dataSrc": "agendaItems"
            },
            columnDefs: [
                {type: 'de_datetime', targets: 1},
                {type: 'de_datetime', targets: 2},
            ],
            "columns": [
                {"data": "title"},
                {
                    "data": function (data) {
                        return formatDate(data.full_startDate);
                    }
                },
                {
                    "data": function (data) {
                        return formatDate(data.endDate);
                    }
                },
                {
                    "data": function (data) {
                        return getAgendaItemActions(data);
                    }
                },
            ]
        });

        $("#startDateBox").on("change.datetimepicker", function (e) {
            agendaTable.ajax.url(getUrl()).load();
        });

        function formatDate(date) {
            date = moment(date);
            return date.format("DD-MM-YYYY HH:mm")
        }

        function getAgendaItemActions(data) {
            let actions = "";
            actions += '<a class="mr-1 ml-1" href="' + '{{ route('beheer.agenda-items.edit', '__id__') }}'.replace('__id__', data.id) + '"><span title="{{'Edit event'}}" class="ion-edit font-size-120 icon" aria-hidden="true"></span></a>';
            actions += '<a class="mr-1 ml-1" href="' + '{{ route('beheer.agenda-items.show', '__id__') }}'.replace('__id__', data.id) + '"><span title="{{'Show event'}}" class="ion-eye font-size-120 icon" aria-hidden="true"></span></a>';
            if (data.application_form_id != null) {
                actions += '<a class="mr-1 ml-1" href="' + '{{ route('beheer.agenda-items.registrations.index', '__id__') }}'.replace('__id__', data.id) + '"><span title="{{'Show sign ups'}}" class="ion-android-list font-size-120 icon" aria-hidden="true"></span></a>';
            }
            actions += '<form class="d-inline" method="POST" action="' + '{{ route('beheer.agenda-items.copy', '__id__') }}'.replace('__id__', data.id) + '"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" class="btn btn-link p-0 mr-1 ml-1"><span title="{{'Copy event'}}" class="ion-ios-copy font-size-120 icon" aria-hidden="true"></span></button></form>';
            return actions;
        }

        function getUrl() {
            let params = "limit=10000000";
            let datePicker = $('#startDate');
            if (datePicker.val() != "") {
                params += "&startDate=" + datePicker.val();
            }

            return "{{ route('api.agenda.index') }}?" + params;
        }
    </script>
@endpush