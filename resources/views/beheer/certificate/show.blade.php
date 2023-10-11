@extends('layouts.beheer')

@section('title')
{{'Certificate'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Certificate'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/certificates/'.$certificate->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{url('/certificates/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> Back
                </a>
                {{ Form::open(array('url' => 'certificates/' . $certificate->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Role'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>
                        {{'Name'}}
                    </td>
                    <td>
                        {{$certificate->name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{'Abbreviation'}}
                    </td>
                    <td>
                        {{$certificate->abbreviation}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
