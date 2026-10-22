@extends('layouts.admin')

@section('title')
Pages
@endsection

@section('content')
    <x-page-header title="Pages">
        <x-button-link :href="route('admin.pages.create')" icon="plus">New page</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Sub item of', 'Position', 'Actions']">
        @foreach ($pages as $page)
            <tr>
                <td>{{$page->name}}</td>
                <td>{{ $page->parent?->name }}</td>
                <td>{{ $page->position }}</td>
                <td>
                    <x-icon-link :href="route('admin.pages.edit', $page)" icon="edit" title="Edit page" />
                    <x-icon-link :href="route('admin.pages.show', $page)" icon="eye" title="Show page" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
