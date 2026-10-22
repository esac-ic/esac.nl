@extends('layouts.beheer')

@section('title')
Event categories
@endsection

@section('content')
    <x-page-header title="Event categories">
        <x-button-link :href="route('beheer.agenda-item-categories.create')" icon="plus">New category</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Name', 'Actions']">
        @foreach ($agendaItemCategories as $category)
            <tr>
                <td>{{$category->name}}</td>
                <td>
                    <x-icon-link :href="route('beheer.agenda-item-categories.edit', $category)" icon="edit" title="Edit category" />
                    <x-icon-link :href="route('beheer.agenda-item-categories.show', $category)" icon="eye" title="Show category" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
