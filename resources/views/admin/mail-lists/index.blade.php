@extends('layouts.admin')

@section('title')
Mail lists
@endsection

@section('content')
    <x-page-header title="Mail lists">
        <form method="post" action="{{ route('admin.mail-lists.sync') }}"
              data-confirm-title="Sync member mail lists"
              data-confirm="Add every member to the mail lists of their member type? This also adds people back who unsubscribed from a list."
              data-confirm-button="Sync" data-confirm-danger="false">
            @csrf
            <button title="Sync member mail lists" type="submit" class="btn btn-primary"><span class="ion-loop" aria-hidden="true"></span> Sync member mail lists</button>
        </form>    
        <x-button-link :href="route('admin.mail-lists.create')" icon="plus">New mail list</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email', 'Member count', 'Actions']">
        @foreach ($mailLists as $mailList)
            <tr>
                <td><a href="{{ route('admin.mail-lists.show', $mailList->id) }}">{{ $mailList->name }}</a></td>
                <td>{{$mailList->address}}</td>
                <td>{{$mailList->members_count}}</td>
                <td>
                    <x-actions-menu :label="$mailList->name">
                        <x-actions-menu.item :href="route('admin.mail-lists.show', $mailList->id)" icon="eye">View members</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
