@extends('layouts.admin')

@section('title')
Member log
@endsection

@section('content')
    <x-page-header title="Member log">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#export-member-log-modal"><span title="Export the member log" class="ion-android-download" aria-hidden="true"></span> Export</button>
    </x-page-header>
    <x-data-table id="member-log-table" :columns="['Type', 'Time', 'Details']">
        @foreach ($entries as $entry)
            <tr>
                <td>{{$entry->type}}</td>
                <td><x-local-time :value="$entry->created_at" format="Y-m-d H:i:s" /></td>
                <td>{{$entry->details}}</td>
            </tr>
        @endforeach
    </x-data-table>
    
    @include("admin.member-log.export-modal")
@endsection

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js'])
    <script type="module">
        $('#member-log-table').DataTable({
            order: [[1, 'desc']],
        });
    </script>
@endpush