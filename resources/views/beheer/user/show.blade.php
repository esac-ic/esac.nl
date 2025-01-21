@extends('layouts.beheer')

@section('title')
{{'Info of ' . $user->getName()}}
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'View user'}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
            @if($user->isPendingMember() and \Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                {{ html()->form('PATCH', '/users/'.$user->id . '/approveAsPendingMember')->open() }}
                <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> {{'Approve as member'}}</button>
                {{ html()->form()->close() }}
                
                {{ html()->form('PATCH', '/users/'.$user->id . '/removeAsPendingMember')->open() }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove as pending'}}</button>
                {{ html()->form()->close() }}
            @else
                @if($user->isOldMember() and \Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                    {{ html()->form('PATCH', '/users/'.$user->id . '/makeActiveMember')->open() }}
                    <button type="submit" class="btn btn-success"><em class="ion-plus"></em> {{'Make active member'}}</button>
                    {{ html()->form()->close() }}
                @endif
                <a href="{{url('/users/'.$user->id . '/edit' )}}" class="btn btn-primary">
                    <span title="{{'Edit'}}" class="ion-edit" aria-hidden="true"></span>
                    {{'Edit'}}
                </a>
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                    <a href="{{url('/users/'.$user->id . '/addCertificate' )}}" class="btn btn-primary">
                        <span title="{{'Add certificate'}}" class="ion-plus" aria-hidden="true"></span>
                        {{'Add certificate'}}
                    </a>
                @endif
                @if(app('request')->input('back') != "false")
                    <a href="{{url('/users/')}}" class="btn btn-primary">
                        <span title="{{'Back'}}" class="ion-android-arrow-back" aria-hidden="true"></span>
                        {{'Back'}}
                    </a>
                @endif
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                    @if($user->isActiveMember())
                        {{ html()->form('PATCH', '/users/'.$user->id . '/removeAsActiveMember')->open() }}
                        <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove as active member'}}</button>
                        {{ html()->form()->close() }}
                    @endif
                @endif
            @endif
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Info of ' . $user->getName()}}</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab1-content" role="tab" aria-controls="general" aria-selected="true">{{'Personal'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2" data-toggle="tab" href="#tab2-content" role="tab" aria-controls="billing" aria-selected="false">{{'Financial'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#tab3-content" role="tab" aria-controls="security" aria-selected="false">{{'Emergency info'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#rols" role="tab" aria-controls="security" aria-selected="false">{{'Roles'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#certifications" role="tab" aria-controls="security" aria-selected="false">{{'Certificates' }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#registrations" role="tab" aria-controls="security" aria-selected="false">{{'Registrations' }}</a>
                </li>
            </ul>
            @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')) || \Illuminate\Support\Facades\Auth::user()->id === $user->id)
            <div class="tab-content space-sm">
                <div class="tab-pane fade show active" id="tab1-content" role="tabpanel" aria-labelledby="tab1-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{'Name'}}</td>
                            <td>{{ $user->getName()}}</td>
                        </tr>
                        <tr>
                            <td>{{'Email address'}}</td>
                            <td>{{$user->email}}</td>
                        </tr>
                        <tr>
                            <td>{{'Phone number'}}</td>
                            <td>{{$user->phonenumber}}</td>
                        </tr>
                        <tr>
                            <td>{{'First name'}}</td>
                            <td>{{ $user->firstname}}</td>
                        </tr>
                        <tr>
                            <td>{{'Preposition'}}</td>
                            <td>{{ $user->preposition}}</td>
                        </tr>
                        <tr>
                            <td>{{'Last name'}}</td>
                            <td>{{ $user->lastname}}</td>
                        </tr>
                        <tr>
                            <td>{{'Address'}}</td>
                            <td>{{$user->street . " " . $user->houseNumber}}</td>
                        </tr>
                        <tr>
                            <td>{{'City'}}</td>
                            <td>{{$user->city}}</td>
                        </tr>
                        <tr>
                            <td>{{'Postal code'}}</td>
                            <td>{{$user->zipcode}}</td>
                        </tr>
                        <tr>
                            <td>{{'Country'}}</td>
                            <td>
                                @isset($user->country)
                                {{trans('countries.' . $user->country)}}
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td>{{'Alternative phone number'}}</td>
                            <td>{{$user->phonenumber_alt}}</td>
                        </tr>
                        <tr>
                            <td>{{'Birthdate'}}</td>
                            <td>{{$user->birthDay}}</td>
                        </tr>
                        <tr>
                            <td>{{'Kind of member'}}</td>
                            <td>
                                @isset($user->kind_of_member)
                                {{trans('kind_of_member.' . $user->kind_of_member)}}
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td>{{'Remarks'}}</td>
                            <td>{{$user->remark}}</td>
                        </tr>
                        <tr>
                            <td>{{'User created at'}}</td>
                            <td>{{\Carbon\Carbon::parse($user->created_at)->format('d-m-Y')}}</td>
                        </tr>
                        <tr>
                            <td>{{'User updated at'}}</td>
                            <td>{{\Carbon\Carbon::parse($user->updated_at)->format('d-m-Y')}}</td>
                        </tr>
                        @isset($user->lid_af)
                        <tr>
                            <td>User removed as active member at</td>
                            <td>{{\Carbon\Carbon::parse($user->lid_af)->format('d-m-Y')}}</td>
                        </tr>
                        @endisset
                    </table>
                </div>
                <div class="tab-pane fade" id="tab2-content" role="tabpanel" aria-labelledby="tab2-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{'IBAN'}}</td>
                            <td>{{$user->IBAN}}</td>
                        </tr>
                        <tr>
                            <td>{{'BIC'}}</td>
                            <td>{{$user->BIC}}</td>
                        </tr>
                        <tr>
                            <td>{{'Accept Automatic Collection'}}</td>
                            <td>{{($user->incasso)? 'Yes' : 'No'}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab3-content" role="tabpanel" aria-labelledby="tab3-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{'Emergency address street'}}</td>
                            <td>{{$user->emergencystreet}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address house number'}}</td>
                            <td>{{$user->emergencyHouseNumber}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address postal code'}}</td>
                            <td>{{$user->emergencyzipcode}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address city'}}</td>
                            <td>{{$user->emergencycity}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address country'}}</td>
                            <td>{{trans('countries.' . $user->emergencycountry)}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency phone number'}}</td>
                            <td>{{$user->emergencyNumber}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="rols" role="tabpanel" aria-labelledby="tab3-content">
                    <table class="table table-striped" style="width:100%">
                        @if(count($user->roles) > 0)
                            <tr>
                                <td rowspan="{{count($user->roles)}}">{{'Roles'}}</td>
                                <td>{{$user->roles[0]->name}}</td>
                            </tr>
                            @for($i=1; $i<count($user->roles);$i++)
                                <tr>
                                    <td>{{$user->roles[$i]->name}}</td>
                                </tr>
                            @endfor
                        @endif
                    </table>
                </div>
                @endif
                <div class="tab-pane fade" id="certifications" role="tabpanel" aria-labelledby="tab3-content">
                    <table class="table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <td>
                                <strong>{{'Name'}}</strong>
                            </td>
                            <td>
                                <strong>{{'Abbreviation'}}</strong>
                            </td>
                            <td>
                                <strong>{{'Management'}}</strong>
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
                                    <a href="{{url('/users/' . $user->id . '/addCertificate/' . $certificate->id)}}"><span title="Edit Certificate" class="ion-edit" aria-hidden="true"></span></a>
                                    <a href="{{url('/users/' . $user->id . '/addCertificate/' . $certificate->id)}}" onclick="event.preventDefault(); document.getElementById('delete-usercertificate-{{$certificate->id}}').submit();">
                                        <span title="Remove Certificate" class="ion-trash-a" aria-hidden="true"></span>
                                    </a>
                                    {{ html()->form('DELETE', '/users/' . $user->id . '/addCertificate/' . $certificate->id)->attributes(['id' => "delete-usercertificate-$certificate->id"])->open() }}
                                    {{ html()->form()->close() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="registrations" role="tabpanel" aria-labelledby="registrations">
                    <table class="table table-striped" style="width:100%" id="registrations_table">
                        <thead>
                        <tr>
                            <td>
                                <strong>{{'Name'}}</strong>
                            </td>
                            <td>
                                <strong>{{'Start date'}}</strong>
                            </td>
                            <td>
                                <strong>{{'Action'}}</strong>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{mix("js/vendor/datatables.js")}}"></script>
    <script>
        $('#registrations_table').DataTable({
            "order": [[ 1, "asc" ]],
            "ajax": {
                'url' : '{{url('api/user/registrations?user_id=' . $user->id)}}',
                "dataSrc": ""
            },
            "columns": [
                { "data": "name" },
                { "data": "startDate"},
                { "data": "actions"},
            ]
        });
        $(document).on('click','#delete_button',function(){
            if(confirm("{{'Are you sure you want do remove your registration for this event?'}}")){
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
