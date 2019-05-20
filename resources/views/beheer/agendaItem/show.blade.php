@extends('layouts.beheer')

@section('title')
{{trans('AgendaItems.info')}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Agenda item</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/agendaItems/'.$agendaItem->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/agendaItems/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                    </a>
                @endif
                {{ Form::open(array('url' => 'agendaItems/' . $agendaItem->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('AgendaItems.info')}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{trans('AgendaItems.title')}}</td>
                    <td>{{$agendaItem->agendaItemTitle->text()}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.shortDescription')}}</td>
                    <td>{{$agendaItem->agendaItemShortDescription->text()}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.startDate')}}</td>
                    <td>{{\Carbon\Carbon::parse($agendaItem->startDate)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.endDate')}}</td>
                    <td>{{\Carbon\Carbon::parse($agendaItem->endDate)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.endDateSubscription')}}</td>
                    <td>{{($agendaItem->subscription_endDate !=null)? \Carbon\Carbon::parse($agendaItem->subscription_endDate)->format('d-m-Y h:i') : ""}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.category')}}</td>
                    <td>{{$agendaItem->agendaItemCategory->categorieName->text()}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.climbingActivity')}}</td>
                    <td>{{$agendaItem->climbing_activity ? trans('menu.yes') : trans('menu.no')}}</td>
                </tr>
                <tr>
                    <td>{{trans('AgendaItems.createdBy')}}</td>
                    <td>{{$agendaItem->getCreatedBy->getName()}}</td>
                </tr>
                <tr>
                    <td>{{trans("AgendaItems.applicationForm")}}</td>
                    <td>{{($agendaItem->application_form_id !=null)? $agendaItem->getApplicationForm->applicationFormName->text():""}}</td>
                </tr>
                <tr>
                    <td>{{trans("AgendaItems.tumpnailImage")}}</td>
                    <td><img src="{{$agendaItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card my-4">
        <div class="card-header">
            <h3>{{trans('AgendaItems.content') }}</h3>
        </div>
        <div class="card-body">
            {!! $agendaItem->agendaItemText->text() !!}
        </div>
    </div>
@endsection