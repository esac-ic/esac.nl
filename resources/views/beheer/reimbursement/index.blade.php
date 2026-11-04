@extends('layouts.beheer')

@section('title')
    {{'Reimbursements'}}
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
            <h1>{{'Reimbursements'}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('reimbursement/create')}}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New reimbursement'}}
                </a>
            </div>
        </div>
    </div>  

    <table id="agenda-items" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Created On'}}</th>
            <th>{{'Title'}}</th>
            <th>{{'Amount'}}</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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