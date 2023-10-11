@extends('layouts.beheer')

@section('title')
{{'Roles'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Roles'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/rols/'.$rol->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{url('/rols/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
                {{ Form::open(array('url' => 'rols/' . $rol->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> Remove</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>Role</h3>
        </div>
        <div class="card-body">
            {{'Name'. ": " . $rol->name}}
        </div>
    </div>
@endsection
