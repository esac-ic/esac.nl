@extends('layouts.beheer')

@section('title')
{{trans('agendaItemCategory.Category')}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans('agendaItemCategory.Category')}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/agendaItemCategories/'.$agendaItemCategory->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{trans("menu.edit")}}
                </a>
                <a href="{{url('/agendaItemCategories/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{trans("menu.back")}}
                </a>
                {{ Form::open(array('url' => 'agendaItemCategories/' . $agendaItemCategory->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans('menu.delete')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('agendaItemCategory.Category')}}</h3>
        </div>
        <div class="card-body">
            {{trans('agendaItemCategory.name'). ": " . $agendaItemCategory->categorieName->text()}}
        </div>
    </div>
@endsection