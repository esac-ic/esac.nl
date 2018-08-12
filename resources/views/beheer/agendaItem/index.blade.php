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
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('AgendaItems.title')}}</th>
            <th>{{trans('AgendaItems.startDate')}}</th>
            <th>{{trans('AgendaItems.endDate')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($agendaItems as $agendaItem)
            <tr>
                <td>{{$agendaItem->agendaItemTitle->text()}}</td>
                <td>{{\Carbon\Carbon::parse($agendaItem->startdate)->format('d M Y')}}</td>
                <td>{{\Carbon\Carbon::parse($agendaItem->endDate)->format('d M Y')}}</td>
                <td>
                    <a href="{{url('/agendaItems/' . $agendaItem->id . '/edit')}}"><span title="{{trans('AgendaItems.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/agendaItems/'. $agendaItem->id)}}"><span title="{{trans("AgendaItems.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                    @if($agendaItem->application_form_id != null)
                        <a href="{{url('/forms/users/'. $agendaItem->id)}}"><span title="{{trans("AgendaItems.showsignups")}}" class="ion-android-list" aria-hidden="true"></span></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script type="text/javascript">

    </script>
@endpush