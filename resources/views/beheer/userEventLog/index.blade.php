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
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportUserEventLogModal"><span title="{{'Export event log'}}" class="ion-android-download" aria-hidden="true"></span> {{"Export"}}</button>
            </div>
        </div>
    </div>
    <table id="eventLog" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Event type</th>
                <th>Time</th>
                <th>Event details</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($events as $entry)
            <tr>
                <td>{{$entry->eventType}}</td>
                <td>{{$entry->created_at}}</td>
                <td>{{$entry->eventDetails}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    @include("beheer.userEventLog.export_dialog")
@endsection

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}"></script>
    @yield('modal_javascript')
    <script>
        $('#eventLog').DataTable({
            order: [[1, 'desc']],
        });
    </script>
@endpush