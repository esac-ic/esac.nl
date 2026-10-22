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
                <td>{{$certificate->name}}</td>
                <td>{{$certificate->abbreviation}}</td>
                <td>
                    <x-icon-link :href="route('admin.certificates.edit', $certificate)" icon="edit" title="Edit certificate" />
                    <x-icon-link :href="route('admin.certificates.show', $certificate)" icon="eye" title="Show certificate" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
