@extends('layouts.beheer')

@section('title')
{{'Application forms'}}
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
            <h1>{{'Application forms'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('beheer.applicationForms.create') }}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New application form'}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{ 'Management' }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($applicationForms as $applicationForm)
                <tr >
                    <td>{{$applicationForm->name}}</td>
                    <td>
                        <a class="mr-1 ml-1" href="{{route('beheer.applicationForms.edit', $applicationForm->id)}}"><span title="{{'Edit application form'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                        <a class="mr-1 ml-1" href="{{route('beheer.applicationForms.show', $applicationForm->id)}}"><span title="Show application form" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
