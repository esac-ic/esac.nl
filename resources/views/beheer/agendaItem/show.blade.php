@extends('layouts.beheer')

@section('title')
{{'Event general information'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Agenda item</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/agendaItems/'.$agendaItem->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/agendaItems/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{'Back'}}
                    </a>
                @endif
                {{ Form::open(array('url' => 'agendaItems/' . $agendaItem->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Event general information'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{'Title'}}</td>
                    <td>{{$agendaItem->title}}</td>
                </tr>
                <tr>
                    <td>{{'Short description'}}</td>
                    <td>{{$agendaItem->shortDescription}}</td>
                </tr>
                <tr>
                    <td>{{'Start date'}}</td>
                    <td>{{\Carbon\Carbon::parse($agendaItem->startDate)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{'End date'}}</td>
                    <td>{{\Carbon\Carbon::parse($agendaItem->endDate)->format('d-m-Y h:i')}}</td>
                </tr>
                <tr>
                    <td>{{'Last possible registration date'}}</td>
                    <td>{{($agendaItem->subscription_endDate !=null)? \Carbon\Carbon::parse($agendaItem->subscription_endDate)->format('d-m-Y h:i') : ""}}</td>
                </tr>
                <tr>
                    <td>{{'Category'}}</td>
                    <td>{{$agendaItem->agendaItemCategory->name}}</td>
                </tr>
                <tr>
                    <td>{{'Show certificates in the event item'}}</td>
                    <td>{{$agendaItem->climbing_activity ? 'Yes' : 'No'}}</td>
                </tr>
                <tr>
                    <td>{{'Created by'}}</td>
                    <td>{{$agendaItem->getCreatedBy->getName()}}</td>
                </tr>
                <tr>
                    <td>{{'Application form'}}</td>
                    <td>{{($agendaItem->application_form_id !=null)? $agendaItem->getApplicationForm->name:""}}</td>
                </tr>
                <tr>
                    <td>{{'Thumbnail image'}}</td>
                    <td><img src="{{$agendaItem->getImageUrl()}}" style="max-width: 240px" class="img-fluid"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card my-4">
        <div class="card-header">
            <h3>{{'Event item content' }}</h3>
        </div>
        <div class="card-body">
            {!! $agendaItem->text !!}
        </div>
    </div>
@endsection
