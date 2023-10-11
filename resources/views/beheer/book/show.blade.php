@extends('layouts.beheer')

@section('title')
{{'Books'}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Books'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/books/'.$book->id . '/edit' )}}" class="btn btn-primary">
                    <em class="ion-edit"></em> {{'Edit'}}
                </a>
                <a href="{{url('/books/')}}" class="btn btn-block btn-primary">
                    <em class="ion-android-arrow-back"></em> {{'Back'}}
                </a>
                {{ Form::open(array('url' => 'books/' . $book->id, 'method' => 'delete')) }}
                <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{'Remove'}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{'Book'}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{'Title'}}</td>
                    <td>{{ $book->title }}</td>
                </tr>
                <tr>
                    <td>{{'Year'}}</td>
                    <td>{{ $book->year }}</td>
                </tr>
                <tr>
                    <td>{{'Country'}}</td>
                    <td>{{ $book->country }}</td>
                </tr>
                <tr>
                    <td>{{'Type'}}</td>
                    <td>{{ $book->type }}</td>
                </tr>
                <tr>
                    <td>{{'Code'}}</td>
                    <td>{{ $book->code }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection