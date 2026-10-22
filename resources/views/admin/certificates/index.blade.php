@extends('layouts.admin')

@section('title')
Certificate
@endsection

@section('content')
    <x-page-header title="Certificate">
        <x-button-link :href="route('admin.certificates.create')" icon="plus">Make a new certificate</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Name', 'Abbreviation', 'Management']">
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
