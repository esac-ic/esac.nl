@extends('layouts.admin')

@section('title')
User event log
@endsection

@section('content')
    <x-page-header title="User event log">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exportUserEventLogModal"><span title="Export event log" class="ion-android-download" aria-hidden="true"></span> Export</button>
    </x-page-header>
    <x-data-table id="eventLog" :columns="['Event type', 'Time', 'Event details']">
        @foreach ($events as $entry)
            <tr>
                <td>{{$entry->event_type}}</td>
                <td><x-local-time :value="$entry->created_at" format="Y-m-d H:i:s" /></td>
                <td>{{$entry->event_details}}</td>
            </tr>
        @endforeach
    </x-data-table>
    
    @include("admin.user-event-log.export_dialog")
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