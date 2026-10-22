@extends('layouts.admin')

@section('title')
{{$registrationForm->name}}
@endsection

@section('content')
    <x-page-header :title="$registrationForm->name">
        <x-edit-button :href="route('admin.registration-forms.edit', $registrationForm)" />
        <x-back-button :href="route('admin.registration-forms.index')" />
        <x-delete-button :action="route('admin.registration-forms.destroy', $registrationForm)" confirm="Are you sure you want to delete this registration form?" />
    </x-page-header>
    <x-card title="Fields" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <thead>
            <th>Name</td>
            <th>Type</td>
            <th>Value required</td>
            <th>Options</td>
            </thead>
            <tbody>
            @foreach($registrationForm->fields as $field)
                <tr>
                    <td>{{$field->name}}</td>
                    <td>{{$field->type}}</td>
                    <td>{{ $field->required ? 'Yes' : 'No' }}</td>
                    <td>
                        @if ($field->options->count() > 0)
                            <ul>
                                @foreach($field->options as $option)
                                    <li>{{ $option->name }} - {{ $option->value }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>
@endsection
