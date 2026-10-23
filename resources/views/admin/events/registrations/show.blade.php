@extends('layouts.admin')

@section('title')
Registration
@endsection

@section('content')
    <x-page-header title="Registration">
        <x-button-link :href="route('admin.events.registrations.index', $event)" icon="android-arrow-back">Back</x-button-link>
    </x-page-header>
    <x-card title="Answers" class="mt-4">
        <x-data-table :columns="['Field', 'Answer']">
            @foreach($answers as $answer)
                <tr>
                    <td>{{ $answer->field->name }}</td>
                    <td>{{ $answer->formatted_value }}</td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
@endsection
