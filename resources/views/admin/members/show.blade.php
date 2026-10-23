@extends('layouts.admin')

@section('title')
{{ $user->name }}
@endsection

@section('content')
    @php([$title, $backRoute, $backLabel] = match (true) {
        $user->isPendingMember() => ['Pending member', 'admin.pending-members.index', 'Pending members'],
        $user->isFormerMember() => ['Former member', 'admin.former-members.index', 'Former members'],
        default => ['Member', 'admin.members.index', 'Members'],
    })
    <x-page-header :$title :back="route($backRoute)" :back-label="$backLabel">
        @if($user->isPendingMember())
            @can('manage-members')
                {{ html()->form('PATCH', route('admin.pending-members.approve', $user))->data('confirm-title', 'Approve sign-up')->data('confirm', 'Approve '.$user->name.' as a member? They are added to the mail lists of their member type.')->data('confirm-button', 'Approve')->data('confirm-danger', 'false')->open() }}
                <button type="submit" class="btn btn-success text-nowrap"><em class="ion-checkmark"></em> Approve as member</button>
                {{ html()->form()->close() }}
                <x-delete-button :action="route('admin.pending-members.destroy', $user)" title="Reject sign-up" :confirm="'Reject the sign-up of '.$user->name.'? Their details are deleted.'" button="Reject sign-up" />
            @endcan
        @else
            @can('manage-members')
                <x-button-link :href="route('admin.members.edit', $user)" icon="edit">Edit</x-button-link>
            @endcan
            @can('manage-certificates')
                <x-button-link :href="route('admin.members.certificates.create', $user)" icon="plus">Add certificate</x-button-link>
            @endcan
            @can('manage-members')
                @if($user->isActiveMember())
                    {{ html()->form('DELETE', route('admin.members.membership.destroy', $user))->class('page-actions-destructive')->data('confirm-title', 'End membership')->data('confirm', 'End the membership of '.$user->name.'? They are logged out everywhere and removed from the mail lists. You can restore it later.')->data('confirm-button', 'End membership')->open() }}
                    <button type="submit" class="btn btn-outline-danger text-nowrap"><span class="ion-log-out" aria-hidden="true"></span> End membership</button>
                    {{ html()->form()->close() }}
                @elseif($user->isFormerMember())
                    {{ html()->form('POST', route('admin.members.membership.store', $user))->data('confirm-title', 'Restore membership')->data('confirm', 'Restore the membership of '.$user->name.'? They can log in again and are added to the mail lists.')->data('confirm-button', 'Restore')->data('confirm-danger', 'false')->open() }}
                        <button type="submit" class="btn btn-success text-nowrap"><em class="ion-refresh"></em> Restore membership</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
        @endif
    </x-page-header>
    <x-card :title="$user->name" class="mt-4">
        <x-member-tabs :$user :details="auth()->user()->can('view-member-details')" :registrations-tab="auth()->user()->can('manage-registrations')">
            <x-slot:certificates>
                <x-data-table :columns="['Name', 'Abbreviation', 'Actions']">
                    @forelse($user->certificates as $certificate)
                        <tr>
                            <td>{{ $certificate->name }}</td>
                            <td>{{ $certificate->abbreviation }}</td>
                            <td>
                                <x-actions-menu :label="$certificate->name">
                                    <x-actions-menu.item :href="route('admin.members.certificates.edit', [$user, $certificate])" icon="edit">Change certificate</x-actions-menu.item>
                                    <x-actions-menu.delete :action="route('admin.members.certificates.destroy', [$user, $certificate])" title="Remove certificate" :confirm="'Remove '.$certificate->name.' from '.$user->name.'?'">Remove certificate</x-actions-menu.delete>
                                </x-actions-menu>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted">No certificates</td></tr>
                    @endforelse
                </x-data-table>
            </x-slot:certificates>
            <x-slot:registrations>
                <x-data-table datatable :data-order="json_encode([[1, 'asc']])" :columns="['Name', 'Start date', 'Actions']">
                    @foreach ($user->registrations as $registration)
                        <tr>
                            <td><a href="{{ route('admin.events.show', $registration->event) }}">{{ $registration->event->title }}</a></td>
                            <td data-order="{{ $registration->event->start_date->timestamp }}">{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                <x-actions-menu :label="$registration->event->title">
                                    <x-actions-menu.item :href="route('admin.events.registrations.show', [$registration->event_id, $registration])" icon="eye">View answers</x-actions-menu.item>
                                    <x-actions-menu.delete :action="route('admin.events.registrations.destroy', [$registration->event_id, $registration])" title="Remove registration" :confirm="'Remove the registration of '.$user->name.' for '.$registration->event->title.'? Their answers are deleted.'">Remove registration</x-actions-menu.delete>
                                </x-actions-menu>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:registrations>
        </x-member-tabs>
    </x-card>
@endsection
