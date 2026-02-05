@extends('layouts.beheer')

@section('title')
{{'Committees'}}
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
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{Session::get('error')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Committees'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{route('beheer.committees.create')}}" class="btn btn-primary" title="{{'New committee'}}">
                    <span class="ion-plus" aria-hidden="true"></span>
                    {{'New committee'}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Abbreviation'}}</th>
            <th>{{'Members count'}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($committees as $committee)
            <tr >
                <td>{{$committee->name}}</td>
                <td>{{$committee->abbreviation}}</td>
                <td>{{$committee->getMemberCount()}}</td>
                <td>
                    <a href="{{route('beheer.committees.edit', $committee)}}"><span title="{{'Edit committee'}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{route('beheer.committees.show', $committee)}}"><span title="{{'Show committee'}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
