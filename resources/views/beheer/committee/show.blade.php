@extends('layouts.beheer')

@section('title')
{{$committee->name}}
@endsection

@section('content')
    @if ($errors->any())
        <p>Hi</p>
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
                <a href="#" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                    <em class="ion-plus"></em> {{'Add members'}}
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
                        {{'Chairperson'}}
                    </td>
                    <td @class(['text-muted' => empty($committee->chair())])>
                        {{$committee->chair() ?: 'This committee has no chairperson.'}}
                    </td>
                </tr>
            </table>
            <h4> {{'Members'}}</h4>
            <table id="committeeMembers" class="table table-striped dt-responsive nowrap" style="width: 100%">
                <thead>
                    <td><strong>{{'Email'}}</strong></td>
                    <td><strong>{{'Name'}}</strong></td>
                    <td><strong>{{'Management'}}</strong></td>
                </thead>
                <tbody>
                @foreach($committee->members as $member)
                    <tr>
                        <td>{{$member->email}}</td>
                        <td>{{$member->getName()}}</td>
                        <td>
                            todo
{{--                            <a href="#" id="deleteMember" data-mailList-id="{{$mailList->getId()}}" data-member-email="{{$member->getAddress()}}"><em class="ion-trash-a"></em></a>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('beheer.committee.adduser')
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
            $('#committeeMembers').DataTable({
                columnDefs: [
                    {width: '40%', target: 0},
                    {width: '40%', target: 1},
                    {width: '20%', target: 2},
                ]
            });
        });

        // $(document).on('click', '#addMember', function() {
        // });
        //
        // $(document).on('click', '#deleteMember', function() {
        //     const mailListId = $(this).attr('data-mailList-id');
        //     const memberEmail = $(this).attr('data-member-email');
        //
        //     $.ajax({
        //         url: '/mailList/' + mailListId + '/member/' + memberEmail,
        //         data: {
        //             _token: window.Laravel.csrfToken
        //         },
        //         type: 'DELETE',
        //         success: function() {
        //             window.location.reload();
        //         }
        //     });
        // });
    </script>
@endpush
