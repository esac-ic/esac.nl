@extends('layouts.beheer')

@section('title')
{{'Member'}}
@endsection

@section('content')
    @include('includes.flash')

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Current members'}}</h1>
        </div>

        @can('manage-members')
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{route('users.create')}}" class="btn btn-primary" title="{{'New user'}}">
                    <span class="ion-person-add" aria-hidden="true"></span>
                    {{'New user'}}
                </a>
                <a href="{{route('users.pennoMemberExportView')}}" class="btn btn-primary" title="{{'Export only the fields needed for the financial administration'}}" >
                    <span class="ion-android-download" aria-hidden="true"></span>
                    {{'Penno export'}}
                </a>
                <a href="{{route('users.exportUsers')}}" class="btn btn-primary" title="{{'Export all active members'}}">
                    <span class="ion-android-download" aria-hidden="true"></span>
                    {{'Export members'}}
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
            <th>{{'Roles'}}*</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody class="table-striped">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{trans('kind_of_member.' . $user->kind_of_member)}}</td>
                <td>@foreach($user->roles as $role) {{$role->id}}@endforeach</td>
                <td>
                    <a class="mr-1 ml-1" href="{{route('users.edit', $user)}}"><span title="{{'Edit user'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{route('users.show', $user)}}"><span title="{{'Show user information'}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h5 class="mt-3 mb-0 bold">*{{'Roles'}}</h5>
    <ol>
    @foreach($roles as $role)
        <li>{{$role->name}}</li>
    @endforeach
    </ol>
@endsection
