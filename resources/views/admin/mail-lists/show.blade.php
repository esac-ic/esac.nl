@extends('layouts.admin')

@section('title')
{{$mailList->name}}
@endsection

@section('content')
    <x-page-header :title="$mailList->name">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-members-modal">
            <em class="ion-plus"></em> Add members
        </a>
        <a href="{{route('admin.mail-lists.index')}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em>  Back
        </a>
        <form method="post"
              action="{{ route('admin.mail-lists.destroy', $mailList->id) }}"
              onsubmit="return confirm('Are you sure you want to delete the mail list?');"
        >
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-primary"><em class="ion-trash-a"></em> Remove</button>
        </form>
    </x-page-header>
    <x-card title="Mail list" class="mt-4">
        <table class="table table-striped dt-responsive nowrap">
            <x-detail-row label="Name">{{$mailList->name}}</x-detail-row>
            <x-detail-row label="Email">{{$mailList->address}}</x-detail-row>
            <x-detail-row label="Members count">{{$mailList->members_count}}</x-detail-row>
        </table>
        <h4> Members</h4>
        <table id="mail-list-members-table" class="table table-striped dt-responsive nowrap">
            <thead>
                <td><strong>Email</strong></td>
                <td><strong>Name</strong></td>
                <td><strong>Actions</strong></td>
            </thead>
            <tbody>
            @foreach($mailList->members as $member)
                <tr>
                    <td>{{$member->address}}</td>
                    <td>{{$member->name}}</td>
                    <td>
                        <a href="#"
                           class="js-remove-mail-list-member"
                           data-destroy-url="{{ route('admin.mail-lists.members.destroy', [$mailList->id, $member->address]) }}"
                        >
                            <em class="ion-trash-a"></em>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>
    @include('admin.mail-lists.add-members-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#mail-list-members-table').dataTable();
        });

        $(document).on('click', '.js-remove-mail-list-member', function() {
            if (confirm('Are you sure you want to remove this member from {{$mailList->address}}?'))
            {
                $.ajax({
                    url: $(this).attr('data-destroy-url'),
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
