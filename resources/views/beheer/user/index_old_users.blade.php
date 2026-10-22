@extends('layouts.beheer')

@section('title')
{{'Old members'}}
@endsection

@section('content')
    @include('includes.flash')

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Old members'}}</h1>
        </div>

        @can('manage-members')
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{ route('users.exportOldUsers') }}" class="btn btn-primary">
                    <span title="{{'New user'}}" class="ion-android-download" aria-hidden="true"></span>
                    {{'Export old members'}}
                </a>
            </div>
        </div>
        @endcan
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
                    <a class="mr-1 ml-1" href="{{route('users.edit', $user)}}"><span title="{{'Edit user'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{route('users.show', $user)}}"><span title="{{'Show user information'}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
