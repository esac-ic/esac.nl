@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    <x-form-card :$fields :cancel="$agendaItemCategory === null ? route('agendaItemCategories.index') : route('agendaItemCategories.show', $agendaItemCategory)">
        <x-field name="name" label="Name" :value="$agendaItemCategory?->name" required />
    </x-form-card>
@endsection
