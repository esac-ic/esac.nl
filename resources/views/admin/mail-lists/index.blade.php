@extends('layouts.admin')

@section('title')
Mail lists
@endsection

@section('content')
    <x-page-header title="Mail lists">
        <form method="post"
              action="{{ route('admin.mail-lists.sync') }}"
              onsubmit="return confirm('Are you sure?\nThis action adds the members to all mail lists of their member type.\nThis may add people again who unsubscribed from a mail list.')"
        >
            @csrf
            <button title="Sync member mail lists" type="submit" class="btn btn-primary"><span class="ion-loop" aria-hidden="true"></span> Sync member mail lists</button>
        </form>    
        <a href="{{route('admin.mail-lists.create')}}" class="btn btn-primary" title="New mail list">
            <span class="ion-plus" aria-hidden="true"></span>
            New mail list
        </a>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Email', 'Members count', 'Actions']">
        @foreach ($mailLists as $mailList)
            <tr>
                <td>{{$mailList->name}}</td>
                <td>{{$mailList->address}}</td>
                <td>{{$mailList->members_count}}</td>
                <td>
                    <a href="{{ route('admin.mail-lists.show', $mailList->address) }}"><span title="Show mail list" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
