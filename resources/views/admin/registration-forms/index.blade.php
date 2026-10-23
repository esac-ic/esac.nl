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
                <td>{{$registrationForm->name}}</td>
                <td>
                    <x-icon-link :href="route('admin.registration-forms.edit', $registrationForm)" icon="edit" title="Edit registration form" />
                    <x-icon-link :href="route('admin.registration-forms.show', $registrationForm)" icon="eye" title="Show registration form" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
