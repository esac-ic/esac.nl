@extends('layouts.beheer')

@section('title')
{{'Old members'}}
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
            <h1>{{'Old members'}}</h1>
        </div>

        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('users/exportOldUsers')}}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-android-download" aria-hidden="true"></span>
                    {{'Export old members'}}
                </a>
            </div>
        </div>
        @endif
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Email address'}}</th>
            <th>{{'Type of member'}}</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($users as $user)
            <tr >
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @isset($user->kind_of_member)
                    {{trans('kind_of_member.' . $user->kind_of_member)}}
                    @endisset
                </td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/users/' . $user->id . '/edit')}}"><span title="{{'Edit user'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/users/'. $user->id)}}"><span title="{{'Show user information'}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
