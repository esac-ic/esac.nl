@extends('layouts.beheer')

@section('title')
{{'Books'}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'Books'}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('books/create')}}" class="btn btn-primary">
                    <span title="{{'New book'}}" class="ion-plus" aria-hidden="true"></span>
                    {{'New book'}}
                </a>
                
                <a href="{{url('books/exportLibrary')}}" class="btn btn-primary">
                    <span title="{{'Export library'}}" class="ion-android-download" aria-hidden="true"></span>
                    {{'Export books'}}
                </a>
                
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{'Title'}}</th>
            <th>{{'Year'}}</th>
            <th>{{'Country'}}</th>
            <th>{{'Type'}}</th>
            <th>{{'Code'}}</th>
            <th>{{'Management'}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($books as $book)
            <tr >
                <td>{{$book->title}}</td>
                <td>{{$book->year}}</td>
                <td>{{$book->country}}</td>
                <td>{{$book->type}}</td>
                <td>{{$book->code}}</td>
                <td>
                    <a class="mr-1 ml-1" href="{{url('/books/' . $book->id . '/edit')}}"><span title="{{'Edit book'}}" class="ion-edit font-size-120" aria-hidden="true"></span></a>
                    <a class="mr-1 ml-1" href="{{url('/books/'. $book->id)}}"><span title="Show book" class="ion-eye font-size-120" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
