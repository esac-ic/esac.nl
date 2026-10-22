@extends('layouts.admin')

@section('title')
Registration
@endsection

@section('content')
    <x-page-header title="Registration">
        <a href="{{url()->previous()}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em> Back
        </a>
    </x-page-header>
    <x-card title="Answers" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>Field</td>
                <th>Answer</td>
            </tr>
            </thead>
            <tbody>
            @foreach($answers as $answer)
                <tr>
                    <td>{{ $answer->field->name }}</td>
                    <td>{{ $answer->formatted_value }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>
@endsection
