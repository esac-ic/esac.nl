@extends('layouts.beheer')

@section('title')
Page
@endsection

@section('content')
    <x-page-header title="Page">
        <x-button-link :href="route('beheer.pages.edit', $page)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('beheer.pages.index')" icon="android-arrow-back">Back</x-button-link>
        @if($page->deletable)
            <x-delete-button :action="route('beheer.pages.destroy', $page)" confirm="Are you sure you want to delete this page?" />
        @endif
    </x-page-header>
    <div class="card mt-4">
        <div class="card-header">
            <h3>Page {{$page->name}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <x-detail-row label="Name">{{$page->name}}</x-detail-row>
                <x-detail-row label="Sub item of">{{ $page->parent?->name }}</x-detail-row>
                <x-detail-row label="URL name">{{$page->url_name}}</x-detail-row>
                <x-detail-row label="User needs to be logged in to view the page">{{($page->login)? 'Yes' : 'No'}}</x-detail-row>
                <x-detail-row label="After menu item">{{ $page->afterItem?->name }}</x-detail-row>
                @foreach($subItems as $subItem)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $loop->count }}">{{'Submenu items'}}</td>
                        @endif
                        <td>{{ $subItem->name }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
