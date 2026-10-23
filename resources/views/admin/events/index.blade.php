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
                <td>{{ $event->title }}</td>
                <td data-order="{{ $event->start_date->timestamp }}">{{ $event->start_date->format('d-m-Y H:i') }}</td>
                <td data-order="{{ $event->end_date->timestamp }}">{{ $event->end_date->format('d-m-Y H:i') }}</td>
                <td>
                    <x-icon-link :href="route('admin.events.edit', $event)" icon="edit" title="Edit event" />
                    <x-icon-link :href="route('admin.events.show', $event)" icon="eye" title="Show event" />
                    @if ($event->registration_form_id && auth()->user()->can('manage-registrations'))
                        <x-icon-link :href="route('admin.events.registrations.index', $event)" icon="android-list" title="Show registrations" />
                    @endif
                    <form class="d-inline" method="post" action="{{ route('admin.events.copy', $event) }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 mr-1 ml-1"><span title="Copy event" class="ion-ios-copy font-size-120" aria-hidden="true"></span></button>
                    </form>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
