@extends('layouts.beheer')

@section('title')
{{'Application form details'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Application form details'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url()->previous()}}" class="btn btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Details application form'}}</h3>
        </div>
        <div class="card-body">
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
                        <td>{{$applicationDataRow->getApplicationFormRow->name}}</td>
                        <td>{{$applicationDataRow->formatted_value}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
