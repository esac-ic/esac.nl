@extends('layouts.beheer')

@section('title')
{{'Pages'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Pages">
        <x-button-link :href="route('beheer.pages.create')" icon="plus">New page</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Name', 'Sub item of', 'After menu item', 'Beheer']">
        @foreach ($pages as $menu)
            <tr>
                <td>{{$menu->name}}</td>
                <td>{{ $menu->partner?->name }}</td>
                <td>{{ $menu->afterItem?->name }}</td>
                <td>
                    <x-icon-link :href="route('beheer.pages.edit', $menu)" icon="edit" title="Edit page" />
                    <x-icon-link :href="route('beheer.pages.show', $menu)" icon="eye" title="Show page" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
