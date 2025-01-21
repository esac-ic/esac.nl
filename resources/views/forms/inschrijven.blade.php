@extends('layouts.app')

@section('content')
<div class="container intro-container" id="app">
    {{ html()->form('POST', $route)->open() }}
        <div class="card">
            <div class="card-header">
                <h3>{{ $applicationForm->name }}</h3>
            </div>
            <div class="card-body">
                <registration-form rows="{{ json_encode($rows) }}"></registration-form>
            </div>
        </div>

        <div class="my-4">
            {{ html()->submit('Submit')->class('btn btn-primary') }}
            <a class="btn btn-danger btn-close" href="{{url($cancleRoute)}}">{{'Cancel'}}</a>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
