@extends('layouts.admin')

@section('title')
{{$registrationForm->name}}
@endsection

@section('content')
    <x-page-header :title="$registrationForm->name">
        <x-button-link :href="route('admin.registration-forms.edit', $registrationForm)" icon="edit">Edit</x-button-link>
        <x-button-link :href="route('admin.registration-forms.index')" icon="android-arrow-back">Back</x-button-link>
        <x-delete-button :action="route('admin.registration-forms.destroy', $registrationForm)" confirm="Are you sure you want to delete this registration form?" />
    </x-page-header>
    <x-card title="Fields" class="mt-4">
        <x-data-table :columns="['Name', 'Type', 'Required', 'Options']">
            @foreach($registrationForm->fields as $field)
                <tr>
                    <td>{{$field->name}}</td>
                    <td>{{ $field->type->label() }}</td>
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
        </x-data-table>
    </x-card>
@endsection
