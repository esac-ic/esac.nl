@extends('layouts.beheer')

@section('title')
{{trans("AgendaItems.agendaItems")}}
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
            <h1>{{trans("AgendaItems.agendaItems")}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('agendaItems/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("AgendaItems.new")}}
                </a>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('startDate', trans('AgendaItems.startDate')) !!}
                <div class="input-group date" id="startDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="startDate" name="startDate" data-target="#startDateBox" value="{{\Carbon\Carbon::now()->addHours(1)->format('d-m-Y')}}" required/>
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
            <th>{{trans('AgendaItems.title')}}</th>
            <th>{{trans('AgendaItems.startDate')}}</th>
            <th>{{trans('AgendaItems.endDate')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
    <script src="{{mix("js/vendor/moment.js")}}" type="text/javascript"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
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
            "order": [[ 1, "asc" ]],
            "ajax": {
                'url' : getUrl(),
                "dataSrc": "agendaItems"
            },
            "columns": [
                { "data": "title" },
                { "data": function(data){
                        return formatDate(data.full_startDate);
                    }},
                { "data": function(data){
                    return formatDate(data.endDate);
                    }},
                { "data": function(data){
                    return getAgendaItemActions(data);
                }},
            ]
        });

        $("#startDateBox").on("change.datetimepicker", function (e) {
            agendaTable.ajax.url(getUrl()).load();
        });

        function formatDate(date){
            date = moment(date);
            return date.format("DD-MM-YYYY HH:mm")
        }

        function getAgendaItemActions(data){
            let actions = "";
            actions += '<a href="{{url('agendaItems')}}/' + data.id + '/edit"><span title="{{trans('AgendaItems.edit')}}" class="ion-edit" aria-hidden="true"></span></a>';
            actions += '<a href="{{url('agendaItems')}}/' + data.id + '"><span title="{{trans('AgendaItems.show')}}" class="ion-eye" aria-hidden="true"></span></a>';
            if(data.application_form_id != null){
                actions += '<a href="{{url('/forms/users')}}/' + data.id + '"><span title="{{trans("AgendaItems.showsignups")}}" class="ion-android-list" aria-hidden="true"></span></a>';
            }
            return actions;
        }

        function getUrl(){
            let params = "limit=10000000";
            let datePicker = $('#startDate');
            if(datePicker.val() != ""){
                params += "&startDate=" + datePicker.val();
            }

            return "{{url("api/agenda")}}?" + params;
        }
    </script>
@endpush