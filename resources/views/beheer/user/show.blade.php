@extends('layouts.beheer')

@section('title')
{{trans('user.info') . $user->getName()}}
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
            <h1>{{trans("user.view")}}</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
            @if($user->isPendingMember() and \Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                        {{ Form::open(array('url' => '/users/'.$user->id . '/approveAsPendingMember', 'method' => 'patch')) }}
                        <button type="submit" class="btn btn-success"><em class="ion-checkmark"></em> {{trans("user.approveAsPendingMember")}}</button>
                        {{ Form::close() }}
                        {{ Form::open(array('url' => '/users/'.$user->id . '/removeAsPendingMember', 'method' => 'patch')) }}
                        <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans("user.removeAsPendingMember")}}</button>
                        {{ Form::close() }}
            @else
                <a href="{{url('/users/'.$user->id . '/edit' )}}" class="btn btn-primary">
                    <span title="{{trans("menu.edit")}}" class="ion-edit" aria-hidden="true"></span>
                    {{trans("menu.edit")}}
                </a>
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator'),Config::get('constants.Certificate_administrator')))
                    <a href="{{url('/users/'.$user->id . '/addCertificate' )}}" class="btn btn-primary">
                        <span title="{{trans("user.addCertificate")}}" class="ion-plus" aria-hidden="true"></span>
                        {{trans("user.addCertificate")}}
                    </a>
                @endif
                @if(app('request')->input('back') != "false")
                    <a href="{{url('/users/')}}" class="btn btn-primary">
                        <span title="{{trans("menu.back")}}" class="ion-android-arrow-back" aria-hidden="true"></span>
                        {{trans("menu.back")}}
                    </a>
                @endif
                @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')))
                    @if($user->isActiveMember())
                        {{ Form::open(array('url' => '/users/'.$user->id . '/removeAsActiveMember', 'method' => 'patch')) }}
                        <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{trans("user.removeAsActiveMember")}}</button>
                        {{ Form::close() }}
                    @endif
                @endif
            @endif
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('user.info') . $user->getName()}}</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#tab1-content" role="tab" aria-controls="general" aria-selected="true">{{trans('user.personal')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab2" data-toggle="tab" href="#tab2-content" role="tab" aria-controls="billing" aria-selected="false">{{trans('user.financial')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#tab3-content" role="tab" aria-controls="security" aria-selected="false">{{trans('user.emergencyInfo')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#rols" role="tab" aria-controls="security" aria-selected="false">{{trans('user.rols')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#certifications" role="tab" aria-controls="security" aria-selected="false">{{trans('certificate.certificates') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab3" data-toggle="tab" href="#registrations" role="tab" aria-controls="security" aria-selected="false">{{trans('user.registrations') }}</a>
                </li>
            </ul>
            @if(\Illuminate\Support\Facades\Auth::user()->hasRole(Config::get('constants.Administrator')) || \Illuminate\Support\Facades\Auth::user()->id === $user->id)
            <div class="tab-content space-sm">
                <div class="tab-pane fade show active" id="tab1-content" role="tabpanel" aria-labelledby="tab1-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{trans('user.name')}}</td>
                            <td>{{ $user->getName()}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.email')}}</td>
                            <td>{{$user->email}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.phonenumber')}}</td>
                            <td>{{$user->phonenumber}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.firstname')}}</td>
                            <td>{{ $user->firstname}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.preposition')}}</td>
                            <td>{{ $user->preposition}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.lastname')}}</td>
                            <td>{{ $user->lastname}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.adress')}}</td>
                            <td>{{$user->street . " " . $user->houseNumber}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.city')}}</td>
                            <td>{{$user->city}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.zipcode')}}</td>
                            <td>{{$user->zipcode}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.country')}}</td>
                            <td>
                                @isset($user->country)
                                {{trans('countries.' . $user->country)}}
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans('user.phonenumber_alt')}}</td>
                            <td>{{$user->phonenumber_alt}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.birthDay')}}</td>
                            <td>{{$user->birthDay}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.gender')}}</td>
                            <td>{{$user->gender}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.kind_of_member')}}</td>
                            <td>
                                @isset($user->kind_of_member)
                                {{trans('kind_of_member.' . $user->kind_of_member)}}
                                @endisset
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans('user.remark')}}</td>
                            <td>{{$user->remark}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.created_at')}}</td>
                            <td>{{trans(\Carbon\Carbon::parse($user->created_at)->format('d-m-Y'))}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.updated_at')}}</td>
                            <td>{{trans(\Carbon\Carbon::parse($user->updated_at)->format('d-m-Y'))}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab2-content" role="tabpanel" aria-labelledby="tab2-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{trans('user.IBAN')}}</td>
                            <td>{{$user->IBAN}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.BIC')}}</td>
                            <td>{{$user->BIC}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.incasso')}}</td>
                            <td>{{($user->incasso)? trans('menu.yes') : trans('menu.no')}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab3-content" role="tabpanel" aria-labelledby="tab3-content">
                    <table class="table table-striped" style="width:100%">
                        <tr>
                            <td>{{trans('user.emergencystreet')}}</td>
                            <td>{{$user->emergencystreet}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.emergencyHouseNumber')}}</td>
                            <td>{{$user->emergencyHouseNumber}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.emergencyzipcode')}}</td>
                            <td>{{$user->emergencyzipcode}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.emergencycity')}}</td>
                            <td>{{$user->emergencycity}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.emergencycountry')}}</td>
                            <td>{{trans('countries.' . $user->emergencycountry)}}</td>
                        </tr>
                        <tr>
                            <td>{{trans('user.emergencyNumber')}}</td>
                            <td>{{$user->emergencyNumber}}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane fade" id="rols" role="tabpanel" aria-labelledby="tab3-content">
                    <table class="table table-striped" style="width:100%">
                        @if(count($user->roles) > 0)
                            <tr>
                                <td rowspan="{{count($user->roles)}}">{{trans('user.rols')}}</td>
                                <td>{{$user->roles[0]->text->text()}}</td>
                            </tr>
                            @for($i=1; $i<count($user->roles);$i++)
                                <tr>
                                    <td>{{$user->roles[$i]->text->text()}}</td>
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
                                <strong>{{trans('certificate.name')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('certificate.abbreviation')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('certificate.duration')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('certificate.validuntil')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('menu.beheer')}}</strong>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->certificates as $certificate)
                            <tr>
                                <td>
                                    {{$certificate->certificateName->text()}}
                                </td>
                                <td>
                                    {{$certificate->abbreviation}}
                                </td>
                                <td>
                                    {{$certificate->duration === null ? "" : $certificate->duration}}
                                </td>
                                <td>
                                    {{$certificate->duration === null ? "" : \Carbon\Carbon::parse($certificate->pivot->startDate)->addMonths($certificate->duration)->format('d-m-Y') }}
                                </td>
                                <td>
                                    <a href="{{url('/users/' . $user->id . '/addCertificate/' . $certificate->id)}}"><span title="{{trans('certificate.editUserCertificate')}}" class="ion-edit" aria-hidden="true"></span></a>
                                    <a href="{{url('/users/' . $user->id . '/addCertificate/' . $certificate->id)}}" onclick="event.preventDefault(); document.getElementById('delete-usercertificate-{{$certificate->id}}').submit();">
                                        <span title="{{trans('certificate.deleteUserCertificate')}}" class="ion-trash-a" aria-hidden="true"></span>
                                    </a>
                                    {{ Form::open(array('url' => '/users/' . $user->id . '/addCertificate/' . $certificate->id, 'method' => 'delete', "id"   => "delete-usercertificate-$certificate->id")) }}
                                    {{ Form::close() }}
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
                                <strong>{{trans('user.name')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('AgendaItems.startDate')}}</strong>
                            </td>
                            <td>
                                <strong>{{trans('menu.action')}}</strong>
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
    <script src="{{mix("js/vendor/datatables.js")}}" type="text/javascript"></script>
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
            if(confirm("{{trans('inschrijven.deleteRegistrationConfurm')}}")){
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