@extends('layouts.beheer')

@section('title')
Mailing list
@endsection

@section('content')
    <x-page-header title="Mailing list">
        <form method="post"
              action="{{ route('beheer.mail-lists.sync') }}"
              onsubmit="return confirm('Are you sure?\nThis action adds users to all maillists associated with their membership type.\nThis may cause people who have unsubscribed from a maillist to be readded.')"
        >
            @csrf
            <button title="Sync member mail lists" type="submit" class="btn btn-primary"><span class="ion-loop" aria-hidden="true"></span> Sync member mail lists</button>
        </form>    
        <a href="{{route('beheer.mail-lists.create')}}" class="btn btn-primary" title="New mailing list">
            <span class="ion-plus" aria-hidden="true"></span>
            New mailing list
        </a>
    </x-page-header>
    <x-data-table :columns="['Name', 'Email', 'Members count', 'Actions']">
        @foreach ($mailLists as $mailingList)
            <tr>
                <td>{{$mailingList->name}}</td>
                <td>{{$mailingList->address}}</td>
                <td>{{$mailingList->members_count}}</td>
                <td>
                    <a href="{{ route('beheer.mail-lists.show', $mailingList->address) }}"><span title="Show mailing list" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
