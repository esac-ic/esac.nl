@extends('layouts.beheer')

@section('title')
{{'Application form details'}}
@endsection

@section('content')
    <x-page-header title="Application form details">
        <a href="{{url()->previous()}}" class="btn btn-primary">
            <em class="ion-android-arrow-back"></em> {{'Back'}}
        </a>
    </x-page-header>
    <x-card title="Details application form" class="mt-4">
        <table class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>{{'Field name'}}</td>
                <th>{{'Filled value'}}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($applicationDataRows as $applicationDataRow)
                <tr>
                    <td>{{$applicationDataRow->formRow->name}}</td>
                    <td>{{$applicationDataRow->formatted_value}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-card>
@endsection
