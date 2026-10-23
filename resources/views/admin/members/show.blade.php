@extends('layouts.admin')

@section('title')
{{ $user->name }}
@endsection

@section('content')
    <x-page-header title="Member">
        @if($user->isPendingMember())
            @can('manage-members')
                {{ html()->form('PATCH', route('admin.pending-members.approve', $user))->open() }}
                <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> Approve as member</button>
                {{ html()->form()->close() }}
                <x-delete-button :action="route('admin.pending-members.destroy', $user)" :confirm="'Are you sure you want to remove '.$user->name.' as a pending member?'">Reject sign-up</x-delete-button>
            @endcan
        @else
            @can('manage-members')
                @if($user->isFormerMember())
                    {{ html()->form('POST', route('admin.members.membership.store', $user))->open() }}
                        <button type="submit" class="btn btn-success"><em class="ion-plus"></em> Restore membership</button>
                    {{ html()->form()->close() }}
                @endif
                <x-button-link :href="route('admin.members.edit', $user)" icon="edit">Edit</x-button-link>
            @endcan
            @can('manage-certificates')
                <x-button-link :href="route('admin.members.certificates.create', $user)" icon="plus">Add certificate</x-button-link>
            @endcan
            @can('manage-members')
                @if($user->isActiveMember())
                    {{ html()->form('DELETE', route('admin.members.membership.destroy', $user))->open() }}
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> End membership</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
        @endif
        <x-button-link :href="route('admin.members.index')" icon="android-arrow-back">Back</x-button-link>
    </x-page-header>
    <x-card :title="$user->name" class="mt-4">
        <x-member-tabs :$user :details="auth()->user()->can('view-member-details')" :registrations-tab="auth()->user()->can('manage-registrations')">
            <x-slot:certificates>
                <x-data-table :columns="['Name', 'Abbreviation', 'Actions']">
                    @foreach($user->certificates as $certificate)
                        <tr>
                            <td>{{ $certificate->name }}</td>
                            <td>{{ $certificate->abbreviation }}</td>
                            <td>
                                <x-icon-link :href="route('admin.members.certificates.edit', [$user, $certificate])" icon="edit" title="Edit certificate" />
                                <x-delete-button icon :action="route('admin.members.certificates.destroy', [$user, $certificate])" confirm="Are you sure you want to remove this certificate?">Remove certificate</x-delete-button>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:certificates>
            <x-slot:registrations>
                <x-data-table datatable :data-order="json_encode([[1, 'asc']])" :columns="['Name', 'Start date', 'Action']">
                    @foreach ($user->registrations as $registration)
                        <tr>
                            <td>{{ $registration->event->title }}</td>
                            <td data-order="{{ $registration->event->start_date->timestamp }}">{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                <x-icon-link :href="route('admin.events.registrations.show', [$registration->event_id, $registration])" icon="eye" title="Show entered information" />
                                <x-delete-button icon :action="route('admin.events.registrations.destroy', [$registration->event_id, $registration])" confirm="Are you sure you want to remove this registration?">Remove registration</x-delete-button>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:registrations>
        </x-member-tabs>
    </x-card>
@endsection
