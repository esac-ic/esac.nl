@extends('layouts.admin')

@section('title')
{{$mailList->name}}
@endsection

@section('content')
    <x-page-header :title="$mailList->name">
        <a href="#" id="addMember" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
            <em class="ion-plus"></em> Add members
        </a>
        <a href="{{route('admin.mail-lists.index')}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em>  Back
        </a>
        <form method="post"
              action="{{ route('admin.mail-lists.destroy', $mailList->id) }}"
              onsubmit="return confirm('Are you sure you want to delete the maillist?');"
        >
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> Remove</button>
        </form>
    </x-page-header>
    <x-card title="Mailing list" class="mt-4">
        <table class="table table-striped dt-responsive nowrap">
            <x-detail-row label="Name">{{$mailList->name}}</x-detail-row>
            <x-detail-row label="Email">{{$mailList->address}}</x-detail-row>
            <x-detail-row label="Members count">{{$mailList->members_count}}</x-detail-row>
        </table>
        <h4> Members</h4>
        <table id="maillistMembers" class="table table-striped dt-responsive nowrap">
            <thead>
                <td><strong>Email</strong></td>
                <td><strong>Name</strong></td>
                <td><strong>Management</strong></td>
            </thead>
            <tbody>
            @foreach($mailList->members as $member)
                <tr>
                    <td>{{$member->address}}</td>
                    <td>{{$member->name}}</td>
                    <td>
                        <a href="#"
                           id="deleteMember"
                           data-mailList-id="{{$mailList->id}}"
                           data-member-email="{{$member->address}}"
                        >
                            <em class="ion-trash-a"></em>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>
    @include('admin.mail-lists.adduser')
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
    <script type="module">
        $(document).ready(function() {
            $('#maillistMembers').dataTable();
        });

        $(document).on('click', '#addMember', function() {
        });

        $(document).on('click', '#deleteMember', function() {
            const mailListId = $(this).attr('data-mailList-id');
            const memberEmail = $(this).attr('data-member-email');
            
            if (confirm('Are you sure you want to remove this member from {{$mailList->address}}?'))
            {
                $.ajax({
                    url: '{{ route('admin.mail-lists.members.destroy', ['__list__', '__member__']) }}'.replace('__list__', mailListId).replace('__member__', memberEmail),
                    data: {
                        _token: window.Laravel.csrfToken
                    },
                    type: 'DELETE',
                    success: function() {
                        window.location.reload();
                    }
                });
            }
        });
    </script>
@endpush
