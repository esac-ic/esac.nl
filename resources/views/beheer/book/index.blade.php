@extends('layouts.beheer')

@section('title')
{{trans("book.books")}}
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
            <h1>{{trans("book.books")}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('books/create')}}" class="btn btn-primary">
                    <span title="{{trans("user.new")}}" class="ion-plus" aria-hidden="true"></span>
                    {{trans("book.new")}}
                </a>
            </div>
        </div>
    </div>
    <table id="users" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
        <tr>
            <th>{{trans('book.title')}}</th>
            <th>{{trans('book.type')}}</th>
            <th>{{trans('menu.beheer')}}</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($books as $book)
            <tr >
                <td>{{$book->title}}</td>
                <td>{{$book->type}}</td>
                <td>
                    <a href="{{url('/books/' . $book->id . '/edit')}}"><span title="{{trans('book.edit')}}" class="ion-edit" aria-hidden="true"></span></a>
                    <a href="{{url('/books/'. $book->id)}}"><span title="{{trans("book.show")}}" class="ion-eye" aria-hidden="true"></span></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection