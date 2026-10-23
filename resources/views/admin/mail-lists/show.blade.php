@extends('layouts.admin')

@section('title')
{{$mailList->name}}
@endsection

@section('content')
    <x-page-header :title="$mailList->name" :back="route('admin.mail-lists.index')" back-label="Mail lists">
        <x-button-link href="#" icon="plus" data-toggle="modal" data-target="#add-members-modal">Add members</x-button-link>
        <x-delete-button :action="route('admin.mail-lists.destroy', $mailList->id)" title="Delete mail list" :confirm="'Delete '.$mailList->address.'? Mail to it stops arriving, and this can\'t be undone.'" button="Delete" />
    </x-page-header>
    <x-card title="Mail list" class="mt-4">
        <table class="table table-striped dt-responsive nowrap">
            <x-detail-row label="Name">{{$mailList->name}}</x-detail-row>
            <x-detail-row label="Email">{{$mailList->address}}</x-detail-row>
            <x-detail-row label="Member count">{{$mailList->members_count}}</x-detail-row>
        </table>
        <h4> Members</h4>
        <x-data-table datatable :columns="['Email', 'Name', 'Actions']">
            @foreach($mailList->members as $member)
                <tr>
                    <td>{{$member->address}}</td>
                    <td>{{$member->name}}</td>
                    <td>
                        <x-actions-menu :label="$member->address">
                            <x-actions-menu.delete :action="route('admin.mail-lists.members.destroy', [$mailList->id, $member->address])" title="Remove from mail list" :confirm="'Remove '.$member->address.' from '.$mailList->address.'?'">Remove from list</x-actions-menu.delete>
                        </x-actions-menu>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
    @include('admin.mail-lists.add-members-modal')
@endsection

