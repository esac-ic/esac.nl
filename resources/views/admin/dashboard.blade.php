@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-page-header title="Dashboard" />

    <div class="list-group">
        @can('manage-members')
            <a href="{{ route('admin.pending-members.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                Pending members
                <span @class(['badge badge-pill', 'badge-warning' => $pendingMembers > 0, 'badge-light' => $pendingMembers === 0])>{{ $pendingMembers }}</span>
            </a>
        @endcan
        @can('view-members')
            <a href="{{ route('admin.members.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                Current members
                <span class="badge badge-pill badge-light">{{ $activeMembers }}</span>
            </a>
        @endcan
        @can('manage-events')
            <a href="{{ route('admin.events.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                Upcoming events
                <span class="badge badge-pill badge-light">{{ $upcomingEvents }}</span>
            </a>
        @endcan
        @can('view-member-log')
            <a href="{{ route('admin.member-log.index') }}" class="list-group-item list-group-item-action">Member log</a>
        @endcan
    </div>
@endsection
