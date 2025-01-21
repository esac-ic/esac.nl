@extends('layouts.beheer')

@section('title')
    {{'Events'}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Events'}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('agendaItems/create')}}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New event'}}
                </a>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="form-group">
                {{ html()->label('Start date')->for('startDate') }}
                <div class="input-group date" id="startDateBox" data-target-input="nearest">
                    {{ html()->text('startDate')
                        ->class(['form-control', 'datetimepicker-input'])
                        ->attribute('data-target', '#startDateBox')
                        ->value(\Carbon\Carbon::now()->addHours(1)->format('d-m-Y'))
                        ->required() }}
                    <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="ion-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table id="agenda-items" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Title'}}</th>
            <th>{{'Start date'}}</th>
            <th>{{'End date'}}</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
    <style>
        .icon {
            margin-left: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}"></script>
    <script src="{{mix("js/vendor/moment.js")}}"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}"></script>
    <script>
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
            actions += '<a class="mr-1 ml-1" href="{{url('agendaItems')}}/' + data.id + '/edit"><span title="{{'Edit event'}}" class="ion-edit font-size-120 icon" aria-hidden="true"></span></a>';
            actions += '<a class="mr-1 ml-1" href="{{url('agendaItems')}}/' + data.id + '"><span title="{{'Show event'}}" class="ion-eye font-size-120 icon" aria-hidden="true"></span></a>';
            if (data.application_form_id != null) {
                actions += '<a class="mr-1 ml-1" href="{{url('/forms/users')}}/' + data.id + '"><span title="{{'Show sign ups'}}" class="ion-android-list font-size-120 icon" aria-hidden="true"></span></a>';
            }
            actions += '<a class="mr-1 ml-1" href="{{url('agendaItems')}}/' + data.id + '/copy"><span title="{{'Copy event'}}" class="ion-ios-copy font-size-120 icon" aria-hidden="true"></span></a>';
            return actions;
        }

        function getUrl() {
            let params = "limit=10000000";
            let datePicker = $('#startDate');
            if (datePicker.val() != "") {
                params += "&startDate=" + datePicker.val();
            }

            return "{{url("api/agenda")}}?" + params;
        }
    </script>
@endpush