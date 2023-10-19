@extends('layouts.beheer')

@section('title')
{{'Pages'}}
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
            <h1>{{'Pages'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('pages/create')}}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New page'}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Sub item of'}}</th>
            <th>{{'After menu item'}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($pages as $menu)
            <tr>
                <td>{{$menu->name}}</td>
                <td>{{($menu->partner != null) ? $menu->partner->name : ""}}</td>
                <td>{{($menu->after != null) ? $menu->afterItem->name : ""}}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/pages/' . $menu->id . '/edit')}}"><span title="{{'Edit page'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/pages/'. $menu->id)}}"><span title="Show page" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
