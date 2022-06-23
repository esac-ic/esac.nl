@extends('layouts.beheer')

@section('title')
{{trans("user.user")}}
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
            <h1>{{trans("user.current_members")}}</h1>
        </div>

        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('users/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-person-add" aria-hidden="true"></span>
                    {{trans("user.new")}}
                </a>
                <a href="{{url('users/exportUsers')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-android-download" aria-hidden="true"></span>
                    {{trans("user.export_users")}}
                </a>
            </div>
        </div>
        @endif
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('user.name')}}</th>
            <th>{{trans('user.email')}}</th>
            <th>{{trans('user.kind_of_member')}}</th>
            <th>{{trans('user.rols')}}*</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody class="table-striped">
        @foreach ($users as $user)
            <tr>
                <td>{{$user->getName()}}</td>
                <td>{{$user->email}}</td>
                <td>{{trans('kind_of_member.' . $user->kind_of_member)}}</td>
                <td>@for($i=0; $i<count($user->roles);$i++) {{$user->roles[$i]->id}}@endfor</td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/users/' . $user->id . '/edit')}}"><span title="{{trans('user.edit')}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/users/'. $user->id)}}"><span title="{{trans("user.show")}}" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h5 class="mt-3 mb-0 bold">*{{trans('user.rols')}}</h4>
    <ol>
    @for($i=0; $i<count($roles);$i++)
        <li>{{$roles[$i]->text->text()}}</li>
    @endfor
    </ol>
@endsection
