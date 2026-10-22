@extends('layouts.beheer')

@section('title')
{{'User event log'}}
@endsection

@section('content')
    @include('includes.flash')
    
    <x-page-header title="User event log">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportUserEventLogModal"><span title="{{'Export event log'}}" class="ion-android-download" aria-hidden="true"></span> {{"Export"}}</button>
    </x-page-header>
    <x-data-table id="eventLog" :columns="['Event type', 'Time', 'Event details']">
        @foreach ($events as $entry)
            <tr>
                <td>{{$entry->event_type}}</td>
                <td>{{ $entry->created_at->tz(config('app.display_timezone')) }}</td>
                <td>{{$entry->event_details}}</td>
            </tr>
        @endforeach
    </x-data-table>
    
    @include("beheer.userEventLog.export_dialog")
@endsection

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js'])
    @yield('modal_javascript')
    <script type="module">
        $('#eventLog').DataTable({
            order: [[1, 'desc']],
        });
    </script>
@endpush