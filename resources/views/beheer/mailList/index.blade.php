@extends('layouts.beheer')

@section('title')
{{trans('MailList.maillist')}}
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
            <h1>{{trans("MailList.mailList")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('mailList/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("MailList.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('MailList.name')}}</th>
            <th>{{trans('MailList.address')}}</th>
            <th>{{trans('MailList.members_count')}}</th>
            <th>Beheer</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($mailLists as $mailingList)
            <tr >
                <td>{{$mailingList->getName()}}</td>
                <td>{{$mailingList->getAddress()}}</td>
                <td>{{$mailingList->getMembersCount()}}</td>
                <td>
                    <a href="{{url('/mailList/'. $mailingList->getAddress())}}"><span title="{{trans("MailList.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
