@extends('layouts.admin')

@section('title')
Event categories
@endsection

@section('content')
    <x-page-header title="Event categories">
        <x-button-link :href="route('admin.event-categories.create')" icon="plus">New category</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Actions']">
        @foreach ($eventCategories as $category)
            <tr>
                <td><a href="{{ route('admin.event-categories.show', $category) }}">{{ $category->name }}</a></td>
                <td>
                    <x-actions-menu :label="$category->name">
                        <x-actions-menu.item :href="route('admin.event-categories.show', $category)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.event-categories.edit', $category)" icon="pencil-square">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
