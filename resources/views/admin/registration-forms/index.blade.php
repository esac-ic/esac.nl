@extends('layouts.admin')

@section('title')
Registration forms
@endsection

@section('content')
    <x-page-header title="Registration forms">
        <x-button-link :href="route('admin.registration-forms.create')" icon="plus">New registration form</x-button-link>
    </x-page-header>
    <x-data-table datatable :columns="['Name', 'Actions']">
        @foreach ($registrationForms as $registrationForm)
            <tr>
                <td><a href="{{ route('admin.registration-forms.show', $registrationForm) }}">{{ $registrationForm->name }}</a></td>
                <td>
                    <x-actions-menu :label="$registrationForm->name">
                        <x-actions-menu.item :href="route('admin.registration-forms.show', $registrationForm)" icon="eye">View</x-actions-menu.item>
                        <x-actions-menu.item :href="route('admin.registration-forms.edit', $registrationForm)" icon="edit">Edit</x-actions-menu.item>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
