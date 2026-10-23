@extends('layouts.admin')

@section('title')
{{$mailList->name}}
@endsection

@section('content')
    <x-page-header :title="$mailList->name">
        <x-button-link href="#" icon="plus" data-toggle="modal" data-target="#add-members-modal">Add members</x-button-link>
        <x-button-link :href="route('admin.mail-lists.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.mail-lists.destroy', $mailList->id)" confirm="Are you sure you want to delete the mail list?" />
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
                        <x-delete-button icon :action="route('admin.mail-lists.members.destroy', [$mailList->id, $member->address])" :confirm="'Are you sure you want to remove this member from '.$mailList->address.'?'">Remove member</x-delete-button>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
    @include('admin.mail-lists.add-members-modal')
@endsection

