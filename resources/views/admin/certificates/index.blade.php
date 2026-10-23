@extends('layouts.admin')

@section('title')
Certificates
@endsection

@section('content')
    <x-page-header title="Certificates">
        <x-button-link :href="route('admin.certificates.create')" icon="plus">New certificate</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Abbreviation', 'Actions']">
        @foreach ($certificates as $certificate)
            <tr>
                <td><a href="{{ route('admin.certificates.show', $certificate) }}">{{ $certificate->name }}</a></td>
                <td>{{$certificate->abbreviation}}</td>
                <td>
                    <x-actions-menu :label="$certificate->name">
                        <x-actions-menu.item :href="route('admin.certificates.show', $certificate)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.certificates.edit', $certificate)" icon="edit">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
