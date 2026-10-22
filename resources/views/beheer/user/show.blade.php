@extends('layouts.beheer')

@section('title')
{{'Info of ' . $user->getName()}}
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix("css/vendor/datatables.css")}}">
@endpush

@section('content')
    @include('includes.flash')

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'View user'}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
            @if($user->isPendingMember() && auth()->user()->can('manage-members'))
                {{ html()->form('POST', route('users.approveAsPendingMember', $user))->open() }}
                @method('PATCH')
                <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> {{'Approve as member'}}</button>
                {{ html()->form()->close() }}
                
                <form method="post"
                      action="{{ route("users.removeAsPendingMember", $user) }}"
                      onsubmit="return confirm('Are you sure you want to remove {{$user->getName()}} as a pending member?');"
                >
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove as pending'}}</button>
                </form>
            @else
                @can('manage-members')
                    @if($user->isOldMember())
                        {{ html()->form('POST', route('users.makeActiveMember', $user))->open() }}
                        @method('PATCH')
                            <button type="submit" class="btn btn-success"><em class="ion-plus"></em> {{'Make active member'}}</button>
                        {{ html()->form()->close() }}
                    @endif
                @endcan
                <a href="{{route('users.edit', $user)}}" class="btn btn-primary">
                    <span title="{{'Edit'}}" class="ion-edit" aria-hidden="true"></span>
                    {{'Edit'}}
                </a>
                @can('manage-certificates')
                    <a href="{{ route('users.certificates.create', $user) }}" class="btn btn-primary">
                        <span title="{{'Add certificate'}}" class="ion-plus" aria-hidden="true"></span>
                        {{'Add certificate'}}
                    </a>
                @endcan
                @if(request('back') != "false")
                    <a href="{{route('users.index')}}" class="btn btn-primary">
                        <span title="{{'Back'}}" class="ion-android-arrow-back" aria-hidden="true"></span>
                        {{'Back'}}
                    </a>
                @endif
                @can('manage-members')
                    @if($user->isActiveMember())
                        {{ html()->form('POST', route('users.removeAsActiveMember', $user))->open() }}
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove as active member'}}</button>
                        {{ html()->form()->close() }}
                    @endif
                @endcan
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
                @can('update', $user)
                <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab1-content" role="tab" aria-controls="tab1-content" aria-selected="true">{{'Personal'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2" data-toggle="tab" href="#tab2-content" role="tab" aria-controls="tab2-content" aria-selected="false">{{'Financial'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#tab3-content" role="tab" aria-controls="tab3-content" aria-selected="false">{{'Emergency info'}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab4" data-toggle="tab" href="#rols" role="tab" aria-controls="rols" aria-selected="false">{{'Roles'}}</a>
                </li>
                @endcan
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => auth()->user()->cannot('update', $user)]) id="tab5" data-toggle="tab" href="#certifications" role="tab" aria-controls="certifications" aria-selected="false">{{'Certificates' }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab6" data-toggle="tab" href="#registrations" role="tab" aria-controls="registrations" aria-selected="false">{{'Registrations' }}</a>
                </li>
            </ul>
            <div class="tab-content space-sm">
                @can('update', $user)
                <div class="tab-pane fade show active" id="tab1-content" role="tabpanel" aria-labelledby="tab1">
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
                            <td>{{$user->phone_number}}</td>
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
                            <td>{{$user->street . " " . $user->house_number}}</td>
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
                            <td>{{$user->phone_number_alt}}</td>
                        </tr>
                        <tr>
                            <td>{{'Birthdate'}}</td>
                            <td>{{$user->birthday}}</td>
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
                            <td>{{ $user->created_at?->tz(config('app.display_timezone'))->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td>{{'User updated at'}}</td>
                            <td>{{ $user->updated_at?->tz(config('app.display_timezone'))->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td>{{'ID'}}</td>
                            <td>{{ $user->id}}</td>
                        </tr>
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
                        <tr>
                            <td>{{'IBAN'}}</td>
                            <td>{{$user->iban}}</td>
                        </tr>
                        <tr>
                            <td>{{'BIC'}}</td>
                            <td>{{$user->bic}}</td>
                        </tr>
                        <tr>
                            <td>{{'Accept Automatic Collection'}}</td>
                            <td>{{($user->incasso)? 'Yes' : 'No'}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab3-content" role="tabpanel" aria-labelledby="tab3">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{'Emergency address street'}}</td>
                            <td>{{$user->emergency_street}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address house number'}}</td>
                            <td>{{$user->emergency_house_number}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address postal code'}}</td>
                            <td>{{$user->emergency_zipcode}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address city'}}</td>
                            <td>{{$user->emergency_city}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency address country'}}</td>
                            <td>{{trans('countries.' . $user->emergency_country)}}</td>
                        </tr>
                        <tr>
                            <td>{{'Emergency phone number'}}</td>
                            <td>{{$user->emergency_number}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="rols" role="tabpanel" aria-labelledby="tab4">
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
                @endcan
                <div @class(['tab-pane fade', 'show active' => auth()->user()->cannot('update', $user)]) id="certifications" role="tabpanel" aria-labelledby="tab5">
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
                                    <a href="{{ route('users.certificates.edit', [$user, $certificate]) }}"><span title="Edit Certificate" class="ion-edit" aria-hidden="true"></span></a>
                                    <a href="{{ route('users.certificates.destroy', [$user, $certificate]) }}" onclick="event.preventDefault(); document.getElementById('delete-usercertificate-{{$certificate->id}}').submit();">
                                        <span title="Remove Certificate" class="ion-trash-a" aria-hidden="true"></span>
                                    </a>
                                    {{ html()->form('POST', route('users.certificates.destroy', [$user, $certificate]))->id("delete-usercertificate-$certificate->id")->open() }}
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
                'url' : '{{ route('api.user.registrations', ['user_id' => $user->id]) }}',
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
