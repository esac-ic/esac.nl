@extends('layouts.admin')

@section('title')
Page
@endsection

@section('content')
    <x-page-header title="Page" :back="route('admin.pages.index')" back-label="Pages">
        <x-button-link :href="route('admin.pages.edit', $page)" icon="edit">Edit</x-button-link>
        @if($page->deletable)
            <x-delete-button :action="route('admin.pages.destroy', $page)" title="Remove page" :confirm="'Remove the page '.$page->name.'?'.($page->children()->exists() ? ' Its sub pages become top-level pages.' : '')" />
        @endif
    </x-page-header>
    <x-card :title="'Page '.$page->name" class="mt-4">
        <table class="table table-striped mb-0">
            <x-detail-row label="Name">{{$page->name}}</x-detail-row>
            <x-detail-row label="Sub page of">{{ $page->parent?->name }}</x-detail-row>
            <x-detail-row label="URL">{{$page->slug}}</x-detail-row>
            <x-detail-row label="Requires login">{{ $page->requires_login ? 'Yes' : 'No' }}</x-detail-row>
            @foreach($children as $child)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{ $loop->count }}">Sub pages</td>
                    @endif
                    <td>{{ $child->name }}</td>
                </tr>
            @endforeach
        </table>
    </x-card>
@endsection
