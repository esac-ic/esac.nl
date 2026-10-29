@extends('layouts.admin')

@section('title')
Sponsors
@endsection

@section('content')
    <x-page-header title="Sponsors">
        <x-button-link :href="route('admin.sponsors.create')" icon="plus">New sponsor</x-button-link>
    </x-page-header>
    <p class="text-muted">The footer shows these logos, in this order.</p>
    <x-data-table :columns="['Logo', 'Name', 'Website', 'Actions']">
        @foreach ($sponsors as $sponsor)
            <tr>
                <td><img src="{{ $sponsor->getFirstMediaUrl('logo', 'footer') }}" alt="" class="img-thumbnail bg-dark" width="120"></td>
                <td>{{ $sponsor->name }}</td>
                <td>{{ $sponsor->url }}</td>
                <td>
                    <x-actions-menu :label="$sponsor->name">
                        <x-actions-menu.item :href="route('admin.sponsors.edit', $sponsor)" icon="pencil-square">Edit</x-actions-menu.item>
                        <x-actions-menu.delete :action="route('admin.sponsors.destroy', $sponsor)" title="Remove sponsor" :confirm="'Remove '.$sponsor->name.'?'">Remove</x-actions-menu.delete>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
