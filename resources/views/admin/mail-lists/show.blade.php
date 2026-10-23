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
        <x-data-table datatable :columns="['Email', 'Name', 'Actions']">
            @foreach($mailList->members as $member)
                <tr>
                    <td>{{$member->address}}</td>
                    <td>{{$member->name}}</td>
                    <td>
                        <form method="post" action="{{ route('admin.mail-lists.members.destroy', [$mailList->id, $member->address]) }}" onsubmit="return confirm('Are you sure you want to remove this member from {{$mailList->address}}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0"><em class="ion-trash-a"></em></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
    @include('admin.mail-lists.add-members-modal')
@endsection

