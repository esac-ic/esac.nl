@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <x-page-header title="Pages">
        <x-button-link :href="route('admin.pages.create')" icon="plus">New page</x-button-link>
    </x-page-header>
    {{-- In menu order; the arrows move a page up or down among its siblings. --}}
    <x-data-table :columns="['Name', 'Sub page of', 'Actions']">
        @foreach ($pages as $page)
            <tr>
                <td>{{ $page->parent_id ? '— ' : '' }}{{ $page->name }}</td>
                <td>{{ $pages->firstWhere('id', $page->parent_id)?->name }}</td>
                <td>
                    <form class="d-inline" method="POST" action="{{ route('admin.pages.move', $page) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-link p-0" name="direction" value="up" title="Move up"><span class="ion-arrow-up-c font-size-120" aria-hidden="true"></span></button>
                        <button class="btn btn-link p-0" name="direction" value="down" title="Move down"><span class="ion-arrow-down-c font-size-120" aria-hidden="true"></span></button>
                    </form>
                    <x-icon-link :href="route('admin.pages.edit', $page)" icon="edit" title="Edit page" />
                    <x-icon-link :href="route('admin.pages.show', $page)" icon="eye" title="Show page" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
