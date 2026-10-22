@extends('layouts.beheer')

@section('title')
Info of {{ $user->getName() }}
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@section('content')
    <x-page-header title="View user">
        @if($user->isPendingMember() && auth()->user()->can('manage-members'))
            {{ html()->form('POST', route('beheer.pending-users.approve', $user))->open() }}
            @method('PATCH')
            <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> Approve as member</button>
            {{ html()->form()->close() }}
        
            <form method="post"
                  action="{{ route("beheer.pending-users.destroy", $user) }}"
                  onsubmit="return confirm('Are you sure you want to remove {{$user->getName()}} as a pending member?');"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> Remove as pending</button>
            </form>
        @else
            @can('manage-members')
                @if($user->isOldMember())
                    {{ html()->form('POST', route('beheer.users.membership.store', $user))->open() }}
                        <button type="submit" class="btn btn-success"><em class="ion-plus"></em> Make active member</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
            <x-button-link :href="route('beheer.users.edit', $user)" icon="edit">Edit</x-button-link>
            @can('manage-certificates')
                <x-button-link :href="route('beheer.users.certificates.create', $user)" icon="plus">Add certificate</x-button-link>
            @endcan
            @can('view-members')
                <x-button-link :href="route('beheer.users.index')" icon="android-arrow-back">Back</x-button-link>
            @endcan
            @can('manage-members')
                @if($user->isActiveMember())
                    {{ html()->form('POST', route('beheer.users.membership.destroy', $user))->open() }}
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> Remove as active member</button>
                    {{ html()->form()->close() }}
                @endif
            @endcan
        @endif
    </x-page-header>
    <x-card :title="'Info of ' . $user->getName()" class="mt-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @can('update', $user)
            <li class="nav-item">
                <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab1-content" role="tab" aria-controls="tab1-content" aria-selected="true">Personal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2" data-toggle="tab" href="#tab2-content" role="tab" aria-controls="tab2-content" aria-selected="false">Financial</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab3" data-toggle="tab" href="#tab3-content" role="tab" aria-controls="tab3-content" aria-selected="false">Emergency info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab4" data-toggle="tab" href="#rols" role="tab" aria-controls="rols" aria-selected="false">Roles</a>
            </li>
            @endcan
            <li class="nav-item">
                <a @class(['nav-link', 'active' => auth()->user()->cannot('update', $user)]) id="tab5" data-toggle="tab" href="#certifications" role="tab" aria-controls="certifications" aria-selected="false">Certificates</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab6" data-toggle="tab" href="#registrations" role="tab" aria-controls="registrations" aria-selected="false">Registrations</a>
            </li>
        </ul>
        <div class="tab-content space-sm">
            @can('update', $user)
            <div class="tab-pane fade show active" id="tab1-content" role="tabpanel" aria-labelledby="tab1">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="Name">{{ $user->getName()}}</x-detail-row>
                    <x-detail-row label="Email address">{{$user->email}}</x-detail-row>
                    <x-detail-row label="Phone number">{{$user->phone_number}}</x-detail-row>
                    <x-detail-row label="First name">{{ $user->firstname}}</x-detail-row>
                    <x-detail-row label="Preposition">{{ $user->preposition}}</x-detail-row>
                    <x-detail-row label="Last name">{{ $user->lastname}}</x-detail-row>
                    <x-detail-row label="Address">{{$user->street . " " . $user->house_number}}</x-detail-row>
                    <x-detail-row label="City">{{$user->city}}</x-detail-row>
                    <x-detail-row label="Postal code">{{$user->zipcode}}</x-detail-row>
                    <tr>
                        <td>Country</td>
                        <td>
                            @isset($user->country)
                            {{trans('countries.' . $user->country)}}
                            @endisset
                        </td>
                    </tr>
                    <x-detail-row label="Alternative phone number">{{$user->phone_number_alt}}</x-detail-row>
                    <x-detail-row label="Birthdate">{{ $user->birthday?->format('d-m-Y') }}</x-detail-row>
                    <tr>
                        <td>Kind of member</td>
                        <td>
                            {{ $user->kind_of_member?->label() }}
                        </td>
                    </tr>
                    <x-detail-row label="Remarks">{{$user->remark}}</x-detail-row>
                    <x-detail-row label="User created at"><x-local-time :value="$user->created_at" format="d-m-Y" /></x-detail-row>
                    <x-detail-row label="User updated at"><x-local-time :value="$user->updated_at" format="d-m-Y" /></x-detail-row>
                    <x-detail-row label="ID">{{ $user->id}}</x-detail-row>
                    @isset($user->lid_af)
                    <tr>
                        <td>User removed as active member at</td>
                        <td>{{ $user->lid_af->format('d-m-Y') }}</td>
                    </tr>
                    @endisset
                </table>
            </div>
            <div class="tab-pane fade" id="tab2-content" role="tabpanel" aria-labelledby="tab2">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="IBAN">{{$user->iban}}</x-detail-row>
                    <x-detail-row label="BIC">{{$user->bic}}</x-detail-row>
                    <x-detail-row label="Accept Automatic Collection">{{($user->incasso)? 'Yes' : 'No'}}</x-detail-row>
                </table>
            </div>
            <div class="tab-pane fade" id="tab3-content" role="tabpanel" aria-labelledby="tab3">
                <table class="table table-striped" style="width:100%">
                    <x-detail-row label="Emergency address street">{{$user->emergency_street}}</x-detail-row>
                    <x-detail-row label="Emergency address house number">{{$user->emergency_house_number}}</x-detail-row>
                    <x-detail-row label="Emergency address postal code">{{$user->emergency_zipcode}}</x-detail-row>
                    <x-detail-row label="Emergency address city">{{$user->emergency_city}}</x-detail-row>
                    <x-detail-row label="Emergency address country">{{trans('countries.' . $user->emergency_country)}}</x-detail-row>
                    <x-detail-row label="Emergency phone number">{{$user->emergency_number}}</x-detail-row>
                </table>
            </div>
            <div class="tab-pane fade" id="rols" role="tabpanel" aria-labelledby="tab4">
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
            <div @class(['tab-pane fade', 'show active' => auth()->user()->cannot('update', $user)]) id="certifications" role="tabpanel" aria-labelledby="tab5">
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
                            <strong>Management</strong>
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
                                <a href="{{ route('beheer.users.certificates.edit', [$user, $certificate]) }}"><span title="Edit Certificate" class="ion-edit" aria-hidden="true"></span></a>
                                <a href="{{ route('beheer.users.certificates.destroy', [$user, $certificate]) }}" onclick="event.preventDefault(); document.getElementById('delete-usercertificate-{{$certificate->id}}').submit();">
                                    <span title="Remove Certificate" class="ion-trash-a" aria-hidden="true"></span>
                                </a>
                                {{ html()->form('POST', route('beheer.users.certificates.destroy', [$user, $certificate]))->id("delete-usercertificate-$certificate->id")->open() }}
                                @method('DELETE')
                                {{ html()->form()->close() }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="registrations" role="tabpanel" aria-labelledby="tab6">
                <table class="table table-striped" style="width:100%" id="registrations_table">
                    <thead>
                    <tr>
                        <td>
                            <strong>Name</strong>
                        </td>
                        <td>
                            <strong>Start date</strong>
                        </td>
                        <td>
                            <strong>Action</strong>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}"></script>
    <script>
        $('#registrations_table').DataTable({
            "order": [[ 1, "asc" ]],
            "ajax": {
                'url' : '{{ route('api.registrations.index', ['user_id' => $user->id]) }}',
                "dataSrc": ""
            },
            "columns": [
                { "data": "name" },
                { "data": "startDate"},
                { "data": "actions"},
            ]
        });
        $(document).on('click','#delete_button',function(){
            if(confirm("Are you sure you want do remove your registration for this event?")){
                var url = $(this).attr('data-url');
                $.ajax({
                    url: url,
                    beforeSend: function(request) {
                        request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    },
                    method: 'DELETE',
                    contentType: 'application/json',
                    success: function(result) {
                        window.location.reload();
                    },
                    error: function(request,msg,error) {
                        window.location.reload();
                    }
                });
            }

        });
    </script>
@endpush
