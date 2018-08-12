@extends('layouts.beheer')

@section('title')
{{trans("ApplicationForm.ApplicationForms")}}
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
            <h1>{{trans("ApplicationForm.ApplicationForms")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('applicationForms/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("ApplicationForm.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('ApplicationForm.name')}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($applicationForms as $applicationForm)
            <tr >
                <td>{{$applicationForm->applicationFormName->text()}}</td>
                <td>
                    <a href="{{url('/applicationForms/' . $applicationForm->id . '/edit')}}"><span title="{{trans('ApplicationForm.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/applicationForms/'. $applicationForm->id)}}"><span title="{{trans("ApplicationForm.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection