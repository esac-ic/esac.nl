@extends('layouts.app')

@section('content')
<div class="container intro-container" id="app">
    {{ html()->form('POST', $route)->open() }}
        <x-card :title="$applicationForm->name">
            <registration-form rows="{{ json_encode($rows) }}"></registration-form>
        </x-card>

        <div class="my-4">
            {{ html()->submit('Submit')->class('btn btn-primary') }}
            <a class="btn btn-danger btn-close" href="{{$cancleRoute}}">{{'Cancel'}}</a>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
