@extends('layouts.admin')

@section('title')
Page
@endsection

@section('content')
    <x-page-header title="Page">
        <x-button-link :href="route('admin.pages.edit', $page)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.pages.index')" icon="android-arrow-back">Back</x-button-link>
        @if($page->deletable)
            <x-delete-button :action="route('admin.pages.destroy', $page)" confirm="Are you sure you want to delete this page?" />
        @endif
    </x-page-header>
    <div class="card mt-4">
        <div class="card-header">
            <h3>Page {{$page->name}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <x-detail-row label="Name">{{$page->name}}</x-detail-row>
                <x-detail-row label="Sub page of">{{ $page->parent?->name }}</x-detail-row>
                <x-detail-row label="Slug">{{$page->slug}}</x-detail-row>
                <x-detail-row label="Requires login">{{ $page->requires_login ? 'Yes' : 'No' }}</x-detail-row>
                @foreach($children as $child)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $loop->count }}">Submenu items</td>
                        @endif
                        <td>{{ $child->name }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
