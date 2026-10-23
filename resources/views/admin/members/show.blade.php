@extends('layouts.admin')

@section('title')
{{ $user->name }}
@endsection

@section('content')
    <x-page-header title="Member">
        @if($user->isPendingMember() && auth()->user()->can('manage-members'))
            {{ html()->form('POST', route('admin.pending-members.approve', $user))->open() }}
            @method('PATCH')
            <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> Approve as member</button>
            {{ html()->form()->close() }}
        
            <form method="post"
                  action="{{ route("admin.pending-members.destroy", $user) }}"
                  onsubmit="return confirm('Are you sure you want to remove {{$user->name}} as a pending member?');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> Reject sign-up</button>
            </form>
        @else
            @can('manage-members')
                @if($user->isFormerMember())
                    {{ html()->form('POST', route('admin.members.membership.store', $user))->open() }}
                        <button type="submit" class="btn btn-success"><em class="ion-plus"></em> Restore membership</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
            @can('manage-members')
                <x-button-link :href="route('admin.members.edit', $user)" icon="edit">Edit</x-button-link>
            @endcan
            @can('manage-certificates')
                <x-button-link :href="route('admin.members.certificates.create', $user)" icon="plus">Add certificate</x-button-link>
            @endcan
            <x-button-link :href="route('admin.members.index')" icon="android-arrow-back">Back</x-button-link>
            @can('manage-members')
                @if($user->isActiveMember())
                    {{ html()->form('POST', route('admin.members.membership.destroy', $user))->open() }}
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> End membership</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
        @endif
    </x-page-header>
    <x-card :title="$user->name" class="mt-4">
        <x-member-tabs :$user :details="auth()->user()->can('view-member-details')" :registrations-tab="auth()->user()->can('manage-registrations')">
            <x-slot:certificates>
                <table class="table table-striped" style="width:100%">
                    <thead>
                    <tr>
                        <td>
                            <strong>Name</strong>
                        </td>
                        <td>
                            <strong>Abbreviation</strong>
                        </td>
                        <td>
                            <strong>Actions</strong>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($user->certificates as $certificate)
                        <tr>
                            <td>
                                {{$certificate->name}}
                            </td>
                            <td>
                                {{$certificate->abbreviation}}
                            </td>
                            <td>
                                <a href="{{ route('admin.members.certificates.edit', [$user, $certificate]) }}"><span title="Edit Certificate" class="ion-edit" aria-hidden="true"></span></a>
                                <a href="{{ route('admin.members.certificates.destroy', [$user, $certificate]) }}" onclick="event.preventDefault(); document.getElementById('remove-certificate-{{$certificate->id}}').submit();">
                                    <span title="Remove Certificate" class="ion-trash-a" aria-hidden="true"></span>
                                </a>
                                {{ html()->form('POST', route('admin.members.certificates.destroy', [$user, $certificate]))->id("remove-certificate-$certificate->id")->open() }}
                                @method('DELETE')
                                {{ html()->form()->close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-slot:certificates>
            <x-slot:registrations>
                <x-data-table datatable :data-order="json_encode([[1, 'asc']])" :columns="['Name', 'Start date', 'Action']">
                    @foreach ($user->registrations as $registration)
                        <tr>
                            <td>{{ $registration->event->title }}</td>
                            <td data-order="{{ $registration->event->start_date->timestamp }}">{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.events.registrations.show', [$registration->event_id, $registration]) }}"><span title="Show entered information" class="ion-eye" aria-hidden="true"></span></a>
                                <form class="d-inline" method="post" action="{{ route('admin.events.registrations.destroy', [$registration->event_id, $registration]) }}" onsubmit="return confirm('Are you sure you want to remove this registration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0"><span title="Remove registration" class="ion-trash-a" aria-hidden="true"></span></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-slot:registrations>
        </x-member-tabs>
    </x-card>
@endsection
