@extends('layouts.admin')

@section('title')
{{$registrationForm->name}}
@endsection

@section('content')
    <x-page-header :title="$registrationForm->name" :back="route('admin.registration-forms.index')" back-label="Registration forms">
        <x-button-link :href="route('admin.registration-forms.edit', $registrationForm)" icon="edit">Edit</x-button-link>
        <x-delete-button :action="route('admin.registration-forms.destroy', $registrationForm)" title="Remove registration form" :confirm="'Remove the registration form '.$registrationForm->name.'?'" />
    </x-page-header>
    <x-card title="Fields" class="mt-4">
        <x-data-table :columns="['Name', 'Type', 'Required', 'Options']">
            @forelse($registrationForm->fields as $field)
                <tr>
                    <td>{{$field->name}}</td>
                    <td>{{ $field->type->label() }}</td>
                    <td>{{ $field->required ? 'Yes' : 'No' }}</td>
                    <td>
                        @if ($field->options->count() > 0)
                            <ul class="mb-0 pl-3">
                                @foreach($field->options as $option)
                                    <li>{{ $option->name }} - {{ $option->value }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted">No fields yet</td></tr>
            @endforelse
        </x-data-table>
    </x-card>
@endsection
