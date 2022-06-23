@extends('layouts.beheer')

@section('title')
{{trans("user.old_members")}}
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
            <h1>{{trans("user.old_members")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('user.name')}}</th>
            <th>{{trans('user.email')}}</th>
            <th>{{trans('user.kind_of_member')}}</th>
            <th>{{trans('menu.beheer')}}</th>
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
                    <a class="mr-1 ml-1" href="{{url('/users/' . $user->id . '/edit')}}"><span title="{{trans('user.edit')}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/users/'. $user->id)}}"><span title="{{trans("user.show")}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
