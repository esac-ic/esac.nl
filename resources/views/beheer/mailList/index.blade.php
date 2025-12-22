@extends('layouts.beheer')

@section('title')
{{'Mailing list'}}
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
            <h1>{{'Mailing list'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <form method="post" action="{{ action([App\Http\Controllers\MailListController::class, 'massMemberMailListSync']) }}" onsubmit="return confirm('Are you sure?\nThis action adds users to all maillists associated with their membership type.\nThis may cause people who have unsubscribed from a maillist to be readded.')">
                    @csrf
                    <button title="{{'Sync member mail lists'}}" type="submit" class="btn btn-primary"><span class="ion-loop" aria-hidden="true"></span> {{'Sync member mail lists'}}</button>
                </form>    
                <a href="{{url('mailList/create')}}" class="btn btn-primary" title="{{'New mailing list'}}">
                    <span class="ion-plus" aria-hidden="true"></span>
                    {{'New mailing list'}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Name'}}</th>
            <th>{{'Email'}}</th>
            <th>{{'Members count'}}</th>
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
                    <a href="{{url('/mailList/'. $mailingList->getAddress())}}"><span title="{{'Show mailing list'}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
