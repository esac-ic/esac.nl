@props(['user', 'details' => true, 'registrationsTab' => true, 'certificates', 'registrations'])
{{-- A member's details in tabs; without details, only the certificates and (with the registrations tab) the registrations. --}}
<ul class="nav nav-tabs" id="member-tabs" role="tablist">
    @if($details)
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
    @endif
    <li class="nav-item">
        <a @class(['nav-link', 'active' => ! $details]) id="certificates-tab" data-toggle="tab" href="#certificates" role="tab" aria-controls="certificates" aria-selected="false">Certificates</a>
    </li>
    @if($registrationsTab)
    <li class="nav-item">
        <a class="nav-link" id="registrations-tab" data-toggle="tab" href="#registrations" role="tab" aria-controls="registrations" aria-selected="false">Registrations</a>
    </li>
    @endif
</ul>
<div class="tab-content space-sm">
    @if($details)
    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
        <table class="table table-striped" style="width:100%">
            <x-detail-row label="Name">{{ $user->name}}</x-detail-row>
            <x-detail-row label="Email address">{{$user->email}}</x-detail-row>
            <x-detail-row label="Phone number">{{$user->phone_number}}</x-detail-row>
            <x-detail-row label="First name">{{ $user->first_name}}</x-detail-row>
            <x-detail-row label="Preposition">{{ $user->preposition}}</x-detail-row>
            <x-detail-row label="Last name">{{ $user->last_name}}</x-detail-row>
            <x-detail-row label="Address">{{ $user->address }}</x-detail-row>
            <x-detail-row label="City">{{$user->city}}</x-detail-row>
            <x-detail-row label="Postal code">{{$user->zipcode}}</x-detail-row>
            <x-detail-row label="Country">{{ $countries[$user->country] ?? $user->country }}</x-detail-row>
            <x-detail-row label="Alternative phone number">{{$user->phone_number_alt}}</x-detail-row>
            <x-detail-row label="Birthdate">{{ $user->birthday?->format('d-m-Y') }}</x-detail-row>
            <x-detail-row label="Member type">{{ $user->member_type?->label() }}</x-detail-row>
            <x-detail-row label="Remarks">{{$user->remark}}</x-detail-row>
            <x-detail-row label="Created at"><x-local-time :value="$user->created_at" format="d-m-Y" /></x-detail-row>
            <x-detail-row label="Updated at"><x-local-time :value="$user->updated_at" format="d-m-Y" /></x-detail-row>
            <x-detail-row label="ID">{{ $user->id}}</x-detail-row>
            @isset($user->membership_ended_on)
            <x-detail-row label="Membership ended on">{{ $user->membership_ended_on->format('d-m-Y') }}</x-detail-row>
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
                    <td>{{ $role->label() }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif
    <div @class(['tab-pane fade', 'show active' => ! $details]) id="certificates" role="tabpanel" aria-labelledby="certificates-tab">
        {{ $certificates }}
    </div>
    @if($registrationsTab)
    <div class="tab-pane fade" id="registrations" role="tabpanel" aria-labelledby="registrations-tab">
        {{ $registrations }}
    </div>
    @endif
</div>
