@extends('layouts.admin')

@section('title')
    Events
@endsection

@section('content')
    <x-page-header title="Events">
        <x-button-link :href="route('admin.events.create')" icon="plus">New event</x-button-link>
    </x-page-header>
    <div class="row mb-3">
        <x-field type="date" name="startDate" label="Start date" :value="today(config('app.display_timezone'))->toDateString()" col="3" />
    </div>

    <x-data-table id="events-table" :columns="['Title', 'Start date', 'End date', 'Actions']" />
@endsection

@push('styles')
    <style>
        .action-icon {
            margin-left: 5px;
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/assets/js/vendor/datatables.js'])
    <script type="module">
        let eventsTable = $('#events-table').DataTable({
            "order": [[1, "asc"]],
            "ajax": {
                'url': eventsApiUrl(),
                "dataSrc": "agendaItems"
            },
            "columns": [
                {"data": "title"},
                {"data": "full_startDate", "render": formatDate},
                {"data": "endDate", "render": formatDate},
                {
                    "data": function (data) {
                        return eventActionLinks(data);
                    }
                },
            ]
        });

        document.getElementById('startDate').addEventListener('change', () => eventsTable.ajax.url(eventsApiUrl()).load());

        // Shows a "Y-m-d H:i:s" date as "d-m-Y H:i", sorting on the original.
        function formatDate(date, type) {
            return type === 'display' ? date.slice(0, 16).replace(/^(\d+)-(\d+)-(\d+)/, '$3-$2-$1') : date;
        }

        function eventActionLinks(data) {
            let actions = "";
            actions += '<a class="mr-1 ml-1" href="' + '{{ route('admin.events.edit', '__id__') }}'.replace('__id__', data.id) + '"><span title="Edit event" class="ion-edit font-size-120 action-icon" aria-hidden="true"></span></a>';
            actions += '<a class="mr-1 ml-1" href="' + '{{ route('admin.events.show', '__id__') }}'.replace('__id__', data.id) + '"><span title="Show event" class="ion-eye font-size-120 action-icon" aria-hidden="true"></span></a>';
            if (data.application_form_id != null) {
                actions += '<a class="mr-1 ml-1" href="' + '{{ route('admin.events.registrations.index', '__id__') }}'.replace('__id__', data.id) + '"><span title="Show registrations" class="ion-android-list font-size-120 action-icon" aria-hidden="true"></span></a>';
            }
            actions += '<form class="d-inline" method="POST" action="' + '{{ route('admin.events.copy', '__id__') }}'.replace('__id__', data.id) + '">@csrf<button type="submit" class="btn btn-link p-0 mr-1 ml-1"><span title="Copy event" class="ion-ios-copy font-size-120 action-icon" aria-hidden="true"></span></button></form>';
            return actions;
        }

        function eventsApiUrl() {
            let params = "limit=10000000";
            let startDate = document.getElementById('startDate').value;
            if (startDate !== "") {
                params += "&startDate=" + startDate.split('-').reverse().join('-');
            }

            return "{{ route('api.events.index') }}?" + params;
        }
    </script>
@endpush