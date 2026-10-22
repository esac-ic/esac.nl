@extends('layouts.beheer')

@section('title')
{{'Application forms'}}
@endsection

@section('content')
    @include('includes.flash')

    <x-page-header title="Application forms">
        <x-button-link :href="route('beheer.application-forms.create')" icon="plus">New application form</x-button-link>
    </x-page-header>
    <x-data-table :columns="['Name', 'Management']">
        @foreach ($applicationForms as $applicationForm)
            <tr>
                <td>{{$applicationForm->name}}</td>
                <td>
                    <x-icon-link :href="route('beheer.application-forms.edit', $applicationForm)" icon="edit" title="Edit application form" />
                    <x-icon-link :href="route('beheer.application-forms.show', $applicationForm)" icon="eye" title="Show application form" />
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
