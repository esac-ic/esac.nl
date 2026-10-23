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
            <x-button-link :href="route('admin.members.edit', $user)" icon="edit">Edit</x-button-link>
            @can('manage-certificates')
                <x-button-link :href="route('admin.members.certificates.create', $user)" icon="plus">Add certificate</x-button-link>
            @endcan
            @can('view-members')
                <x-button-link :href="route('admin.members.index')" icon="android-arrow-back">Back</x-button-link>
            @endcan
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
        <ul class="nav nav-tabs" id="member-tabs" role="tablist">
            @can('update', $user)
            <li class="nav-item">
                <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">Personal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial" role="tab" aria-controls="financial" aria-selected="false">Financial</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="emergency-tab" data-toggle="tab" href="#emergency" role="tab" aria-controls="emergency" aria-selected="false">Emergency info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">Roles</a>
            </li>
            @endcan
            <li class="nav-item">
                <a @class(['nav-link', 'active' => auth()->user()->cannot('update', $user)]) id="certificates-tab" data-toggle="tab" href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">Certificates</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="registrations-tab" data-toggle="tab" href="#registrations" role="tab" aria-controls="registrations" aria-selected="false">Registrations</a>
            </li>
        </ul>
        <div class="tab-content space-sm">
            @can('update', $user)
            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="Name">{{ $user->name}}</x-detail-row>
                    <x-detail-row label="Email address">{{$user->email}}</x-detail-row>
                    <x-detail-row label="Phone number">{{$user->phone_number}}</x-detail-row>
                    <x-detail-row label="First name">{{ $user->first_name}}</x-detail-row>
                    <x-detail-row label="Preposition">{{ $user->preposition}}</x-detail-row>
                    <x-detail-row label="Last name">{{ $user->last_name}}</x-detail-row>
                    <x-detail-row label="Address">{{$user->street . " " . $user->house_number}}</x-detail-row>
                    <x-detail-row label="City">{{$user->city}}</x-detail-row>
                    <x-detail-row label="Postal code">{{$user->zipcode}}</x-detail-row>
                    <tr>
                        <td>Country</td>
                        <td>
                            @isset($user->country)
                            {{ $countries[$user->country] ?? $user->country }}
                            @endisset
                        </td>
                    </tr>
                    <x-detail-row label="Alternative phone number">{{$user->phone_number_alt}}</x-detail-row>
                    <x-detail-row label="Birthdate">{{ $user->birthday?->format('d-m-Y') }}</x-detail-row>
                    <tr>
                        <td>Member type</td>
                        <td>
                            {{ $user->member_type?->label() }}
                        </td>
                    </tr>
                    <x-detail-row label="Remarks">{{$user->remark}}</x-detail-row>
                    <x-detail-row label="Created at"><x-local-time :value="$user->created_at" format="d-m-Y" /></x-detail-row>
                    <x-detail-row label="Updated at"><x-local-time :value="$user->updated_at" format="d-m-Y" /></x-detail-row>
                    <x-detail-row label="ID">{{ $user->id}}</x-detail-row>
                    @isset($user->membership_ended_on)
                    <tr>
                        <td>Membership ended on</td>
                        <td>{{ $user->membership_ended_on->format('d-m-Y') }}</td>
                    </tr>
                    @endisset
                </table>
            </div>
            <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="IBAN">{{$user->iban}}</x-detail-row>
                    <x-detail-row label="BIC">{{$user->bic}}</x-detail-row>
                    <x-detail-row label="Direct debit">{{($user->direct_debit)? 'Yes' : 'No'}}</x-detail-row>
                </table>
            </div>
            <div class="tab-pane fade" id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="Emergency address street">{{$user->emergency_street}}</x-detail-row>
                    <x-detail-row label="Emergency address house number">{{$user->emergency_house_number}}</x-detail-row>
                    <x-detail-row label="Emergency address postal code">{{$user->emergency_zipcode}}</x-detail-row>
                    <x-detail-row label="Emergency address city">{{$user->emergency_city}}</x-detail-row>
                    <x-detail-row label="Emergency address country">{{ $countries[$user->emergency_country] ?? $user->emergency_country }}</x-detail-row>
                    <x-detail-row label="Emergency phone number">{{$user->emergency_number}}</x-detail-row>
                </table>
            </div>
            <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                <table class="table table-striped" style="width:100%">
                    @foreach($user->roles as $role)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{$loop->count}}">Roles</td>
                            @endif
                            <td>{{$role->name}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @endcan
            <div @class(['tab-pane fade', 'show active' => auth()->user()->cannot('update', $user)]) id="certificates" role="tabpanel" aria-labelledby="certificates-tab">
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
            </div>
            <div class="tab-pane fade" id="registrations" role="tabpanel" aria-labelledby="registrations-tab">
                <x-data-table datatable :data-order="json_encode([[1, 'asc']])" :columns="['Name', 'Start date', 'Action']">
                    @foreach ($user->registrations as $registration)
                        <tr>
                            <td>{{ $registration->event->title }}</td>
                            <td data-order="{{ $registration->event->start_date->timestamp }}">{{ $registration->event->start_date->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.events.registrations.show', [$registration->event_id, $registration]) }}"><span title="Show entered information" class="ion-eye" aria-hidden="true"></span></a>
                                <form class="d-inline" method="post" action="{{ route('admin.events.registrations.destroy', [$registration->event_id, $registration]) }}" onsubmit="return confirm('Are you sure you want to remove your registration for this event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0"><span title="Remove registration" class="ion-trash-a" aria-hidden="true"></span></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </div>
        </div>
    </x-card>
@endsection
