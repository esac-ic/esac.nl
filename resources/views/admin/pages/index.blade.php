@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <x-page-header title="Pages">
        <x-button-link :href="route('admin.pages.create')" icon="plus">New page</x-button-link>
    </x-page-header>
    {{-- In menu order; the arrows move a page up or down among its siblings. --}}
    <x-data-table :columns="['Name', 'Sub page of', 'Order', 'Actions']">
        @foreach ($pages as $page)
            <tr>
                <td>{{ $page->parent_id ? '— ' : '' }}<a href="{{ route('admin.pages.show', $page) }}">{{ $page->name }}</a></td>
                <td>{{ $page->parent?->name }}</td>
                <td class="text-nowrap">
                    <form class="d-inline" method="POST" action="{{ route('admin.pages.move', $page) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-link p-0 mr-2" name="direction" value="up" title="Move {{ $page->name }} up" aria-label="Move {{ $page->name }} up"><span class="ion-arrow-up-c font-size-120" aria-hidden="true"></span></button>
                        <button class="btn btn-link p-0" name="direction" value="down" title="Move {{ $page->name }} down" aria-label="Move {{ $page->name }} down"><span class="ion-arrow-down-c font-size-120" aria-hidden="true"></span></button>
                    </form>
                </td>
                <td>
                    <x-actions-menu :label="$page->name">
                        <x-actions-menu.item :href="route('admin.pages.show', $page)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.pages.edit', $page)" icon="edit">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
