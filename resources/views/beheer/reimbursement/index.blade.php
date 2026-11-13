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

    <table id="reimbursement" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Title'}}</th>
            <th>{{'Amount'}}</th>
            <th>{{'Created On'}}</th>
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

        let agendaTable = $('#reimbursement').DataTable({
            "order": [[2, "asc"]],
            "ajax": {
                'url': "{{url("api/reimbursement")}}?limit=100000",
                "dataSrc": "reimbursements"
            },
            columnDefs: [
                {width: '15%', targets: 1},
                {width: '20%', targets: [2,3]},
                {type: 'de_datetime', targets: 2},
            ],
            "columns": [
                {"data": "title"},
                {"data": "amount"},
                {
                    "data": function (data) {
                        return formatDate(data.createdOn);
                    }
                },
                {
                    "data": function (data) {
                        return getReimbursementActions(data);
                    }
                },
            ]
        });

        function formatDate(date) {
            date = moment(date);
            return date.format("DD-MM-YYYY")
        }

        function getReimbursementActions(data) {
            let actions = "";
            actions += '<a class="mr-1 ml-1" href="{{url('reimbursement')}}/' + data.id + '/edit"><span title="{{'Edit event'}}" class="ion-edit font-size-120 icon" aria-hidden="true"></span></a>';
            actions += '<a class="mr-1 ml-1" href="{{url('reimbursement')}}/' + data.id + '"><span title="{{'Show event'}}" class="ion-eye font-size-120 icon" aria-hidden="true"></span></a>';
            return actions;
        }
    </script>
@endpush