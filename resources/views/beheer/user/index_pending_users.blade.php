@extends('layouts.beheer')

@section('title')
{{'Pending users'}}
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
            <h1>{{'Pending users'}}</h1>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Email address'}}</th>
            <th>{{'Type of member'}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($users as $user)
            <tr >
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{trans('kind_of_member.' . $user->kind_of_member)}}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/users/' . $user->id . '/edit')}}"><span title="{{'Edit user'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/users/'. $user->id)}}"><span title="{{'Show user information'}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
