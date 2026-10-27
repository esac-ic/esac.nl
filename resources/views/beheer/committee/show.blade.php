@extends('layouts.beheer')

@section('title')
    {{$committee->name}}
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$committee->name}}</h1>
        </div>
        
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="javascript:void(0);" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <em class="ion-plus"></em> {{'Add members'}}
                </a>
                <a href="javascript:void(0);" id="setChairPersons" class="btn btn-primary" data-toggle="modal" data-target="#setChairPersonsModal">
                    <em class="ion-person"></em> {{'Set chairperson(s)'}}
                </a>
                <a href="{{route("beheer.committees.index")}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
                {{--                <a href="{{url('/mailList/')}}" class="btn btn-primary">--}}
                {{--                    <em class="ion-android-arrow-back"></em>  {{'Back'}}--}}
                {{--                </a>--}}
                {{--                <form method="post" action="{{ url('mailList/' . $mailList->getId()) }}" onsubmit="return confirm('Are you sure you want to delete the maillist?');">--}}
                {{--                    @csrf--}}
                {{--                    @method('DELETE')--}}
                {{--                    <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> {{'Remove'}}</button>--}}
                {{--                </form>--}}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Committee'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped dt-responsive nowrap">
                <tr>
                    <td style="width: 30%">
                        {{'Name'}}
                    </td>
                    <td>
                        {{$committee->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Abbreviation'}}
                    </td>
                    <td>
                        {{$committee->abbreviation}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Email'}}
                    </td>
                    <td>
                        {{$committee->email}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Members count'}}
                    </td>
                    <td>
                        {{$committee->members()->count()}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{$committee->chairPersons()->count() <= 1 ? 'Chairperson':'Chairpersons'}}
                    </td>
                    <td @class(['text-muted' => $committee->chairPersons()->count() == 0])>
                        @if($committee->chairPersons()->count() > 0)
                            @foreach($committee->chairPersons() as $chair)
                                {{$chair->getName()}}
                                @if(!$loop->last)
                                    {{", "}}
                                @endif
                            @endforeach
                        @else
                            {{"This committee has no chairperson."}}
                        @endif
{{--                        {{ !empty($committee->chairPersons()) ? $committee->chairPersons()->pluck("firstname") : 'This committee has no chairperson.'}}--}}
                    </td>
                </tr>
            </table>
            <h4> {{'Members'}}</h4>
            <table id="committeeMembers" class="table table-striped dt-responsive nowrap" style="width: 100%">
                <thead>
                    <td><strong>{{'Name'}}</strong></td>
                    <td><strong>{{'Email'}}</strong></td>
                    <td><strong>{{'Management'}}</strong></td>
                </thead>
                <tbody>
                @foreach($committee->members as $member)
                    <tr>
                        <td>{{$member->getName()}}</td>
                        <td>{{$member->email}}</td>
                        <td>
                            <form method="post"
                                  action="{{ route('beheer.committees.removeMember', [$committee, $member]) }}">
                                @csrf
                                @method('DELETE')
                                <a href="javascript:void(0);" onclick="$(this).closest('form').submit()"><em
                                            class="ion-trash-a"></em></a>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('beheer.committee.adduser')
    
{{--    Set chair modal    --}}
{{--    TODO make this a seperate component? --}}
    <div class="modal fade" id="setChairPersonsModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="setCommitteeChairPersonsForm" method="post"
                      action="{{ route('beheer.committees.setChairPersons', $committee) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ "Modal title" }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered table-hover" id="committeeChairList"
                               style="width: 100%">
                            <thead>
                            <tr>
                                <td></td>
                                <td><strong>{{'Name'}}</strong></td>
                                <td><strong>{{'Email address'}}</strong></td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($committee->members as $member)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               value="{{$member->id}}"
                                               {{$committee->chairPersons()->contains($member) ? "checked": ""}}
                                               name="chair_persons[]"
                                        >
                                    </td>
                                    <td>
                                        {{$member->getName()}}
                                    </td>
                                    <td>
                                        {{$member->email}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary float-right" value="Set chairpersons"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @yield('modal_javascript')
    <script>
        $(document).ready(function () {
            $('#committeeMembers').DataTable({
                columnDefs: [
                    {width: '45%', target: 0},
                    {width: '45%', target: 1},
                    {width: '10%', target: 2},
                ]
            });
        });
    </script>
@endpush
