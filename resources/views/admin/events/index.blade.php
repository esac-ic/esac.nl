@extends('layouts.admin')

@section('title')
    Events
@endsection

@section('content')
    <x-page-header title="Events">
        <x-button-link :href="route('admin.events.create')" icon="plus">New event</x-button-link>
    </x-page-header>
    <form method="get" action="{{ route('admin.events.index') }}" class="form-row align-items-end mb-3">
        <x-field type="date" name="start_date" label="Ending on or after" :value="$startDate" col="3" />
        <div class="form-group col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <x-data-table datatable :data-order="json_encode([[1, 'asc']])" :columns="['Title', 'Start date', 'End date', 'Actions']">
        @foreach ($events as $event)
            <tr>
                <td><a href="{{ route('admin.events.show', $event) }}">{{ $event->title }}</a></td>
                <td data-order="{{ $event->start_date->timestamp }}">{{ $event->start_date->format('d-m-Y H:i') }}</td>
                <td data-order="{{ $event->end_date->timestamp }}">{{ $event->end_date->format('d-m-Y H:i') }}</td>
                <td>
                    <x-actions-menu :label="$event->title">
                        <x-actions-menu.item :href="route('admin.events.show', $event)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.events.edit', $event)" icon="pencil-square">Edit</x-actions-menu.item>
                        @if ($event->registration_form_id)
                            @can('manage-registrations')
                                <x-actions-menu.item :href="route('admin.events.registrations.index', $event)" icon="list-bullet">Registrations</x-actions-menu.item>
                            @endcan
                        @endif
                        <x-actions-menu.item :href="route('admin.events.create', ['copy' => $event])" icon="document-duplicate">New event based on this</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
