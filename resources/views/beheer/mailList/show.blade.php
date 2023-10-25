@extends('layouts.beheer')

@section('title')
{{$mailList->getName()}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{$mailList->getName()}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="#" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <em class="ion-plus"></em> {{'Add members'}}
                </a>
                <a href="{{url('/mailList/')}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em>  {{'Back'}}
                </a>
                {{ Form::open(array('url' => 'mailList/' . $mailList->getId(), 'method' => 'delete')) }}
                    <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Mailing list'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped dt-responsive nowrap">
                <tr>
                    <td>
                        {{'Name'}}
                    </td>
                    <td>
                        {{$mailList->getName()}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Email'}}
                    </td>
                    <td>
                        {{$mailList->getAddress()}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Members count'}}
                    </td>
                    <td>
                        {{$mailList->getMembersCount()}}
                    </td>
                </tr>
            </table>
            <h4> {{'Members'}}</h4>
            <table id="maillistMembers" class="table table-striped dt-responsive nowrap">
                <thead>
                    <td><strong>{{'Email'}}</strong></td>
                    <td><strong>{{'Name'}}</strong></td>
                    <td><strong>{{'Management'}}</strong></td>
                </thead>
                <tbody>
                @foreach($mailList->getMembers() as $member)
                    <tr>
                        <td>{{$member->getAddress()}}</td>
                        <td>{{$member->getName()}}</td>
                        <td>
                            <a href="#" id="deleteMember" data-mailList-id="{{$mailList->getId()}}" data-member-email="{{$member->getAddress()}}"><em class="ion-trash-a"></em></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('beheer.mailList.adduser')
@endsection
@push('styles')
    <style>
        .marginbox {
            margin: 20px;
        }
    </style>
@endpush
@push('scripts')
    @yield('modal_javascript')
    <script>
        $(document).ready(function() {
            $('#maillistMembers').dataTable();
        });

        $(document).on('click', '#addMember', function() {
        });

        $(document).on('click', '#deleteMember', function() {
            const mailListId = $(this).attr('data-mailList-id');
            const memberEmail = $(this).attr('data-member-email');

            $.ajax({
                url: '/mailList/' + mailListId + '/member/' + memberEmail,
                data: {
                    _token: window.Laravel.csrfToken
                },
                type: 'DELETE',
                success: function() {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush
