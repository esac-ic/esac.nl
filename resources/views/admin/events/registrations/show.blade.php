@extends('layouts.admin')

@section('title')
Registration
@endsection

@section('content')
    <x-page-header title="Registration" :back="route('admin.events.registrations.index', $event)" back-label="Registrations">
    </x-page-header>
    <x-card title="Answers" class="mt-4">
        <x-data-table :columns="['Field', 'Answer']">
            @foreach($answers as $answer)
                <tr>
                    <td>{{ $answer->question }}</td>
                    <td>{{ $answer->value }}</td>
                </tr>
            @endforeach
        </x-data-table>
    </x-card>
@endsection
