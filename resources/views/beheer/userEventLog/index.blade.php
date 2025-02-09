@extends('layouts.beheer')

@section('title')
{{'User event log'}}
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
            <h1>{{'User event log'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <!-- <a href="{{url('userEventLog/exportDialog')}}" class="btn btn-primary">
                    <span title="{{'Export event log'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'Export'}}
                </a> -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportUserEventLogModal"><span title="{{'Export event log'}}" class="ion-android-download" aria-hidden="true"></span> {{"Export"}}</button>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>Event type</th>
            <th>Time</th>
            <th>User</th>
            <th>Event details</th>
            <th>Management</th>
        </tr>
        <!-- <tr class="d-flex">
            <th class="col-sm-auto">Event type</th>
            <th class="col-sm-auto">Time</th>
            <th class="col-sm-auto">User</th>
            <th class="col">Event details</th>
            <th class="col-2">Management</th>
        </tr> -->
        </thead>
        <tbody>

        @foreach ($events as $entry)
            <tr>
                <td>{{$entry->eventType}}</td>
                <td>{{$entry->created_at}}</td>
                <td>{{$entry->user->getName()}}</td>
                <td>{{$entry->eventDetails}}</td>
                
                <td>
                    {{ Form::open(array('url' => 'userEventLog/' . $entry->id, 'method' => 'delete')) }}
                    <button type="submit" class="btn p-0"><span title="Delete entry" class="ion-trash-a font-size-120 text-primary" aria-hidden="true"></span></button>
                    {{ Form::close() }}
                </td>
            </tr>
            <!-- <tr class="d-flex">
                <td class="col-sm-auto">{{$entry->eventType}}</td>
                <td class="col-sm-auto">{{$entry->created_at}}</td>
                <td class="col-sm-auto">{{$entry->user->getName()}}</td>
                <td class="col">{{$entry->eventDetails}}</td>
                
                <td class="col-2">
                    <a class="mr-1 ml-1" href="{{url('/userEventLogEntry/' . $entry->id . '/delete')}}"><span title="Delete entry" class="ion-trash-a font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr> -->
        @endforeach
        </tbody>
    </table>
    
    @include("beheer.userEventLog.export_dialog")
@endsection