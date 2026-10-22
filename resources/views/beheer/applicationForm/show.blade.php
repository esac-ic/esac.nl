@extends('layouts.beheer')

@section('title')
{{$applicationForm->name}}
@endsection

@section('content')
    <x-page-header :title="$applicationForm->name">
        <x-edit-button :href="route('beheer.application-forms.edit', $applicationForm)" />
        <x-back-button :href="route('beheer.application-forms.index')" />
        <x-delete-button :action="route('beheer.application-forms.destroy', $applicationForm)" confirm="Are you sure you want to delete this application form?" />
    </x-page-header>
    <x-card title="Form rows" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <thead>
            <th>Name</td>
            <th>Row type</td>
            <th>Value required</td>
            <th>Options</td>
            </thead>
            <tbody>
            @foreach($applicationForm->applicationFormRows as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>{{$row->type}}</td>
                    <td>{{ $row->required ? 'Yes' : 'No' }}</td>
                    <td>
                        @if ($row->applicationFormRowOptions->count() > 0)
                            <ul>
                                @foreach($row->applicationFormRowOptions as $rowOption)
                                    <li>{{ $rowOption->name }} - {{ $rowOption->value }}</li>
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
