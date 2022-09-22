@extends('layouts.beheer')

@section('title')
{{trans("agendaItemCategory.Categories")}}
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
            <h1>{{trans("agendaItemCategory.Categories")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('agendaItemCategories/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("agendaItemCategory.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('agendaItemCategory.name')}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($agendaItemCategories as $category)
            <tr>
                <td>{{$category->categorieName->text()}}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/agendaItemCategories/' . $category->id . '/edit')}}"><span title="{{trans('agendaItemCategory.edit')}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/agendaItemCategories/'. $category->id)}}"><span title="{{trans("agendaItemCategory.show")}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
